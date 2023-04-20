<?php

namespace Coderello\LaravelNovaLang\Commands;

use Exception;
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
    public const SEPARATORS = '-‑_';

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
            /** @var string $locales */
            $locales = $this->argument('locales');
            $locales = $this->fixSeparators($locales);
            $locales = collect(explode(',', $locales))->filter();
        } else {
            $locales = collect();
        }

        if (! $locales->count()) {
            $this->error('You must either specify one or more locales, or use the --all option.');

            exit;
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
        return preg_replace('/[' . static::SEPARATORS . ']+/', $separator, $locale ?? '');
    }

    protected function isForce(): bool
    {
        return (bool) $this->option('force');
    }

    protected function isAll(): bool
    {
        return (bool) $this->option('all');
    }

    protected function directoryFrom(): string
    {
        if (function_exists('base_path')) {
            return base_path('vendor/coderello/laravel-nova-lang/resources/lang');
        }

        throw new Exception('Command cannot be run outside Laravel');
    }

    protected function directoryNovaSource(): string
    {
        if (function_exists('base_path')) {
            return base_path('vendor/laravel/nova/resources/lang');
        }

        throw new Exception('Command cannot be run outside Laravel');
    }

    protected function directoryTo(): string
    {
        if (function_exists('resource_path')) {
            return resource_path('lang/vendor/nova');
        }

        throw new Exception('Command cannot be run outside Laravel');
    }

    public function noLocalesRequested(Collection $requestedLocales): void
    {
        if (! $requestedLocales->count()) {
            $this->error('You must either specify one or more locales, or use the --all option.');

            exit;
        }
    }
}
