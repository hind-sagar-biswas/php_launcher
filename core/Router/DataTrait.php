<?php

namespace Core\Router;

trait DataTrait
{
    public array $data_requires = [];

    public function data(array $data_keys): self
    {
        $this->data_requires = $data_keys;
        return $this;
    }
}
