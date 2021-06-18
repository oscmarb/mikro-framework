<?php

declare(strict_types=1);

namespace Mikro\Http;

class AbstractControllersFactory
{
    private array $controllers;

    public function __construct(\Traversable $controllers)
    {
        $this->controllers = \iterator_to_array($controllers);
    }

    public function controllers(): array
    {
        return $this->controllers;
    }
}