<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangMissing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-lang:missing
                            {locales? : Comma-separated list of languages}
                            {--all : Output all languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output missing keys from Laravel Nova language files to storage folder.';

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
        
        $outputDirectory = storage_path('app/nova-lang/missing');
        $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);
        
        $sourceKeys = array_keys(json_decode($this->filesystem->get($sourceFile), true));
        
        $availableLocales = $this->getAvailableLocales();
        
        $requestedLocales = $this->getRequestedLocales();
        
        if (!$requestedLocales->count()) {
            $this->error('You must either specify one or more locales, or use the --all option.');
            return;
        }

        $requestedLocales->each(function (string $locale) use ($availableLocales, $sourceKeys, $outputDirectory) {
            
            if (! $availableLocales->contains($locale)) {
                $this->warn(sprintf('The translation file for [%s] locale does not exist. You could help other people by creating this file and sending a PR :)', $locale));

                if (!$this->confirm(sprintf('Do you wish to create the file for [%s]?', $locale))) {
                    return;
                }
                
                $missingKeys = $sourceKeys;
            }
            else {
                $inputDirectory = $this->directoryFrom().'/'.$locale;

                $inputFile = $inputDirectory.'.json';

                $localeKeys = array_keys(json_decode($this->filesystem->get($inputFile), true));
                
                $localeKeys = array_map(function($key) {
                    return str_replace('\\\'', '\'', $key);
                }, $localeKeys);
                
                $missingKeys = array_diff($sourceKeys, $localeKeys);
            }
            
            $outputKeys = array_fill_keys($missingKeys, '');
            
            $outputFile = $outputDirectory.'/'.$locale.'.json';
            
            if (count($outputKeys)) {
                $this->filesystem->put($outputFile, json_encode($outputKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

                $this->info(sprintf('%d missing translation keys for [%s] locale have been output to [%s].', count($missingKeys), $locale, $outputFile));
            } elseif ($this->filesystem->exists($outputFile)) {
                    $this->warn(sprintf('[%s] locale has no missing translation keys. The existing output file at [%s] was deleted.', $locale, $outputFile));
                    $this->filesystem->delete($outputFile);
            } else {
                $this->warn(sprintf('[%s] locale has no missing translation keys. No output file was created.', $locale));
            }
        });
    }

    protected function getRequestedLocales(): Collection
    {
        if ($this->isAll()) {
            return $this->getAvailableLocales();
        }
        
        return collect(explode(',', $this->argument('locales')))->filter();
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

    protected function isAll(): bool
    {
        return $this->option('all');
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
