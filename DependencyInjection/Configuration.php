<?php

namespace Funstaff\TikaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('funstaff_tika');

        $rootNode
            ->children()
                ->scalarNode('tika_path')->isRequired()->end()
                ->scalarNode('output_format')->defaultValue('xml')
                    ->validate()
                        ->ifNotInArray(array('xml', 'html', 'text'))
                        ->thenInvalid('Not authorized value for output (only xml, html and text)')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
