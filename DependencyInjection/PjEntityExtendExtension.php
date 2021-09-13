<?php

namespace Pj\EntityExtendBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class PjEntityExtendExtension.
 *
 * @author Paulius Jarmalavičius <paulius.jarmalavicius@gmail.com>
 */
class PjEntityExtendExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('extended_entities', $config['extended_entities']);
    }
}