<?php

namespace App\Core\Attributes;

#[\Attribute]
class Route
{
    protected string $path;
    protected string $method;

    public function __construct(string $path, string $method = "GET")
    {
        $this->path = $path;
        $this->method = $method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}