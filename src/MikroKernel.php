<?php

declare(strict_types=1);

namespace Mikro;

use Mikro\Container\MikroContainer;
use Mikro\Http\Router;

class MikroKernel
{
    public static function start(): self
    {
        $kernel = new self();
        $kernel->run();

        return $kernel;
    }

    public function __construct()
    {
        MikroContainer::load($this->servicesDir());
        Router::load();
    }

    protected function servicesDir(): ?string
    {
        return null;
    }

    public function run(): void
    {
        Router::instance()->sendResponse();
    }
}