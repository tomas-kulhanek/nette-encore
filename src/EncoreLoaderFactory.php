<?php

declare(strict_types=1);

namespace vavo\EncoreLoader;

interface EncoreLoaderFactory
{
    public function create(): EncoreLoaderComponent;
}
