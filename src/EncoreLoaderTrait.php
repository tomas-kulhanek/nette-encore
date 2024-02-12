<?php

declare(strict_types=1);

namespace vavo\EncoreLoader;

use Nette\DI\Attributes\Inject;

trait EncoreLoaderTrait
{
    #[Inject]
    public EncoreLoaderFactory $loader;

    protected function createComponentEncore(): EncoreLoaderComponent
    {
        return $this->loader->create();
    }
}
