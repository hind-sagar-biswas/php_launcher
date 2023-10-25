<?php

namespace Core\Router;

trait QueryTrait
{
    public array $query_requires = [];

    public function query(array $query_keys): self
    {
        $this->query_requires = $query_keys;
        return $this;
    }
}
