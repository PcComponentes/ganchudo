<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ganchudo');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('inspectors')->isRequired()
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->scalarNode('command')->isRequired()->end()
                            ->arrayNode('iterator')
                                ->children()
                                    ->arrayNode('in')->isRequired()
                                        ->scalarPrototype()->end()
                                    ->end()
                                    ->arrayNode('exclude')->defaultValue([])
                                        ->scalarPrototype()->end()
                                    ->end()
                                    ->scalarNode('file')->isRequired()->end()
                                ->end()
                            ->end()
                            ->scalarNode('timeout')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
