<?php

namespace Core\Router;


class BaseRoute
{
    public string $file;
    public string $name;

    use GaurdTrait;
    use DataTrait;
    use QueryTrait;

    public function __construct(public readonly string $method = 'GET', public readonly string $route)
    {
    }
}