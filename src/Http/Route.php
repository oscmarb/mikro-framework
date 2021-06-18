<?php

declare(strict_types=1);

namespace Mikro\Http;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Route
{
    private string $path;
    private array $methods;

    public function __construct(string $path, array $methods = [])
    {
        $this->path = $path;
        $this->methods = $methods;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function methods(): array
    {
        return $this->methods;
    }
}