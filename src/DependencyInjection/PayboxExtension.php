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

        if (!empty($config['parameters'])) {
            if (empty($config['public_key'])) {
                $config['public_key'] = __DIR__ . '/../Resources/config/paybox_public_key.pem';
            }
            $config['parameters']['public_key'] = $config['public_key'];
            $container->setParameter('paybox.parameters', $config['parameters']);
        }

        if (!empty($config['servers'])) {
            $container->setParameter('paybox.servers', $config['servers']);
        }

        if (!empty($config['simulate_error'])) {
            $container->setParameter('paybox.simulate_error.code', $config['simulate_error']['code']);
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}