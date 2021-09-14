<?php

namespace Coderello\LaravelNovaLang\Commands;

class NovaLangMissing extends AbstractDevCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missing
                            {locales? : Comma-separated list of languages}
                            {--all : Output all languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output missing keys from Laravel Nova language files to storage folder.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->formalLocalesRequested()) {
            return;
        }

        // $sourceDirectory = $this->directoryNovaSource().'/en';
        // $sourceFile = $sourceDirectory.'.json';

        // if (!$this->filesystem->exists($sourceDirectory) || !$this->filesystem->exists($sourceFile)) {
        //     $this->error('The source language files were not found in the vendor/laravel/nova directory.');

        //     return;
        // }

        $sourceKeys = $this->getNovaKeys();

        if (!count($sourceKeys)) {
            $this->error('The source language files were not found in the vendor/laravel/nova directory. Have you run `composer install`?');
            return;
        }

        $outputDirectory = $this->base_path('build/missing');
        $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);

        $availableLocales = $this->getAvailableLocales();

        $requestedLocales = $this->getRequestedLocales();

        if ($this->noLocalesRequested($requestedLocales)) {
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
                $inputFile = $this->directoryFrom() . "/$locale.json";

                $localeKeys = array_keys(json_decode($this->filesystem->get($inputFile), true));

                $localeKeys = array_map(function($key) {
                    return str_replace('\\\'', '\'', $key);
                }, $localeKeys);

                $missingKeys = array_diff($sourceKeys, $localeKeys);
            }

            $outputKeys = array_fill_keys($missingKeys, '');

            $outputFile = "$outputDirectory/$locale.json";

            if (count($outputKeys)) {
                $this->saveJson($outputFile, $outputKeys);

                $this->info(sprintf('%d missing translation keys for [%s] locale have been output to [%s].', count($missingKeys), $locale, $outputFile));
            } elseif ($this->filesystem->exists($outputFile)) {
                    $this->warn(sprintf('[%s] locale has no missing translation keys. The existing output file at [%s] was deleted.', $locale, $outputFile));
                    $this->filesystem->delete($outputFile);
            } else {
                $this->warn(sprintf('[%s] locale has no missing translation keys. No output file was created.', $locale));
            }
        });
    }
}
