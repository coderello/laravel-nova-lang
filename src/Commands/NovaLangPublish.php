<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Support\Collection;

class NovaLangPublish extends AbstractCommand
{
    protected const CONFIRM_EN_OVERWRITE = 'Are you sure you want to publish translations for "en" locale? This will overwrite the original file from laravel/nova.';
    protected const CONFIRM_OVERWRITE = 'Translations for "%s" locale already exist%s. Use --force to overwrite.';
    protected const LOCALE_NOT_EXIST = 'Unfortunately, translations for "%s" locale don\'t exist. Feel free to send a PR to coderello/laravel-nova-lang to add this locale and help others.';
    protected const PUBLISHED_SUCCESSFULLY = 'Translations for "%s" locale have been published successfully%s.';
    protected const ALIASES_NOT_USED = 'Aliases "%s" were not used by the selected locales.';
    protected const ALIAS_WRONG_FORMAT = 'If publishing more than one locale, the aliases must be in the format "locale:alias,...".';
    protected const ONLY_ONE_ALIAS = 'If publishing only one locale with a simple alias, only one alias should be passed.';
    protected const ALIAS_NOT_VALID = 'Alias "%s" is not valid.';
    protected const ALIAS_DECLARED_MORE_THAN_ONCE = 'Alias for "%s" locale was declared more than once and will be overwritten by the last value.';
    protected const AS_ALIAS = ' as "%s"';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-lang:publish
                            {locales? : Comma-separated list of languages}
                            {--all : Publish all languages}
                            {--alias= : Publish files using a different filename for certain locales, in the format "locale:alias,..."}
                            {--U|underscore : Use underscore instead of dash as locale separator }
                            {--force : Override existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Laravel Nova language files to resource folder.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $availableLocales = $this->getAvailableLocales();

        $requestedLocales = $this->getRequestedLocales();

        $requestedLocales->each(function (string $alias, string $locale) use ($availableLocales) {
            if ($alias == 'en' && $this->isForce()) {
                if (! $this->confirm(sprintf(static::CONFIRM_EN_OVERWRITE))) {
                    return;
                }
            }

            if (! $availableLocales->contains($locale)) {
                $this->warn(sprintf(static::LOCALE_NOT_EXIST, $locale));

                return;
            }

            $asAlias = '';

            if ($this->option('underscore')) {
                $alias = $this->fixSeparators($alias, '_');
            }

            if ($alias !== $locale) {
                $asAlias = sprintf(static::AS_ALIAS, $alias);
            }

            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $outputDirectory = $this->directoryTo().'/'.$alias;

            $inputFile = $inputDirectory.'.json';

            $outputFile = $outputDirectory.'.json';

            if (($this->filesystem->exists($outputDirectory) || $this->filesystem->exists($outputFile)) && ! $this->isForce()) {
                $this->warn(sprintf(static::CONFIRM_OVERWRITE, $locale, $asAlias));

                return;
            }

            if ($this->filesystem->exists($inputDirectory)) {
                $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);

                $this->filesystem->copyDirectory($inputDirectory, $outputDirectory);
            }

            if ($this->filesystem->exists($inputFile)) {
                $this->filesystem->copy($inputFile, $outputFile);
            }

            $this->info(sprintf(static::PUBLISHED_SUCCESSFULLY, $locale, $asAlias));
        });
    }

    protected function getRequestedLocales(): Collection
    {
        $locales = parent::getRequestedLocales();

        $aliases = $this->getLocaleAliases($locales->count() == 1 ? $locales->first() : false);

        $locales = $locales->mapWithKeys(function (string $locale) use (&$aliases) {
            $alias = $aliases->pull($locale, $locale);

            return [$locale => $alias];
        });

        if ($aliases->count()) {
            $aliases = $aliases->map(function (string $locale, string $alias) {
                return "$alias:$locale";
            })->join(',');

            $this->warn(sprintf(static::ALIASES_NOT_USED, $aliases));
        }

        return $locales;
    }

    /**
     * Get aliases for locales.
     *
     * @param bool|string $single
     * @return Collection
     */
    protected function getLocaleAliases($single = false): Collection
    {
        $aliases = collect();

        /** @var string $input */
        $input = $this->option('alias');

        if ($input) {
            $inputs = explode(',', $input);

            if (strpos($input, ':') === false) {
                if ($single && count($inputs) == 1) {
                    return collect([(string) $single => $input]);
                }

                $this->error(static::ALIAS_WRONG_FORMAT);

                exit;
            } elseif (substr_count($input, ':') < count($inputs)) {
                if ($single) {
                    $this->error(static::ONLY_ONE_ALIAS);
                } else {
                    $this->error(static::ALIAS_WRONG_FORMAT);
                }

                exit;
            }

            foreach ($inputs as $input) {
                [$locale, $alias] = explode(':', $input);

                if (empty($alias) || empty($locale)) {
                    $this->error(sprintf(static::ALIAS_NOT_VALID, $input));
                    exit;
                }

                if ($aliases->has($locale)) {
                    $this->warn(sprintf(static::ALIAS_DECLARED_MORE_THAN_ONCE, $locale));
                }

                $locale = $this->fixSeparators($locale);

                $aliases->put($locale, $alias);
            }
        }

        return $aliases;
    }
}
