<?php

namespace Antilop\Bundle\PayboxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PayboxExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (!empty($config['paybox'])) {
            $config['parameters']['public_key'] = $config['public_key'];

            $container->setParameter('paybox.servers', $config['servers']);
            $container->setParameter('paybox.parameters', $config['parameters']);
            $container->setParameter('paybox.transport.class', $config['transport']);
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}