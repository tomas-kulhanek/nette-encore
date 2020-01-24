# Nette Encore
Integration of  [symfony/webpack-encore-bundle](https://github.com/symfony/webpack-encore-bundle) into Nette project.

## Install
```bash
composer require vavo/nette-encore
```
## Usage
1 . Register extension.

```config
extensions:
	encoreLoader:
		- vavo\EncoreLoader\DI\EncoreLoaderExtensions
```

2 . Define encore properties in config.

```config
encoreLoader:
    outDir: "%wwwDir%/build/"
    defaultEntry: index
```
3 . Add trait into Presenter.

```php
<?php

declare(strict_types=1);

namespace App\Presenters;

use vavo\EncoreLoader\EncoreLoaderTrait;
use Nette;

class BasePresenter extends Nette\Application\UI\Presenter
{
	use EncoreLoaderTrait;
...
```

4 . Add control into @layout.latte. You can specify what file should be included.
```
{control encore-css}
{control encore-css, [filename]}
...
{control encore-js, index}
```

5 . Use latte macro anywhere you need.
```
{asset "build/images/logo.svg"}
```
