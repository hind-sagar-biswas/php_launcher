<?php

namespace Core\Router;


class WebRoute
{
    public string $file;
    public string $name;

    use GaurdTrait;
    use DataTrait;
    use QueryTrait;

    public function __construct(public readonly string $method = 'GET', public readonly string $route)
    {
    }

    public function name(string $name): self
    {
        $this->name = $name;
        $this->file = ROOTPATH . "facade/$name.php";
        return $this;
    }

    public function call(string $fileName, string $ext = 'php'): self
    {
        $this->file = ROOTPATH . "facade/$fileName.$ext";
        if (!file_exists($this->file)) throw new \Exception("Route file `$this->file` not found!");
        if (!is_file($this->file)) throw new \Exception("`$this->file` is not a file!");
        return $this;
    }
}
