<?php

namespace Coderello\LaravelNovaLang\Commands;

class NovaLangReorder extends AbstractDevCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reorder
                            {locales? : Comma-separated list of languages}
                            {--all : Output all languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reorder the keys from Laravel Nova language files to match the source file order and output to storage folder.';

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

        $sourceDirectory = $this->directoryNovaSource().'/en';
        $sourceFile = "$sourceDirectory.json";

        if (!$this->filesystem->exists($sourceDirectory) || !$this->filesystem->exists($sourceFile)) {
            $this->error('The source language files were not found in the vendor/laravel/nova directory.');

            return;
        }

        $novaKeys = array_values(array_diff(array_keys(json_decode($this->filesystem->get($sourceFile), true)), static::IGNORED_KEYS));
        $sourceKeys = [];

        // Workaround for keys that have been forgotten by Nova
        $forgottenKeys = [
            // After existing key => Insert new key(s)
            '90 Days' => '365 Days'
        ];

        foreach ($novaKeys as $novaKey) {
            $sourceKeys[] = $novaKey;

            if (array_key_exists($novaKey, $forgottenKeys)) {
                $sourceKeys = array_merge($sourceKeys, (array) $forgottenKeys[$novaKey]);
            }
        }

        $availableLocales = $this->getAvailableLocales();

        $requestedLocales = $this->getRequestedLocales();

        if ($this->noLocalesRequested($requestedLocales)) {
            return;
        }

        $requestedLocales->each(function (string $locale) use ($availableLocales, $sourceKeys) {

            if (! $availableLocales->contains($locale)) {
                return $this->error(sprintf('The translation file for [%s] locale does not exist. You could help other people by creating this file and sending a PR :)', $locale));
            }

            $inputFile = $this->directoryFrom()."/$locale.json";
            $outputFile = $inputFile;

            $localeTranslations = json_decode($this->filesystem->get($inputFile), true);

            $localeKeys = array_values(array_diff(array_keys($localeTranslations), static::IGNORED_KEYS));

            $reorderedKeys = array_diff_assoc(array_values(array_intersect($sourceKeys, $localeKeys)), $localeKeys);

            $missingKeys = [];

            if (count($reorderedKeys)) {

                $outputKeys = [];
                foreach ($sourceKeys as $key) {
                    if (isset($localeTranslations[$key])) {
                        $outputKeys[$key] = $localeTranslations[$key];
                    }
                    else {
                        $missingKeys[$key] = '';
                    }
                }

                $this->filesystem->put($outputFile, json_encode($outputKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

                $this->info(sprintf('%d translation keys for [%s] locale were out of order. The updated file has been output to [%s].', count($reorderedKeys), $locale, $outputFile));

            } else {
                $this->warn(sprintf('[%s] locale has no translation keys out of order.', $locale));
            }

            if (count($missingKeys)) {
                $this->warn(sprintf('Additionally, %d translation keys for [%s] locale were missing. Run the command `php nova-lang missing` to view them.', count($missingKeys), $locale));
            }

        });
    }
}
