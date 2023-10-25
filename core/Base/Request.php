<?php

namespace Core\Base;

use Core\Base\RequestType;
use Core\Security\Csrf;

class Request
{
    public readonly RequestType $type;
    public readonly string $full_url;
    public readonly ?string $origin;
    public readonly string $method;
    public readonly string $route;
    public readonly array $query;
    public readonly array $data;
    public readonly string $url;
    public readonly int $page;

    public function __construct()
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        $parsed_uri = parse_url($request_uri);
        $req_path = $parsed_uri['path'];

        $req_route = preg_replace('/' . preg_quote(APP_ROOT, '/') . '/', '', $req_path, 1);
        $req_query_string = isset($parsed_uri['query']) ? $parsed_uri['query'] : '';



        parse_str($req_query_string, $req_query);
        if (!str_ends_with($req_route, '/')) $req_route .= '/';

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->type = RequestType::fromRoute($req_route);
        $this->full_url = APP_URL . $req_route . '?' . $req_query_string;
        $this->url = APP_URL . $req_route;
        $this->route = preg_replace('/' . preg_quote(APP_API_ROOT, '/') . '/', '', $req_route, 1);
        $this->origin = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        
        // $data = ($this->method === 'POST') ? $_POST : $_GET;
        $data = $_POST;
        foreach ($data as $key => $value) {
            $data[$key] = (!is_array($value)) ? htmlspecialchars($value, ENT_QUOTES) : $value;
        }
        $this->data = $data;

        $this->page = (isset($req_query['page']) && is_numeric($req_query['page'])) ? intval($req_query['page']) : 1;
        
        unset($req_query['page']);
        $this->query = array_map(fn ($val): string => htmlspecialchars($val, ENT_QUOTES), $req_query);
        
        if (CSRF_ENABLED === 'true' && $this->type === RequestType::WEB && $this->method == 'POST') Csrf::validateToken();
    }
}
