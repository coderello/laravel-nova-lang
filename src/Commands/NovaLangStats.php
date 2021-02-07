<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangStats extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-lang:stats';

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
        if (!config('app.debug')) {
            $this->error('This command will only run in debug mode.');

            return;
        }

        $sourceDirectory = $this->directoryNovaSource().'/en';
        $sourceFile = $sourceDirectory.'.json';

        if (!$this->filesystem->exists($sourceDirectory) || !$this->filesystem->exists($sourceFile)) {
            $this->error('The source language files were not found in the vendor/laravel/nova directory.');

            return;
        }

        $outputDirectory = storage_path('app/nova-lang');
        $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);

        $contributorsFile = __DIR__.'/../../contributors.json';
        $contributors = collect(json_decode($this->filesystem->get($contributorsFile), true));

        $sourceKeys = $this->getJsonKeys($sourceFile);
        $sourcePhpKeys = $this->getPhpKeys($this->directoryNovaSource().'/en');

        $sourceCount = count($sourceKeys) + count($sourcePhpKeys);
        $translatedCount = 0;

        $availableLocales = $this->getAvailableLocales();
        $blame = collect($this->getBlame());

        $caouecsLocales = $this->getCaouecsLocales();
        $allLocales = $caouecsLocales->merge($availableLocales)->unique()->values();

        $allLocales->each(function (string $locale) use ($contributors, $sourceKeys, $sourceCount, $sourcePhpKeys, $blame, &$translatedCount) {

            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $inputFile = $inputDirectory.'.json';

            $localeKeys = $this->getJsonKeys($inputFile);
            $missingKeys = array_diff($sourceKeys, $localeKeys);

            $localePhpKeys = $this->getPhpKeys($inputDirectory);
            $missingPhpKeys = array_diff($sourcePhpKeys, $localePhpKeys);

            $localeStat = $contributors->get($locale, [
                'name' => class_exists('Locale') ? \Locale::getDisplayName($locale) : $locale,
                'complete' => null,
                'contributors' => [],
            ]);

            $complete = $sourceCount - count($missingKeys) - count($missingPhpKeys);

            if (!is_null($complete) && $complete > 0) {

                if ($blameContributors = $blame->get($locale)) {
                    foreach ($blameContributors as $contributor => $lines) {
                        if (!($contributor == 'hivokas' && $lines == 3)) {
                            if (!isset($localeStat['contributors'][$contributor])) {
                                $localeStat['contributors'][$contributor] = $lines;
                            }
                            else {
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
                ->map(function($lines, $name) {
                    return compact('lines', 'name');
                })->sort(function($a, $b) {
                    return $a['lines'] === $b['lines'] ? $a['name'] <=> $b['name'] : 0 - ($a['lines'] <=> $b['lines']);
                })->map(function($contributor) {
                    return $contributor['lines'];
                })->all();
            }

            $contributors->put($locale, $localeStat);

        });

        $en = $contributors->pull('en');

        $contributors = $contributors->sort(function($a, $b) {
            return $a['complete'] === $b['complete'] ? $a['name'] <=> $b['name'] : 0 - ($a['complete'] <=> $b['complete']);
        });

        list($contributors, $missing) = $contributors->partition(function ($contribution, $locale) {
            return $contribution['complete'] > 0;
        });

        $contributors->prepend($en, 'en');

        $outputFile = $outputDirectory.'/contributors.json';

        $this->filesystem->put($outputFile, json_encode($contributors->merge($missing), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->info(sprintf('Updated "contributors.json" has been output to [%s].', $outputFile));
        $this->warn('* Replace ./contributors.json in your fork of the repository with this file.');

        $contributorsTable = $contributors->map(function($localeStat, $locale) use ($sourceCount) {

            $percent = $this->getPercent($localeStat['complete'], $sourceCount);
            $icon = $this->getPercentIcon($localeStat['complete'], $percent);

            $contributors = implode(', ', array_map(function($contributor) {
                if ($contributor == '(unknown)') {
                    return $contributor;
                }
                return sprintf('[%s](https://github.com/%s)', $contributor, $contributor);
            }, array_keys($localeStat['contributors'])));

            $hasPhp = $localeStat['php'] ? sprintf('[`json`](resources/lang/%s.json)', $locale) : '~~`php`~~';
            $hasJson = $localeStat['json'] ? sprintf('[`php`](resources/lang/%s)', $locale) : '~~`json`~~';

            return sprintf('| `%s` | %s | %s %s | ![%d (%s%%)](%s) | %s |', str_replace('-', '‑', $locale), $localeStat['name'], $hasJson, $hasPhp, $localeStat['complete'], $percent, $icon, $contributors);
        });

        $missing = $missing->sortBy(function($localeStat) {
            return $localeStat['name'];
        });

        $missing->transform(function($localeStat, $locale) use ($sourceCount) {

            $icon = $this->getPercentIcon(0, 0);
            return sprintf('| `%s` | %s | ![%d (%s%%)](%s) |', str_replace('-', '‑', $locale), $localeStat['name'], 0, 0, $icon);

        });

        $outputFile = $outputDirectory.'/README.excerpt.md';

        $languagesCount = $contributors->count();

        $sourceComplete = $sourceCount * $languagesCount;
        $percent = $this->getPercent($translatedCount, $sourceComplete);
        $countIcon = $this->getPercentIcon($languagesCount);
        $icon = $this->getPercentIcon($translatedCount, $percent);

        $totals = sprintf('Total languages ![%s](%s)  ', $languagesCount, $countIcon).PHP_EOL.
            sprintf('Total lines translated ![%s (%s%%)](%s)', number_format($translatedCount), $percent, $icon);

        $header = '## Available Languages'.PHP_EOL.PHP_EOL.
            'Note: There is no need to update the count of translated strings and add your username below, as this is done by script when your PR is merged.'.PHP_EOL.PHP_EOL.
            $totals.PHP_EOL.PHP_EOL.
            '| Code | Language | Translated files | Lines translated | Thanks to |'.PHP_EOL.
            '| --- | --- | --- | --- | --- |';

        $contents = $header.PHP_EOL.$contributorsTable->join(PHP_EOL);

        if ($missing->count()) {

            $parityCount = $caouecsLocales->intersect($availableLocales)->count();
            $caouecsCount = $caouecsLocales->count();

            $missingPercent = $this->getPercent($parityCount, $caouecsCount);
            $icon = $this->getPercentIcon($parityCount.'%2F'.$caouecsCount, $missingPercent);

            $totals = sprintf('Parity with `laravel-lang/lang` ![%d/%d (%s%%)](%s)', $parityCount, $caouecsCount, $missingPercent, $icon);

            $header = '## Missing Languages'.PHP_EOL.PHP_EOL.
                'The following languages are supported for the main Laravel framework by the excellent [laravel-lang/lang](https://github.com/laravel-lang/lang) package. We would love for our package to make these languages available for Nova as well. If you are able to contribute to any of these or other languages, please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.'.PHP_EOL.PHP_EOL.
                $totals.PHP_EOL.PHP_EOL.
                '| Code | Language | Lines translated |'.PHP_EOL.
                '| --- | --- | --- |';

            $contents .= PHP_EOL.PHP_EOL.$header.PHP_EOL.$missing->join(PHP_EOL);
        }

        $this->filesystem->put($outputFile, $contents);

        $this->info(sprintf('Updated "README.excerpt.md" has been output to [%s].', $outputFile));
        $this->warn('* Replace the Available Languages table in ./README.md in your fork of the repository with the contents of this file.');

        $outputFile = $outputDirectory . '/introduction.excerpt.md';

        $contributorsList = $contributors->map(function ($localeStat, $locale) use ($sourceCount) {

            $percent = $this->getPercent($localeStat['complete'], $sourceCount);

            return sprintf('* `%s` %s &middot; **%d (%s%%)**', str_replace('-', '‑', $locale), $localeStat['name'], $localeStat['complete'], $percent);
        });

        $totals = sprintf('Total languages **%s**  ', $languagesCount) . PHP_EOL .
            sprintf('Total lines translated **%s (%s%%)**', number_format($translatedCount), $percent);

        $header = '### Available Languages' . PHP_EOL . PHP_EOL .
            $totals . PHP_EOL;

        $contents = $header . PHP_EOL . $contributorsList->join(PHP_EOL);

        $contents .= PHP_EOL . PHP_EOL . 'See the full list of contributors on [GitHub](https://github.com/coderello/laravel-nova-lang#available-languages).';

        $this->filesystem->put($outputFile, $contents);

        $this->info(sprintf('Updated "introduction.excerpt.md" has been output to [%s].', $outputFile));
        $this->warn('* Replace the Available Languages list in ./docs/introduction.md in your fork of the repository with the contents of this file.');
    }

    protected function getPercent(int $complete, int $total): float
    {
        if ($total == 0) {
            return 0;
        }

        return $complete > $total ? 100 : round(($complete / $total) * 100, 1);
    }

    protected function getPercentIcon($complete, $percent = null): string
    {
        if (is_null($percent)) {
            return sprintf('https://img.shields.io/badge/%d-gray?style=flat-square', $complete);
        }

        $colors = [
            1   => 'red',
            85  => 'orange',
            90  => 'yellow',
            95  => 'green',
            100 => 'brightgreen',
        ];

        $percent = floor($percent);

        $colors = array_filter($colors, function($color, $limit) use ($percent) {
            return $percent >= $limit;
        }, ARRAY_FILTER_USE_BOTH);

        $color = array_pop($colors) ?: 'lightgray';

        $complete = ctype_digit($complete) ? number_format($complete) : $complete;

        return sprintf('https://img.shields.io/badge/%s-%s%%25-%s?style=flat-square', $complete, $percent, $color);
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

    protected function getCaouecsLocales(): Collection
    {
        if (!$this->filesystem->exists($this->directoryCaouecsSource())) {
            return collect();
        }

        $localesByDirectories = collect($this->filesystem->directories($this->directoryCaouecsSource().'/src'))
            ->map(function (string $path) {
                return $this->caouecsMapping($this->filesystem->name($path));
            });

        $localesByFiles = collect($this->filesystem->files($this->directoryCaouecsSource().'/json'))
            ->map(function (SplFileInfo $splFileInfo) {
                return $this->caouecsMapping($splFileInfo->getBasename('.'.$splFileInfo->getExtension()));
            });

        return $localesByDirectories->merge($localesByFiles)->unique()->values();
    }

    protected function getJsonKeys(string $path): array
    {
        if ($this->filesystem->exists($path)) {
            $json = json_decode($this->filesystem->get($path), true);

            if (!is_array($json)) {
                throw new \Exception('Invalid JSON file: '.$path);
            }

            return array_diff(array_keys($json), static::IGNORED_KEYS);
        }

        return [];
    }

    protected function getPhpKeys(string $path): array
    {
        return collect($this->filesystem->glob($path.'/*.php'))
            ->map(function (string $path) {
                $file = basename($this->filesystem->basename($path), '.php');

                $php = $this->filesystem->getRequire($path);

                if (!is_array($php)) {
                    throw new \Exception('Invalid JSON file: ' . $path);
                }

                $keys = collect(array_keys($php))
                    ->map(function ($key) use ($file) {
                        return "$file.$key";
                    });
                return $keys;
            })->flatten()->all();
    }

    protected function directoryFrom(): string
    {
        return base_path('vendor/coderello/laravel-nova-lang/resources/lang');
    }

    protected function directoryNovaSource(): string
    {
        return base_path('vendor/laravel/nova/resources/lang');
    }

    protected function directoryCaouecsSource(): string
    {
        return base_path('vendor/laravel-lang/lang');
    }

    protected function caouecsMapping(string $caouecs): string
    {
        $caouecs = str_replace('_', '-', $caouecs);

        $mapping = [
            'uz-cyrillic' => 'uz-Cyrl',
            'uz-latin'    => 'uz-Latn',
            'sr-cyrillic' => 'sr',
            'sr-cyrl'     => 'sr',
            'sr-latin'    => 'sr-Latn',
            'sr'          => 'sr-Latn',
            'me'          => 'cnr',
            'sr-latn-me'  => 'cnr',
        ];

        return $mapping[strtolower($caouecs)] ?? $caouecs;
    }

    protected function getBlame(): array
    {
        $token = env('GITHUB_TOKEN_NOVALANG');

        $contributions = ['en' => ['taylorotwell' => 10001, 'bonzai' => 10000, 'davidhemphill' => 10000, 'themsaid' => 10000]];

        if (!$token) {
            $this->error('To download newer contributions from GitHub, ensure the GITHUB_TOKEN_NOVALANG env key is set to a personal access token. Falling back to existing contributors list.');
            return $contributions;
        }

        $graphql = 'query { repository(owner: "coderello", name: "laravel-nova-lang") { pullRequests(last: 25, states: [MERGED]) { nodes { number title body state merged changedFiles files(first: 100) { nodes { path additions deletions } } author { login } } } } }';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.github.com/graphql',
            CURLOPT_USERAGENT => 'langcompare',
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

        if (!isset($result['data']['repository']['pullRequests']['nodes'])) {
            return $contributions;
        }

        $pullRequests = $result['data']['repository']['pullRequests']['nodes'];

        foreach ($pullRequests as $pullRequest) {
            if (!in_array($pullRequest['number'], [148, 156], true)) {
                $author = $pullRequest['author']['login'] ?? '(unknown)';

                foreach ($pullRequest['files']['nodes'] as $file) {
                    if (stripos($file['path'], 'resources/lang') === 0) {
                        $locale = preg_replace('@resources/lang/([^./]+)[./].*@', '$1', $file['path']);

                        if ($locale != 'cn' && $file['additions']) {
                            if (isset($contributions[$locale][$author])) {
                                $contributions[$locale][$author] += $file['additions'];
                            }
                            else {
                                $contributions[$locale][$author] = $file['additions'];
                            }
                        }
                    }
                }
            }
        }

        $collaborators = [
            'kitbs' => ['en', 'de', 'es', 'fr'],
            'hivokas' => ['ru'],
        ];

        foreach ($collaborators as $collaborator => $collaboratorLocales) {
            $collaboratorLocales = array_diff(array_keys($contributions), $collaboratorLocales);
            foreach ($collaboratorLocales as $locale) {
                unset($contributions[$locale][$collaborator]);
            }
        }

        return $contributions;
    }
}
