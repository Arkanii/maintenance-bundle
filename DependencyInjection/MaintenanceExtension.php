<?php

namespace Arkanii\MaintenanceBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MaintenanceExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('maintenance.enabled', $config['enabled']);
        $container->setParameter('maintenance.authorized_ips', $config['authorized_ips']);
        $container->setParameter('maintenance.debug_urls', $config['debug_urls']);
        $container->setParameter('maintenance.authorize_admin_panel', $config['authorize_admin_panel']);
        $container->setParameter('maintenance.admin_url', $config['admin_url']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yaml');
    }
}