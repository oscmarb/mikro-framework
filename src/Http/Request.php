<?php

declare(strict_types=1);

namespace Mikro\Http;

class Request extends \Symfony\Component\HttpFoundation\Request
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';

    public static function allMethods(): array
    {
        return [
            self::GET,
            self::POST,
            self::PUT,
            self::DELETE,
        ];
    }
}