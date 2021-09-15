<?php

namespace Coderello\LaravelNovaLang\Commands;

class NovaLangMissing extends AbstractDevCommand
{
    protected const MISSING_TEXT = '<MISSING>';
    protected const SAVED_MISSING_KEYS = '%d missing translation keys for "%s" locale have been added to [%s].';
    protected const REMOVE_MISSING_VALUES = 'Ensure you translate or remove all "%s" values before raising your PR.';
    protected const NO_MISSING_KEYS = '"%s" locale has no missing translation keys.';
    protected const RUN_COUNTRY_COMMAND = '%d of these missing keys are country names. Run the command `php nova-lang country %s` to automatically add them.';

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
    protected $description = 'Add missing keys from Nova language files.';

    /**
     * Handle the command for a given locale.
     *
     * @param string $locale
     * @return void
     */
    protected function handleLocale(string $locale): void
    {
        $inputFile = $this->directoryFrom("$locale.json");

        if (!$this->availableLocales->contains($locale)) {
            $this->warn(sprintf(static::LOCALE_FILE_DOES_NOT_EXIST, $locale));

            if (!$this->confirm(sprintf(static::WANT_TO_CREATE_FILE, $locale))) {
                return;
            }

            $missingKeys = $this->sourceKeys;
        } else {
            $localeTranslations = $this->loadJson($inputFile);

            $missingKeys = array_diff($this->sourceKeys, array_keys($localeTranslations));
        }

        $outputKeys = [];

        foreach ($this->sourceKeys as $sourceKey) {
            $outputKeys[$sourceKey] = $localeTranslations[$sourceKey] ?? static::MISSING_TEXT;
        }

        $outputFile = $inputFile;

        $countryKeys = array_values(NovaLangCountry::COUNTRY_KEYS);
        $countryKeys = count(array_intersect($missingKeys, $countryKeys));

        $missingKeys = count($missingKeys);

        if ($missingKeys > 0) {
            $this->saveJson($outputFile, $outputKeys);

            $this->info(sprintf(static::SAVED_MISSING_KEYS, $missingKeys, $locale, $outputFile));

            if ($countryKeys) {
                $this->comment('    ' . sprintf(static::RUN_COUNTRY_COMMAND, $countryKeys, $locale));
                $this->newLine();
            }

        } else {
            $this->info(sprintf(static::NO_MISSING_KEYS, $locale));
        }
    }

    protected function afterHandle()
    {
        $this->warn(sprintf(static::REMOVE_MISSING_VALUES, static::MISSING_TEXT));
    }
}
