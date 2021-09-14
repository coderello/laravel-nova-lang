<?php

namespace Coderello\LaravelNovaLang\Commands;

abstract class AbstractDevCommand extends AbstractCommand
{
    protected function directoryFrom(): string
    {
        return $this->base_path('resources/lang');
    }

    protected function directoryNovaSource(): string
    {
        return $this->base_path('vendor/laravel/nova/resources/lang');
    }

    protected function base_path(?string $path = null): string
    {
        return rtrim(realpath(__DIR__ . '/../..') . '/' . $path, '/');
    }

    protected function saveJson(string $path, mixed $content)
    {
        $this->filesystem->put($path, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }

    protected function saveText(string $path, string $content)
    {
        $this->filesystem->put($path, rtrim($content) . PHP_EOL);
    }

    protected function getNovaKeys(): array
    {
        $sourceDirectory = $this->directoryNovaSource() . '/en';
        $sourceFile = "$sourceDirectory.json";

        if (!$this->filesystem->exists($sourceDirectory) || !$this->filesystem->exists($sourceFile)) {
            return [];
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