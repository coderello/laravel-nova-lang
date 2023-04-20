<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use SplFileInfo;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

class NovaLangStats extends AbstractDevCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect translation completion and contribution stats for documentation.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sourceDirectory = $this->directoryNovaSource().'/en';
        $sourceFile = $sourceDirectory.'.json';

        if (! $this->filesystem->exists($sourceDirectory) || ! $this->filesystem->exists($sourceFile)) {
            $this->error('The source language files were not found in the vendor/laravel/nova directory.');

            exit;
        }

        $contributorsFile = $this->basePath('contributors.json');
        $contributors = collect($this->loadJson($contributorsFile));

        $sourceKeys = $this->getJsonKeys($sourceFile);
        $sourcePhpKeys = $this->getPhpKeys($this->directoryNovaSource().'/en');

        $sourceCount = count($sourceKeys) + count($sourcePhpKeys);
        $translatedCount = 0;

        $availableLocales = $this->getAvailableLocales();
        $blame = collect($this->getBlame());

        $availableLocales->each(function (string $locale) use ($contributors, $sourceKeys, $sourceCount, $sourcePhpKeys, $blame, &$translatedCount) {
            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $inputFile = $inputDirectory.'.json';

            $localeKeys = $this->getJsonKeys($inputFile);
            $missingKeys = array_diff($sourceKeys, $localeKeys);

            $localePhpKeys = $this->getPhpKeys($inputDirectory);
            $missingPhpKeys = array_diff($sourcePhpKeys, $localePhpKeys);

            try {
                $displayName = class_exists('Locale') ? \Locale::getDisplayName($locale) : $locale;
            } catch (MethodNotImplementedException $e) {
                $displayName = $locale;
            }

            $localeStat = $contributors->get($locale, [
                'name' => $displayName,
                'complete' => null,
                'contributors' => [],
            ]);

            $complete = $sourceCount - count($missingKeys) - count($missingPhpKeys);

            if (! is_null($complete) && $complete > 0) {
                if ($blameContributors = $blame->get($locale)) {
                    foreach ($blameContributors as $contributor => $lines) {
                        if (! ($contributor == 'hivokas' && $lines == 3)) {
                            if (! isset($localeStat['contributors'][$contributor])) {
                                $localeStat['contributors'][$contributor] = $lines;
                            } else {
                                if ($lines > $localeStat['contributors'][$contributor]) {
                                    $localeStat['contributors'][$contributor] = $lines;
                                }
                            }
                        }
                    }
                }

                $translatedCount += $complete;

                $localeStat['complete'] = $complete;
                $localeStat['json'] = count($localeKeys) > 0;
                $localeStat['php'] = count($localePhpKeys) > 0;

                $localeStat['contributors'] = collect($localeStat['contributors'])
                    ->map(function ($lines, $name) {
                        return compact('lines', 'name');
                    })->sort(function ($a, $b) {
                        return $a['lines'] === $b['lines'] ? $a['name'] <=> $b['name'] : 0 - ($a['lines'] <=> $b['lines']);
                    })->map(function ($contributor) {
                        return $contributor['lines'];
                    })->all();
            }

            $contributors->put($locale, $localeStat);
        });

        // Update "contributors.json"

        $en = $contributors->pull('en');

        $contributors = $contributors->sort(function ($a, $b) {
            return $a['complete'] === $b['complete'] ? $a['name'] <=> $b['name'] : 0 - ($a['complete'] <=> $b['complete']);
        });

        list($contributors, $missing) = $contributors->partition(function ($contribution, $locale) {
            return $contribution['complete'] > 0;
        });

        $contributors->prepend($en, 'en');

        $outputFile = $this->basePath('contributors.json');

        $this->saveJson($outputFile, $contributors->merge($missing));

        $this->info('Updated "contributors.json"');

        // Update "README.md"

        $contributorsTable = $contributors->map(function ($localeStat, $locale) use ($sourceCount) {
            $percent = $this->getPercent($localeStat['complete'], $sourceCount);
            $icon = $this->getPercentBadge($localeStat['complete'], $percent);

            $contributors = implode(', ', array_map(function ($contributor) {
                if ($contributor == '(unknown)') {
                    return $contributor;
                }

                return sprintf('[%s](https://github.com/%s)', $contributor, $contributor);
            }, array_keys($localeStat['contributors'])));

            $hasPhp = $localeStat['php'] ? sprintf('[`json`](resources/lang/%s.json)', $locale) : '~~`php`~~';
            $hasJson = $localeStat['json'] ? sprintf('[`php`](resources/lang/%s)', $locale) : '~~`json`~~';

            return sprintf('| `%s` | %s | %s %s | ![%d (%s%%)](%s) | %s |', str_replace('-', '‑', $locale), $localeStat['name'], $hasJson, $hasPhp, $localeStat['complete'], $percent, $icon, $contributors);
        });

        $outputFile = $this->basePath('README.md');

        $languagesCount = $contributors->count();

        $sourceComplete = $sourceCount * $languagesCount;
        $percent = $this->getPercent($translatedCount, $sourceComplete);
        $countIcon = $this->getTextBadge($languagesCount);
        $icon = $this->getPercentBadge($translatedCount, $percent);

        $composer = $this->loadJson($this->basePath('composer.lock'))['packages-dev'];
        $package = array_filter($composer, fn ($package) => $package['name'] == 'laravel/nova');
        $novaVersion = array_shift($package)['version'];
        $versionIcon = $this->getTextBadge($novaVersion);

        $totals =
            sprintf('Latest Nova version ![%s](%s)  ', $novaVersion, $versionIcon) . PHP_EOL .
            sprintf('Total languages ![%s](%s)  ', $languagesCount, $countIcon) . PHP_EOL .
            sprintf('Total lines translated ![%s (%s%%)](%s)', number_format($translatedCount), $percent, $icon);

        $header = '## Available Languages' . PHP_EOL . PHP_EOL.
            'We welcome new languages and additions/improvements to existing languages! Please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.' . PHP_EOL . PHP_EOL .
            '**Note**: There is no need to update the count of translated strings and add your username below, as this is done by script when your PR is merged.' . PHP_EOL . PHP_EOL .
            $totals . PHP_EOL . PHP_EOL .
            '| Code | Language | Translated files | Lines translated | Thanks to |' . PHP_EOL .
            '| --- | --- | --- | --- | --- |';

        $contents = $header . PHP_EOL . $contributorsTable->join(PHP_EOL);

        $originalContents = $this->loadText($outputFile);

        $contents = preg_replace('/(.+)## Available Languages.+/sm', '$1' . $contents, $originalContents);

        $this->saveText($outputFile, $contents);

        $this->info('Updated "README.md"');

        // Update "docs/introduction.md"

        $outputFile = $this->basePath('docs/introduction.md');

        $contributorsList = $contributors->map(function ($localeStat, $locale) use ($sourceCount) {
            $percent = $this->getPercent($localeStat['complete'], $sourceCount);

            return sprintf('* `%s` %s &middot; **%d** (%s%%)', str_replace('-', '‑', $locale), $localeStat['name'], $localeStat['complete'], $percent);
        });

        $totals = sprintf('Latest Nova version **%s**  ', $novaVersion) . PHP_EOL .
            sprintf('Total languages **%s**  ', $languagesCount) . PHP_EOL .
            sprintf('Total lines translated **%s** (%s%%)', number_format($translatedCount), $percent);

        $header = '### Available Languages' . PHP_EOL . PHP_EOL .
            $totals . PHP_EOL;

        $contents = $header . PHP_EOL . $contributorsList->join(PHP_EOL);

        $contents .= PHP_EOL . PHP_EOL . 'See the full list of contributors on [GitHub](https://github.com/coderello/laravel-nova-lang#available-languages).';

        $originalContents = $this->loadText($outputFile);

        $contents = preg_replace('/^#+ Available Languages.+/sm', '$1' . $contents, $originalContents);

        $this->saveText($outputFile, $contents);

        $this->info('Updated "docs/introduction.md"');
    }

    protected function getPercent(int $complete, int $total): float
    {
        if ($total == 0) {
            return 0;
        }

        return $complete > $total ? 100 : round(($complete / $total) * 100, 1);
    }

    protected function getPercentBadge(float $complete, float $percent): string
    {
        $colors = [
            1 => 'red',
            85 => 'orange',
            90 => 'yellow',
            95 => 'green',
            100 => 'brightgreen',
        ];

        $percent = floor($percent);

        $colors = array_filter($colors, function ($color, $limit) use ($percent) {
            return $percent >= $limit;
        }, ARRAY_FILTER_USE_BOTH);

        $color = array_pop($colors) ?: 'lightgray';

        $complete = ctype_digit($complete) ? number_format($complete) : $complete;

        return sprintf('https://img.shields.io/badge/%s-%s%%25-%s?style=flat-square', $complete, $percent, $color);
    }

    protected function getTextBadge(string $text): string
    {
        return sprintf('https://img.shields.io/badge/%s-gray?style=flat-square', $text);
    }

    protected function getAvailableLocales(): Collection
    {
        $localesByDirectories = collect($this->filesystem->directories($this->directoryFrom()))
            ->map(function (string $path) {
                return $this->filesystem->name($path);
            });

        $localesByFiles = collect($this->filesystem->files($this->directoryFrom()))
            ->map(function (SplFileInfo $splFileInfo) {
                return $splFileInfo->getBasename('.'.$splFileInfo->getExtension());
            });

        return $localesByDirectories->merge($localesByFiles)->unique()->values();
    }

    protected function getJsonKeys(string $path): array
    {
        if ($this->filesystem->exists($path)) {
            $json = $this->loadJson($path);

            if (! is_array($json)) {
                throw new \Exception('Invalid JSON file: '.$path);
            }

            return array_diff(array_keys($json), $this->getIgnoredNovaKeys());
        }

        return [];
    }

    protected function getPhpKeys(string $path): array
    {
        return collect($this->filesystem->glob($path.'/*.php'))
            ->map(function (string $path) {
                $file = basename($this->filesystem->basename($path), '.php');

                $php = $this->filesystem->getRequire($path);

                if (! is_array($php)) {
                    throw new \Exception('Invalid JSON file: ' . $path);
                }

                $keys = collect(array_keys($php))
                    ->map(function ($key) use ($file) {
                        return "$file.$key";
                    });

                return $keys;
            })->flatten()->all();
    }

    protected function getBlame(): array
    {
        try {
            $token = $this->loadText('.github_token');
        } catch (FileNotFoundException $e) {
            $token = null;
        }

        $contributions = ['en' => ['taylorotwell' => 10001, 'bonzai' => 10000, 'davidhemphill' => 10000, 'themsaid' => 10000]];

        if (! $token) {
            $this->error('To download newer contributions from GitHub, create a file named .github_token in the package root directory which contains a personal access token. Falling back to existing contributors list.');

            return $contributions;
        }

        $graphql = 'query { repository(owner: "coderello", name: "laravel-nova-lang") { pullRequests(last: 25, states: [MERGED]) { nodes { number title body state merged changedFiles files(first: 100) { nodes { path additions deletions } } author { login } } } } }';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.github.com/graphql',
            CURLOPT_USERAGENT => 'coderello/laravel-nova-lang',
            CURLOPT_HTTPHEADER => [
                'Authorization: bearer '.$token,
                'Content-Type: application/json',
            ],
            CURLOPT_POST => 1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(['query' => $graphql]),
        ]);

        $result = json_decode(curl_exec($curl), true);

        curl_close($curl);

        if (! isset($result['data']['repository']['pullRequests']['nodes'])) {
            return $contributions;
        }

        $pullRequests = $result['data']['repository']['pullRequests']['nodes'];

        foreach ($pullRequests as $pullRequest) {
            if (! in_array($pullRequest['number'], [148, 156], true)) {
                $author = $pullRequest['author']['login'] ?? '(unknown)';

                foreach ($pullRequest['files']['nodes'] as $file) {
                    if (stripos($file['path'], 'resources/lang') === 0) {
                        $locale = preg_replace('@resources/lang/([^./]+)[./].*@', '$1', $file['path']);

                        if ($locale != 'cn' && $file['additions']) {
                            if (isset($contributions[$locale][$author])) {
                                $contributions[$locale][$author] += $file['additions'];
                            } else {
                                $contributions[$locale][$author] = $file['additions'];
                            }
                        }
                    }
                }
            }
        }

        $maintainers = [
            'kitbs' => ['en', 'de', 'es', 'fr'],
            'hivokas' => ['ru'],
        ];

        foreach ($maintainers as $maintainer => $maintainerLocales) {
            $maintainerLocales = array_diff(array_keys($contributions), $maintainerLocales);
            foreach ($maintainerLocales as $locale) {
                unset($contributions[$locale][$maintainer]);
            }
        }

        return $contributions;
    }
}
