<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangPublish extends AbstractCommand
{
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
     * @return mixed
     */
    public function handle()
    {
        if ($this->formalLocalesRequested()) {
            return;
        }

        $availableLocales = $this->getAvailableLocales();

        $requestedLocales = $this->getRequestedLocales();

        $formalLocales = $this->getFormalLocales();

        if ($this->noLocalesRequested($requestedLocales)) {
            return;
        }

        $requestedLocales->each(function (string $alias, string $locale) use ($availableLocales) {

            if ($alias == 'en' && $this->isForce()) {
                if (!$this->confirm(sprintf('Are you sure you want to publish translations for [en] locale? This will overwrite the file from laravel/nova.'))) {
                    return;
                }
            }

            if (! $availableLocales->contains($locale)) {
                $this->warn(sprintf('Unfortunately, translations for [%s] locale don\'t exist. Feel free to send a PR to add them and help other people.', $locale));

                return;
            }

            $asAlias = '';

            if ($this->option('underscore')) {
                $alias = $this->fixSeparators($alias, '_');
            }

            if ($alias !== $locale) {
                $asAlias = sprintf(' as [%s]', $alias);
            }

            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $outputDirectory = $this->directoryTo().'/'.$alias;

            $inputFile = $inputDirectory.'.json';

            $outputFile = $outputDirectory.'.json';

            if (($this->filesystem->exists($outputDirectory)
                || $this->filesystem->exists($outputFile))
                && ! $this->isForce()) {
                $this->warn(sprintf('Translations for [%s] locale already exist%s. Use --force to overwrite.', $locale, $asAlias));

                return;
            }

            if ($this->filesystem->exists($inputDirectory)) {
                $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);

                $this->filesystem->copyDirectory($inputDirectory, $outputDirectory);
            }

            if ($this->filesystem->exists($inputFile)) {

                $this->filesystem->copy($inputFile, $outputFile);

            }

            $this->info(sprintf('Translations for [%s] locale have been published successfully%s.', $locale, $asAlias));
        });
    }

    protected function getRequestedLocales(): Collection
    {
        $locales = parent::getRequestedLocales();

        $aliases = $this->getLocaleAliases($locales->count() == 1 ? $locales->first() : false);

        $locales = $locales->mapWithKeys(function (string $locale, string $alias) use (&$aliases) {
            $alias = $aliases->pull($locale, $locale);

            return [$locale => $alias];
        });

        if ($aliases->count()) {
            $aliases = $aliases->map(function (string $locale, string $alias) {
                return "$alias:$locale";
            })->join(',');

            $this->info(sprintf('Aliases [%s] were not used by the selected locales.', $aliases));
        }

        return $locales;
    }

    protected function getLocaleAliases($single = false): Collection
    {
        $aliases = collect();

        $input = $this->option('alias');

        if ($input) {

            $inputs = explode(',', $input);

            if (strpos($input, ':') === false) {
                if ($single && count($inputs) == 1) {
                    return collect([$single => $input]);
                }

                $this->error('If publishing more than one locale, the aliases must be in the format "locale:alias,...".');
                exit;
            }
            elseif (substr_count($input, ':') < count($inputs)) {
                if ($single) {
                    $this->error('If publishing only one locale with a simple alias, only one alias should be passed.');
                }
                else {
                    $this->error('If publishing more than one locale, the aliases must be in the format "locale:alias,...".');
                }
                exit;
            }

            foreach ($inputs as $input) {
                @list($locale, $alias) = explode(':', $input);

                if (empty($alias) || empty($locale)) {
                    $this->error(sprintf('Alias [%s] is not valid.', $input));
                    exit;
                }

                if ($aliases->has($locale)) {
                    $this->warn(sprintf('Alias for [%s] locale was declared more than once and will be overwritten by the last value.', $locale));
                }


                $locale = $this->fixSeparators($locale);

                $aliases->put($locale, $alias);
            }
        }

        return $aliases;
    }
}
