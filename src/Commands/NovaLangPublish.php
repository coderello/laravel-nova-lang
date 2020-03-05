<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangPublish extends Command
{
    /**
     * Possible locale separators.
     * @var string
     */
    const SEPARATORS = '-‑_';

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
                            {--formal= : Publish the formal variants of certain locales, comma-separated}
                            {--force : Override existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Laravel Nova language files to resource folder.';

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
        if ($this->isFormalLocale($this->argument('locales')) || $this->isFormalLocale($this->option('alias'))) {
            $this->error('You must not specify the @formal suffix in the locales argument or --alias option.');
            return;
        }

        $availableLocales = $this->getAvailableLocales();

        $requestedLocales = $this->getRequestedLocales();

        $formalLocales = $this->getFormalLocales();

        if (!$requestedLocales->count()) {
            $this->error('You must either specify one or more locales, or use the --all option.');
            return;
        }

        $requestedLocales->each(function (string $alias, string $locale) use ($availableLocales, $formalLocales) {

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

            $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);

            $this->filesystem->copyDirectory($inputDirectory, $outputDirectory);

            $this->filesystem->copy($inputFile, $outputFile);

            if ($formalLocales->contains($locale)) {
                $inputFileFormal = $inputDirectory . '@formal.json';

                if ($this->filesystem->exists($inputFileFormal)) {
                    $formalKeys = json_decode($this->filesystem->get($inputFileFormal), true);
                    $informalKeys = json_decode($this->filesystem->get($inputFile), true);

                    $outputKeys = array_merge($informalKeys, $formalKeys);

                    $this->filesystem->put($outputFile, json_encode($outputKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

                    return $this->info(sprintf('<fg=green;options=bold>Formal</> translations for [%s] locale have been published successfully.', $locale, $asAlias));
                }

                $this->warn(sprintf('There is no formal variant for [%s] locale. The ordinary translation file will be published instead.', $locale, $asAlias));
            }

            $this->info(sprintf('Translations for [%s] locale have been published successfully%s.', $locale, $asAlias));
        });
    }

    protected function getRequestedLocales(): Collection
    {
        if ($this->isAll()) {
            $locales = $this->getAvailableLocales();
        }
        elseif ($this->argument('locales')) {
            $locales = $this->fixSeparators($this->argument('locales'));
            $locales = collect(explode(',', $locales))->filter();
        }
        else {
            return collect();
        }

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

    protected function getFormalLocales(): Collection
    {
        if (!$this->option('formal')) {
            return collect();
        }

        if ($this->isAllFormal()) {
            $locales = $this->getAvailableFormalLocales();
        } else {
            $locales = $this->fixSeparators($this->option('formal'));
            $locales = collect(explode(',', $locales))->filter();
        }

        return $locales;
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
            })->filter(function ($locale) {
                return !$this->isFormalLocale($locale);
            });

        return $localesByDirectories->intersect($localesByFiles)->values();
    }

    protected function getAvailableFormalLocales(): Collection
    {
        return collect($this->filesystem->files($this->directoryFrom()))
            ->map(function (SplFileInfo $splFileInfo) {
                return str_replace('.'.$splFileInfo->getExtension(), '', $splFileInfo->getFilename());
            })->filter(function ($locale) {
                return $this->isFormalLocale($locale);
            })->map(function ($locale) {
                return $this->stripFormalLocale($locale);
            })->values();
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

    protected function fixSeparators(?string $locale, string $separator = '-'): string
    {
        return preg_replace('/['.static::SEPARATORS.']+/', $separator, $locale);
    }

    public function isFormalLocale(?string $locale): bool
    {
        return stripos($locale, '@formal') !== false;
    }

    public function stripFormalLocale(?string $locale): string
    {
        return str_replace('@formal', '', $locale);
    }

    protected function isForce(): bool
    {
        return $this->option('force');
    }

    protected function isAll(): bool
    {
        return $this->option('all');
    }

    protected function isAllFormal(): bool
    {
        return $this->option('formal') == '*';
    }

    protected function directoryFrom(): string
    {
        return base_path('vendor/coderello/laravel-nova-lang/resources/lang');
    }

    protected function directoryTo(): string
    {
        return resource_path('lang/vendor/nova');
    }
}
