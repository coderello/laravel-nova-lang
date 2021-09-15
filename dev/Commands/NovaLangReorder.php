<?php

namespace Coderello\LaravelNovaLang\Commands;

class NovaLangReorder extends AbstractDevCommand
{

    protected const KEYS_OUT_OF_ORDER = '%d translation keys for "%s" locale were out of order. The updated file has been output to [%s].';
    protected const NO_KEYS_OUT_OF_ORDER = '"%s" locale has no translation keys out of order.';
    protected const RUN_MISSING_COMMAND = 'Additionally, %d translation keys for "%s" locale were missing. Run the command `php nova-lang missing` to add them.';

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
    protected $description = 'Reorder the keys from Nova language files to match the source file order.';

    /**
     * Handle the command for a given locale.
     *
     * @param string $locale
     * @return void
     */
    public function handleLocale(string $locale)
    {
        if (!$this->availableLocales->contains($locale)) {
            $this->error(sprintf(static::LOCALE_FILE_DOES_NOT_EXIST, $locale));

            exit;
        }

        $inputFile = $this->directoryFrom("$locale.json");
        $outputFile = $inputFile;

        $localeTranslations = $this->loadJson($inputFile);

        $localeKeys = array_values(array_diff(array_keys($localeTranslations), static::IGNORED_KEYS));

        $reorderedKeys = array_diff_assoc(array_values(array_intersect($this->sourceKeys, $localeKeys)), $localeKeys);

        $missingKeys = [];

        if (count($reorderedKeys)) {

            $outputKeys = [];
            foreach ($this->sourceKeys as $key) {
                if (isset($localeTranslations[$key])) {
                    $outputKeys[$key] = $localeTranslations[$key];
                } else {
                    $missingKeys[$key] = '';
                }
            }

            $this->saveJson($outputFile, $outputKeys);

            $this->info(sprintf(static::KEYS_OUT_OF_ORDER, count($reorderedKeys), $locale, $outputFile));
        } else {
            $this->warn(sprintf(static::NO_KEYS_OUT_OF_ORDER, $locale));
        }

        if (count($missingKeys)) {
            $this->warn(sprintf(static::RUN_MISSING_COMMAND, count($missingKeys), $locale));
        }
    }
}
