<?php

declare(strict_types=1);

namespace Mikro\Http;

abstract class AbstractController
{
    public function request(): Request
    {
        return Request::createFromGlobals();
    }
}