<?php

declare(strict_types=1);

namespace vavo\EncoreLoader;
use Nette\Application\UI\Control;

class EncoreLoaderComponent extends Control
{
    public function __construct(protected EncoreLoaderService $encoreLoaderService)
    {
    }

    public function createComponentCss(): CssLoaderComponent
    {
        return new CssLoaderComponent($this->encoreLoaderService);
    }

    public function createComponentJs(): JsLoaderComponent
    {
        return new JsLoaderComponent($this->encoreLoaderService);
    }
}
