<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-lang:publish
                            {locales : Comma separated list of languages}
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
        $availableLocales = $this->getAvailableLocales();

        $this->getRequestedLocales()->each(function (string $locale) use ($availableLocales) {
            if (! $availableLocales->contains($locale)) {
                $this->error(sprintf('Unfortunately, translations for [%s] locale don\'t exist. Feel free to send a PR to add them and help other people :)', $locale));

                return;
            }

            $inputDirectory = $this->directoryFrom().'/'.$locale;

            $outputDirectory = $this->directoryTo().'/'.$locale;

            $inputFile = $inputDirectory.'.json';

            $outputFile = $outputDirectory.'.json';

            if (($this->filesystem->exists($outputDirectory)
                || $this->filesystem->exists($outputFile))
                && ! $this->isForce()) {
                $this->error(sprintf('Translations for [%s] locale already exist.', $locale));

                return;
            }

            $this->filesystem->makeDirectory($outputDirectory, 0777, true, true);

            $this->filesystem->copyDirectory($inputDirectory, $outputDirectory);

            $this->filesystem->copy($inputFile, $outputFile);

            $this->info(sprintf('Translations for [%s] locale have been published successfully.', $locale));
        });
    }

    protected function getRequestedLocales(): Collection
    {
        return collect(explode(',', $this->argument('locales')));
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
            });

        return $localesByDirectories->intersect($localesByFiles)->values();
    }

    protected function isForce(): bool
    {
        return $this->option('force');
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
