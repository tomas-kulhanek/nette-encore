<?php

declare(strict_types=1);

namespace vavo\EncoreLoader\DI;

use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\Statement;
use Nette\DI\InvalidConfigurationException;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use vavo\EncoreLoader\EncoreLoaderFactory;
use vavo\EncoreLoader\EncoreLoaderService;

final class EncoreLoaderExtension extends CompilerExtension
{
    public function getConfigSchema(): Schema
    {
        parent::getConfigSchema();

        return Expect::structure(
            [
                'outDir'       => Expect::string()->default('\build'),
                'defaultEntry' => Expect::string()->default('index'),
            ]
        );
    }

    public function loadConfiguration(): void
    {
        $containerBuilder = $this->getContainerBuilder();
        $definition = $containerBuilder->getDefinitionByType(LatteFactory::class);

        $containerBuilder->addDefinition($this->prefix('encoreLoaderService'))
            ->setFactory(EncoreLoaderService::class, [(array)$this->config]);

        if (!$definition instanceof FactoryDefinition) {
            throw new InvalidConfigurationException(
                sprintf(
                    'latte.latteFactory service definition must be of type %s, not %s',
                    FactoryDefinition::class,
                    $definition::class
                )
            );
        }

        $serviceDefinition = $definition->getResultDefinition();
        $serviceDefinition->addSetup('addExtension', [new Statement(\vavo\EncoreLoader\Latte\Extension\EncoreLoaderExtension::class)]);

        $containerBuilder->addFactoryDefinition($this->prefix('encoreLoaderFactory'))
            ->setImplement(EncoreLoaderFactory::class);
    }
}
