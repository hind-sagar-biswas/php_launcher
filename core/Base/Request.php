<?php

namespace Core\Base;

use Core\Base\RequestType;
use Core\Security\Csrf;

class Request
{
    // Define properties with 'readonly' and their data types
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
        // Get the request URI and parse it
        $request_uri = $_SERVER['REQUEST_URI'];
        $parsed_uri = parse_url($request_uri);
        $req_path = $parsed_uri['path'];

        // Remove the APP_ROOT from the request path
        $req_route = preg_replace('/' . preg_quote('/' . APP_ROOT, '/') . '/', '', $req_path, 1);

        // Get the query string from the parsed URI
        $req_query_string = isset($parsed_uri['query']) ? $parsed_uri['query'] : '';

        // Parse the query string into an associative array
        parse_str($req_query_string, $req_query);

        // Append a trailing slash to the route if it doesn't have one
        $route_to_look = (str_ends_with($req_route, '/')) ? $req_route : $req_route . '/';

        // Set properties based on request information
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->type = RequestType::fromRoute($req_route);
        $this->full_url = APP_URL . $req_route . '?' . $req_query_string;
        $this->url = APP_URL . $req_route;
        $this->route = preg_replace('/' . preg_quote(APP_API_ROOT, '/') . '/', '/', $route_to_look, 1);
        $this->origin = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

        // Sanitize and store POST data
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = (!is_array($value)) ? htmlspecialchars($value, ENT_QUOTES) : $value;
        }
        $this->data = $data;

        // Determine the 'page' from query parameters or default to 1
        $this->page = (isset($req_query['page']) && is_numeric($req_query['page'])) ? intval($req_query['page']) : 1;

        // Remove 'page' from the query parameters
        unset($req_query['page']);

        // Sanitize and store the remaining query parameters
        $this->query = array_map(fn ($val): string => htmlspecialchars($val, ENT_QUOTES), $req_query);

        // Validate CSRF token if enabled and it's a web request with POST method
        if (CSRF_ENABLED && $this->type === RequestType::WEB && $this->method == 'POST') Csrf::validateToken();
    }
}
