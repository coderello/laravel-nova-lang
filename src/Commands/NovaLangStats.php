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
        $sourceCount = count($sourceKeys);
        
        $availableLocales = $this->getAvailableLocales();
        
        $availableLocales->each(function (string $locale) use ($contributors, $sourceKeys, $sourceCount) {
            
            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $inputFile = $inputDirectory.'.json';

            $localeKeys = array_keys(json_decode($this->filesystem->get($inputFile), true));

            $missingKeys = array_diff($sourceKeys, $localeKeys);

            $localeStat = $contributors->get($locale, [
                'name' => class_exists('Locale') ? Locale::getDisplayName($locale) : $locale,
                'contributors' => [],
            ]);
            
            $localeStat['complete'] = $sourceCount - count($missingKeys);
            
            // TODO: Count contributions using git blame?
            
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
        
        $contributors = $contributors->sortByDesc('complete');
        
        $outputFile = $outputDirectory.'/contributors.json';
        
        $this->filesystem->put($outputFile, json_encode($contributors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        
        $this->info(sprintf('Updated "contributors.json" has been output to [%s].', $outputFile));
        $this->warn('* Replace contributors.json in your fork of the repository with this file.');
        
        $contributors->transform(function($localeStat, $locale) use ($sourceCount) {
            
            $percent = round(($localeStat['complete'] / $sourceCount) * 100, 1).'%';
            
            $contributors = implode(', ', array_map(function($contributor) {
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
}
