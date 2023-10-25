<?php

namespace Core\Base;

enum RequestType: string {
    case WEB = 'facade';
    case API = 'api';

    public function getDir(): string {
        return ROOTPATH . $this->value;
    }

    public static function fromRoute($route): RequestType {
        return (preg_match('/api\w*/', $route)) ? self::API : self::WEB;
    }
}