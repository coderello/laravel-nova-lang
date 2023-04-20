<?php

namespace Coderello\LaravelNovaLang\Commands;

class NovaLangCleanup extends AbstractDevCommand
{
    protected const MISSING_TEXT = '<MISSING>';
    protected const REMOVED_MISSING_KEYS = '%d missing or blank translation keys for "%s" locale were removed.';
    protected const NO_MISSING_KEYS = '"%s" locale has no missing or blank translation keys.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup
                            {locales? : Comma-separated list of languages}
                            {--all : Output all languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove missing or blank keys from Nova language files.';

    /**
     * Handle the command for a given locale.
     *
     * @param string $locale
     * @return void
     */
    protected function handleLocale(string $locale): void
    {
        $inputFile = $this->directoryFrom("$locale.json");

        if (! $this->availableLocales->contains($locale)) {
            $this->warn(sprintf(static::LOCALE_FILE_DOES_NOT_EXIST, $locale));

            return;
        }

        $inputKeys = $this->loadJson($inputFile);

        $outputKeys = array_filter($inputKeys, fn ($text) => ! empty(trim($text)) && $text !== static::MISSING_TEXT && ! empty(preg_replace('/\s+/u', '', $text)));

        $missingKeys = count($inputKeys) - count($outputKeys);

        $outputFile = $inputFile;

        if ($missingKeys > 0) {
            $this->saveJson($outputFile, $outputKeys);

            $this->info(sprintf(static::REMOVED_MISSING_KEYS, $missingKeys, $locale, $outputFile));
        } else {
            $this->info(sprintf(static::NO_MISSING_KEYS, $locale));
        }
    }

    protected function afterHandle()
    {
        //
    }
}
