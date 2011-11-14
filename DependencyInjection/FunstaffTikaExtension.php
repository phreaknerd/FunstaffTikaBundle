<?php

namespace Funstaff\TikaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * FunstaffTikaExtension
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class FunstaffTikaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('funstaff.tika.config', $config);

        if (!file_exists($config['tika_path'])) {
            throw new \InvalidArgumentException(
                sprintf('The tika app with path "%s" does not exists', $config['tika_path'])
            );
        }
    }

    public function getAlias()
    {
        return 'funstaff_tika';
    }
}