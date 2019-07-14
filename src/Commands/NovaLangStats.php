<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangStats extends Command
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
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        parent::__construct();
    }

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

        $sourceKeys = array_keys(json_decode($this->filesystem->get($sourceFile), true));

        if (!in_array(':resource Details', $sourceKeys)) { // Temporary fix until laravel/nova#463 is merged
            $sourceKeys = array_unique(array_merge($sourceKeys, [
                'Action',
                'Changes',
                'Original',
                'This resource no longer exists',
                ':resource Details',
            ]));
        }

        $sourceCount = count($sourceKeys);

        $availableLocales = $this->getAvailableLocales();
        $blame = collect($this->getBlame());

        $availableLocales->each(function (string $locale) use ($contributors, $sourceKeys, $sourceCount, $blame) {

            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $inputFile = $inputDirectory.'.json';

            $localeKeys = array_keys(json_decode($this->filesystem->get($inputFile), true));

            $missingKeys = array_diff($sourceKeys, $localeKeys);

            $localeStat = $contributors->get($locale, [
                'name' => class_exists('Locale') ? \Locale::getDisplayName($locale) : $locale,
                'contributors' => [],
            ]);

            if ($blameContributors = $blame->get($locale)) {
                $localeStat['contributors'] = array_merge($localeStat['contributors'], $blameContributors);
            }

            $localeStat['complete'] = $sourceCount - count($missingKeys);

            $localeStat['contributors'] = collect($localeStat['contributors'])
                ->map(function($lines, $name) {
                    return compact('lines', 'name');
                })->sort(function($a, $b) {
                return $a['lines'] === $b['lines'] ? $a['name'] <=> $b['name'] : 0 - ($a['lines'] <=> $b['lines']);
            })->map(function($contributor) {
                return $contributor['lines'];
            })->all();

            $contributors->put($locale, $localeStat);

        });

        $contributors = $contributors->sort(function($a, $b) {
            return $a['complete'] === $b['complete'] ? $a['name'] <=> $b['name'] : 0 - ($a['complete'] <=> $b['complete']);
        });

        $outputFile = $outputDirectory.'/contributors.json';

        $this->filesystem->put($outputFile, json_encode($contributors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->info(sprintf('Updated "contributors.json" has been output to [%s].', $outputFile));
        $this->warn('* Replace contributors.json in your fork of the repository with this file.');

        $contributors->transform(function($localeStat, $locale) use ($sourceCount) {

            $percent = round(($localeStat['complete'] / $sourceCount) * 100, 1).'%';

            $contributors = implode(', ', array_map(function($contributor) {
                if ($contributor == '(deleted)') {
                    return $contributor;
                }
                return sprintf('[%s](https://github.com/%s)', $contributor, $contributor);
            }, array_keys($localeStat['contributors'])));

            return sprintf('| %s | [%s](resources/lang/%s.json) | %d (%s) | %s |', $localeStat['name'], $locale, $locale, $localeStat['complete'], $percent, $contributors);
        });

        $outputFile = $outputDirectory.'/README.excerpt.md';

        $header = '## Available Languages'.PHP_EOL.PHP_EOL.'| Language | Code | Lines translated | Thanks to |'.PHP_EOL.'| --- | --- | --- | --- |';

        $this->filesystem->put($outputFile, $header.PHP_EOL.$contributors->join(PHP_EOL));

        $this->info(sprintf('Updated "README.excerpt.md" has been output to [%s].', $outputFile));
        $this->warn('* Replace the Available Languages table in README.md in your fork of the repository with the contents of this file.');
    }

    protected function getAvailableLocales(): Collection
    {
        $localesByDirectories = collect($this->filesystem->directories($this->directoryFrom()))
            ->map(function (string $path) {
                return $this->filesystem->name($path);
            });

        $localesByFiles = collect($this->filesystem->files($this->directoryFrom()))
            ->map(function (SplFileInfo $splFileInfo) {
                return str_replace('.'.$splFileInfo->getExtension(), '', $splFileInfo->getFilename());
            });

        return $localesByDirectories->intersect($localesByFiles)->values();
    }

    protected function directoryFrom(): string
    {
        return base_path('vendor/coderello/laravel-nova-lang/resources/lang');
    }

    protected function directoryNovaSource(): string
    {
        return base_path('vendor/laravel/nova/resources/lang');
    }

    protected function getBlame(): array
    {
        $token = env('GITHUB_TOKEN_NOVALANG');

        $contributions = ['en' => ['taylorotwell' => 10001, 'bonzai' => 10000, 'davidhemphill' => 10000, 'themsaid' => 10000]];

        if (!$token) {
            $this->error('To download newer contributions from GitHub, ensure the GITHUB_TOKEN_NOVALANG env key is set to a personal access token. Falling back to existing contributors list.');
            return $contributions;
        }

        $graphql = 'query { repository(owner: "coderello", name: "laravel-nova-lang") { pullRequests(first: 100, states: [MERGED]) { nodes { number title body state merged changedFiles files(first: 100) { nodes { path additions deletions } } author { login } } } } }';

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
                $author = $pullRequest['author']['login'] ?? '(deleted)';

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

        return $contributions;
    }
}
