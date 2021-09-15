<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Support\Collection;

abstract class AbstractDevCommand extends AbstractCommand
{
    protected const LOCALE_FILE_DOES_NOT_EXIST = 'The translation file for "%s" locale does not exist. You could help by creating this file and sending a PR.';
    protected const WANT_TO_CREATE_FILE = 'Do you wish to create the file for "%s" locale?';

    protected ?Collection $availableLocales = null;
    protected ?Collection $requestedLocales = null;
    protected ?Collection $sourceLocales = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->availableLocales = $this->getAvailableLocales();

        $this->requestedLocales = $this->getRequestedLocales();

        $this->sourceKeys = $this->getNovaKeys();

        $this->requestedLocales->each(fn (string $locale) => $this->handleLocale($locale));
    }

    protected function directoryFrom(string $path = null): string
    {
        return $this->basePath("resources/lang/$path");
    }

    protected function directoryNovaSource(): string
    {
        return $this->basePath('vendor/laravel/nova/resources/lang');
    }

    protected function basePath(?string $path = null, bool $create = false): string
    {
        $path = rtrim(realpath(__DIR__ . '/../..') . '/' . $path, '/');

        if ($create) {
            $this->filesystem->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    protected function loadJson(string $path): mixed
    {
        if ($this->filesystem->exists($path)) {
            return json_decode($this->filesystem->get($path), true);
        }

        return [];
    }

    protected function loadText(string $path): string
    {
        if ($this->filesystem->exists($path)) {
            return $this->filesystem->get($path);
        }

        return '';
    }

    protected function saveJson(string $path, mixed $content): int
    {
        return $this->filesystem->put($path, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }

    protected function saveText(string $path, string $content): int
    {
        return $this->filesystem->put($path, rtrim($content) . PHP_EOL);
    }

    protected function getNovaKeys(): array
    {
        $sourceDirectory = $this->directoryNovaSource() . '/en';
        $sourceFile = "$sourceDirectory.json";

        if (!$this->filesystem->exists($sourceDirectory) || !$this->filesystem->exists($sourceFile)) {
            $this->error('The Nova language files were not found in the "vendor/laravel/nova" directory. Have you run `composer install`?');

            exit;
        }

        $novaKeys = array_values(array_diff(array_keys(json_decode($this->filesystem->get($sourceFile), true)), static::IGNORED_KEYS));
        $sourceKeys = [];

        // Workaround for keys that have been forgotten by Nova
        $forgottenKeys = [
            // After existing key => Insert new key(s)
            '90 Days' => '365 Days',
        ];

        foreach ($novaKeys as $novaKey) {
            $sourceKeys[] = $novaKey;

            if (array_key_exists($novaKey, $forgottenKeys)) {
                $sourceKeys = array_merge($sourceKeys, (array) $forgottenKeys[$novaKey]);
            }
        }

        return $sourceKeys;
    }
}