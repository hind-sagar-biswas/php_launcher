<?php

namespace Core\Router;

trait GaurdTrait
{
    public array $gaurds = [];
    
    public function gaurd(string $gaurdName): self
    {
        $this->gaurds[] = $gaurdName;
        return $this;
    }
}
