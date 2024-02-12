<?php

declare(strict_types=1);

namespace vavo\EncoreLoader;

use JsonException;

use function in_array;

final class EncoreLoaderService
{
    private string $outDir;

    private readonly string $defaultEntry;

    /** @var string[] */
    private array $files = [];

    public function __construct(array $encoreConfig)
    {
        $this->outDir = $encoreConfig['outDir'];
        $this->defaultEntry = $encoreConfig['defaultEntry'];
    }

    /**
     * @throws JsonException
     */
    public function getAsset(string $asset): string
    {
        $path = realpath($this->outDir) . DIRECTORY_SEPARATOR . 'manifest.json';

        if (!is_file($path)) {
            return '';
        }
        $json = file_get_contents($path);

        if ($json !== false) {
            $content = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            if (isset($content[$asset])) {
                return $content[$asset];
            }

            $foundAssets = preg_grep('/' . preg_quote($asset, '/') . '$/', array_keys($content));

            if ($foundAssets !== false && $foundAssets !== []) {
                return $content[current($foundAssets)];
            }
        }

        return '';
    }

    /**
     * @return mixed[]
     * @throws JsonException
     */
    public function getFiles(string $type, ?string $entry = null): array
    {
        $entryPoints = $this->getEntryPoints();

        if ($entry === null) {
            $entry = $this->defaultEntry;
        }

        if (!isset($entryPoints[$entry][$type])) {
            return [];
        }

        return $this->checkDuplicity($entryPoints[$entry][$type]);
    }

    /**
     * @return mixed[]
     * @throws JsonException
     */
    public function getEntryPoints(): array
    {
        $path = $this->outDir . 'entrypoints.json';
        if (is_file($path)) {
            $json = file_get_contents($path);

            if ($json !== false) {
                $content = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

                return $content['entrypoints'] ?? [];
            }
        }

        return [];
    }

    /**
     * @param mixed[] $entryPoints
     *
     * @return mixed[]
     */
    private function checkDuplicity(array $entryPoints): array
    {
        foreach ($entryPoints as $key => $entryPoint) {
            if (in_array($entryPoint, $this->files, true)) {
                unset($entryPoints[$key]);
            } else {
                $this->files[] = $entryPoint;
            }
        }

        return $entryPoints;
    }

    public function setOutDir(string $outDir): void
    {
        $this->outDir = $outDir;
    }
}
