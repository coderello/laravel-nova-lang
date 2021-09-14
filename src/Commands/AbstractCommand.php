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
            });

        return $localesByDirectories->merge($localesByFiles)->unique()->values();
    }

    protected function fixSeparators(?string $locale, string $separator = '-'): string
    {
        return preg_replace('/[' . static::SEPARATORS . ']+/', $separator, $locale);
    }

    protected function isForce(): bool
    {
        return $this->option('force');
    }

    protected function isAll(): bool
    {
        return $this->option('all');
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

    public function noLocalesRequested(Collection $requestedLocales): bool
    {
        if (!$requestedLocales->count()) {
            $this->error('You must either specify one or more locales, or use the --all option.');
            return true;
        }

        return false;
    }

}