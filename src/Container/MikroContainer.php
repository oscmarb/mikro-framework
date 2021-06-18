<?php

declare(strict_types=1);

namespace Mikro\Container;

use Mikro\Http\AbstractController;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MikroContainer
{
    private static self $instance;
    private ContainerInterface $container;

    public static function instance(): self
    {
        return self::$instance;
    }

    public static function load(?string $servicesDir = null): self
    {
        return self::$instance ??= new self($servicesDir);
    }

    public function __construct(?string $servicesDir = null)
    {
        $this->container = new ContainerBuilder();

        $this->container->registerForAutoconfiguration(AbstractController::class)
            ->addTag('mikro.controller');

        $loader = new YamlFileLoader($this->container, new FileLocator('/'));
        $loader->load(__DIR__.'/../../config/services.yaml');

        if (false === empty($servicesDir)) {
            $loader->load($servicesDir.'/services.yaml');
        }

        $this->container->compile();
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }
}