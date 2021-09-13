<?php

namespace Pj\EntityExtendBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * @author Paulius Jarmalavičius <paulius.jarmalavicius@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pj_entity_extend');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('extended_entities')
                ->useAttributeAsKey('name')
                ->prototype('scalar')
            ->end();

        return $treeBuilder;
    }
}
