<?php

namespace Coderello\LaravelNovaLang\Commands;

use Exception;

class NovaLangReorder extends AbstractDevCommand
{
    protected const KEYS_OUT_OF_ORDER = '%d translation keys for "%s" locale were out of order.';
    protected const RENAMED_KEYS = '%d keys were renamed for Nova 4.0.';
    protected const EXTRA_KEYS = '%d keys were not present in the Nova source file and have been removed.';
    protected const OUTPUT_FILE = 'The updated file has been output to [%s].';
    protected const NO_KEYS_OUT_OF_ORDER = '"%s" locale has no translation keys out of order.';
    protected const RUN_MISSING_COMMAND = '%d translation keys for "%s" locale were missing. Run the command `php nova-lang missing %2$s` to add them.';

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
        if (! $this->availableLocales->contains($locale)) {
            $this->error(sprintf(static::LOCALE_FILE_DOES_NOT_EXIST, $locale));

            exit;
        }

        $inputFile = $this->directoryFrom("$locale.json");
        $outputFile = $inputFile;

        $novaRenamedKeys = $this->getRenamedNovaKeys();

        $unsortedLocaleTranslations = $this->loadJson($inputFile);
        $localeTranslations = [];

        $renamedKeys = 0;

        foreach ($unsortedLocaleTranslations as $key => $translation) {
            if ($newKey = $novaRenamedKeys[$key] ?? false) {
                $localeTranslations[$newKey] = $translation;
                $renamedKeys++;
            } else {
                $localeTranslations[$key] = $translation;
            }
        }

        $localeKeys = array_diff(array_keys($localeTranslations), static::IGNORED_KEYS);

        $extraKeys = count(array_diff($localeKeys, $this->sourceKeys));

        $localeKeys = array_values(array_intersect($localeKeys, $this->sourceKeys));

        $commonKeys = array_values(array_intersect($this->sourceKeys, $localeKeys));

        $diffs = $this->array_diff_order($commonKeys, $localeKeys);

        if ($diffs > 0 || $renamedKeys > 0 || $extraKeys > 0) {
            $missingKeys = 0;

            $outputKeys = [];

            foreach ($this->sourceKeys as $key) {
                if (isset($localeTranslations[$key])) {
                    $outputKeys[$key] = $localeTranslations[$key];
                } else {
                    $missingKeys++;
                }
            }

            $this->saveJson($outputFile, $outputKeys);

            $this->info(sprintf(static::KEYS_OUT_OF_ORDER, $diffs, $locale));

            if ($renamedKeys > 0) {
                $this->info(sprintf(static::RENAMED_KEYS, $renamedKeys));
            }

            if ($extraKeys > 0) {
                $this->info(sprintf(static::EXTRA_KEYS, $extraKeys, $locale));
            }

            $this->info(sprintf(static::OUTPUT_FILE, $outputFile));

        } else {
            $this->info(sprintf(static::NO_KEYS_OUT_OF_ORDER, $locale));

            $missingKeys = count($this->sourceKeys) - count($commonKeys);
        }

        if ($missingKeys > 0) {
            $this->warn('    ' . sprintf(static::RUN_MISSING_COMMAND, $missingKeys, $locale));
            $this->newLine();
        }
    }

    protected function array_diff_order(array $array2, array $array1): int
    {
        if (count($array1) <> count($array2)) {
            throw new Exception('Both arrays must be the same size');
        }

        if (count(array_diff($array1, $array2)) > 0) {
            throw new Exception('Both arrays must contain the same values');
        }

        $diff = [];
        $moves = 0;

        for ($i = 0; $i < count($array1); $i++) {
            while ($array1[$i] != ($array2[$i] ?? null)) {
                if (in_array($array1[$i], $diff, true)) {
                    $moves++;
                    $diff = array_diff($diff, [$array1[$i]]);
                    array_splice($array2, $i, 0, $array1[$i]);
                } else {
                    $diff[] = $array2[$i];
                    unset($array2[$i]);
                }

                $array2 = array_values($array2);
            }
        }

        if (count($diff)) {
            $moves += count($diff);
        }

        return $moves;
    }
}
