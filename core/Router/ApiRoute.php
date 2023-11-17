<?php

namespace Core\Router;


class ApiRoute extends BaseRoute
{
    public function name(string $name): self
    {
        $this->name = $name;
        $this->file = ROOTPATH . "facade/api/$name.php";
        return $this;
    }

    public function call(string $fileName, string $ext = 'php'): self
    {
        $this->file = ROOTPATH . "facade/api/$fileName.$ext";
        if (!file_exists($this->file)) throw new \Exception("Route file `$this->file` not found!");
        if (!is_file($this->file)) throw new \Exception("`$this->file` is not a file!");
        return $this;
    }

}
