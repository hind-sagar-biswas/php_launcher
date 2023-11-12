<?php

namespace Core\Base;

use InvalidArgumentException;

class Url
{
    private ?array $query = null;
    private string $base;

    public function __construct(string $base)
    {
        [$this->base, $this->query] = self::parse($base);
    }

    public function addQuery(array $query): self
    {
        if (array_is_list($query)) throw new InvalidArgumentException("\$query must be associative array");

        $this->query = $this->query ? array_merge($this->query, $query) : $query;
        return $this;
    }

    public function removeQuery(): self
    {
        $this->query = null;
        return $this;
    }

    public function stripQuery(): array
    {
        $query = $this->query;
        $this->query = null;
        return $query;
    }

    public function extractQuery(): ?array
    {
        return $this->query;
    }

    public function extractBase(): string
    {
        return $this->base;
    }

    public function http(): self
    {
        $this->base = str_replace('https://', 'http://', $this->base, 1);
        return $this;
    }

    public function https(): self
    {
        $this->base = str_replace('http://', 'https://', $this->base, 1);
        return $this;
    }

    public function build(): string
    {
        if (!$this->query) return $this->base;
        return $this->base . '?' . http_build_query($this->query, '', '&', PHP_QUERY_RFC3986);
    }

    public static function parse(string $url, bool $assoc = false): array
    {
        $parsedUrl = parse_url($url);

        // If the URL doesn't have a scheme, add a default one to ensure proper parsing
        if (!isset($parsedUrl['scheme'])) {
            $url = 'http://' . $url;
            $parsedUrl = parse_url($url);
        }

        // Extract base
        $base = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        if (isset($parsedUrl['path'])) $base .= $parsedUrl['path'];

        // Extract and parse query
        $query = null;
        if (isset($parsedUrl['query'])) parse_str($parsedUrl['query'], $query);

        return $assoc ? ['base' => $base, 'query' => $query] : [$base, $query];
    }
}
