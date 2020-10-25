<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangReorder extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-lang:reorder
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
        if (!config('app.debug')) {
            $this->error('This command will only run in debug mode.');

            return;
        }

        if ($this->formalLocalesRequested()) {
            return;
        }

        $sourceDirectory = $this->directoryNovaSource().'/en';
        $sourceFile = $sourceDirectory.'.json';

        if (!$this->filesystem->exists($sourceDirectory) || !$this->filesystem->exists($sourceFile)) {
            $this->error('The source language files were not found in the vendor/laravel/nova directory.');

            return;
        }

        $outputDirectory = storage_path('app/nova-lang/reorder');
        $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);

        $sourceKeys = array_values(array_diff(array_keys(json_decode($this->filesystem->get($sourceFile), true)), static::IGNORED_KEYS));

        $availableLocales = $this->getAvailableLocales();

        $requestedLocales = $this->getRequestedLocales();

        if ($this->noLocalesRequested($requestedLocales)) {
            return;
        }

        $requestedLocales->each(function (string $locale) use ($availableLocales, $sourceKeys, $outputDirectory) {

            if (! $availableLocales->contains($locale)) {
                return $this->error(sprintf('The translation file for [%s] locale does not exist. You could help other people by creating this file and sending a PR :)', $locale));
            }

            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $inputFile = $inputDirectory.'.json';

            $localeTranslations = json_decode($this->filesystem->get($inputFile), true);

            $localeKeys = array_values(array_diff(array_keys($localeTranslations), static::IGNORED_KEYS));

            $reorderedKeys = array_diff_assoc(array_values(array_intersect($sourceKeys, $localeKeys)), $localeKeys);

            $outputFile = $outputDirectory.'/'.$locale.'.json';

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

                $this->info(sprintf('%d translation keys for [%s] locale were out of order. The reordered file has been output to [%s].', count($reorderedKeys), $locale, $outputFile));

            } elseif ($this->filesystem->exists($outputFile)) {
                    $this->warn(sprintf('[%s] locale has no translation keys out of order. The existing output file at [%s] was deleted.', $locale, $outputFile));
                    $this->filesystem->delete($outputFile);
            } else {
                $this->warn(sprintf('[%s] locale has no translation keys out of order. No output file was created.', $locale));
            }

            if (count($missingKeys)) {
                $this->warn(sprintf('Additionally, %d translation keys for [%s] locale were missing. Run the `nova-lang:missing` command to view them.', count($missingKeys), $locale));
            }

        });
    }
}
