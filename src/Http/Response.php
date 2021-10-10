<?php

declare(strict_types=1);

namespace Mikro\Http;

class Response extends \Symfony\Component\HttpFoundation\Response
{
    public static function internalServerError(): self
    {
        return self::buildResponse(self::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function methodNotAllowed(): self
    {
        return self::buildResponse(self::HTTP_METHOD_NOT_ALLOWED);
    }

    public static function noContent(): self
    {
        return self::buildResponse(self::HTTP_NO_CONTENT);
    }

    public static function response(?string $data = null): self
    {
        return self::buildResponse(self::HTTP_OK, $data);
    }

    public static function badRequest(?string $data = null): self
    {
        return self::buildResponse(self::HTTP_BAD_REQUEST, $data);
    }

    public static function notFound(): self
    {
        return self::buildResponse(self::HTTP_NOT_FOUND);
    }

    public static function unauthorized(): self
    {
        return self::buildResponse(self::HTTP_UNAUTHORIZED);
    }

    public static function forbidden(): self
    {
        return self::buildResponse(self::HTTP_FORBIDDEN);
    }

    public static function buildResponse(int $code, mixed $data = null, array $headers = []): self
    {
        return new self($data, $code, $headers);
    }
}