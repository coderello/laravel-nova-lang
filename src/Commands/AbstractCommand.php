<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use SplFileInfo;

abstract class AbstractCommand extends Command
{
    /**
     * Possible locale separators.
     * @var string
     */
    const SEPARATORS = '-‑_';

    /**
     * @var string[]
     */
    const IGNORED_KEYS = ['*', '—'];

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

    protected function getRequestedLocales(): Collection
    {
        if ($this->isAll()) {
            $locales = $this->getAvailableLocales();
        } elseif ($this->argument('locales')) {
            $locales = $this->fixSeparators($this->argument('locales'));
            $locales = collect(explode(',', $locales))->filter();
        } else {
            $locales = collect();
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
                return str_replace('.' . $splFileInfo->getExtension(), '', $splFileInfo->getFilename());
            })->filter(function ($locale) {
                return !$this->isFormalLocale($locale);
            });

        return $localesByDirectories->merge($localesByFiles)->unique()->values();
    }

    protected function getFormalLocales(): Collection
    {
        if (!$this->hasOption('formal') || !$this->option('formal')) {
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

    protected function getAvailableFormalLocales(): Collection
    {
        return collect($this->filesystem->files($this->directoryFrom()))
            ->map(function (SplFileInfo $splFileInfo) {
                return str_replace('.' . $splFileInfo->getExtension(), '', $splFileInfo->getFilename());
            })->filter(function ($locale) {
                return $this->isFormalLocale($locale);
            })->map(function ($locale) {
                return $this->stripFormalLocale($locale);
            })->values();
    }

    protected function fixSeparators(?string $locale, string $separator = '-'): string
    {
        return preg_replace('/[' . static::SEPARATORS . ']+/', $separator, $locale);
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
        return $this->option('formal') ? $this->option('formal') == '*' : false;
    }

    protected function directoryFrom(): string
    {
        return base_path('vendor/coderello/laravel-nova-lang/resources/lang');
    }

    protected function directoryNovaSource(): string
    {
        return base_path('vendor/laravel/nova/resources/lang');
    }

    protected function directoryTo(): string
    {
        return resource_path('lang/vendor/nova');
    }

    public function formalLocalesRequested(): bool
    {
        if ($this->isFormalLocale($this->argument('locales')) || ($this->hasOption('alias') && $this->isFormalLocale($this->option('alias')))) {
            $this->error('You must not specify the @formal suffix in the locales argument or --alias option.');
            return true;
        }

        return false;
    }

    public function noLocalesRequested(Collection $requestedLocales): bool
    {
        if (!$requestedLocales->count()) {
            $this->error('You must either specify one or more locales, or use the --all option.');
            return true;
        }

        return false;
    }

}