<?php

declare(strict_types=1);

namespace vavo\EncoreLoader;

use JsonException;

final class CssLoaderComponent extends EncoreLoaderComponent
{
    private const Type = 'css';

    /**
     * @throws JsonException
     */
    public function render(?string $entry = null): void
    {
        $template = $this->getTemplate();

        $template->files = $this->encoreLoaderService->getFiles(self::Type, $entry);
        $template->setFile(__DIR__ . '/CssLoader.latte');

        $template->render();
    }
}
