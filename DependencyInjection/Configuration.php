<?php

namespace Arkanii\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("maintenance");

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->info('Enabled the maintenance status.')
                    ->defaultFalse()
                ->end()
                ->arrayNode('authorized_ips')
                    ->fixXmlConfig('authorized_ip')
                    ->info('Authorized ips who pass through the maintenance mode.')
                    ->example(['127.0.0.1'])
                    ->scalarPrototype()->end()
                    ->defaultValue(['127.0.0.1'])
                ->end()
                ->arrayNode('debug_urls')
                    ->fixXmlConfig('debug_url')
                    ->info('Symfony debug URLs. They will pass through the maintenance mode.')
                    ->example(['_error', '_wdt', '_profiler'])
                    ->scalarPrototype()->end()
                    ->defaultValue(['_error', '_wdt', '_profiler'])
                ->end()
                ->booleanNode('authorize_admin_panel')
                    ->info('Authorize connection to administration panel when maintenance mode is enabled.')
                    ->defaultFalse()
                ->end()
                ->scalarNode('admin_url')
                    ->info('Admin base url, links will pass through the maintenance mode if authorized.')
                    ->defaultValue('admin')
                    ->example('admin')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}