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

    protected function base_path(string $path): string
    {
        return realpath(__DIR__ . '/../..') . '/' . $path;
    }
}