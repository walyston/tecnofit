<?php

namespace config\Http;

final class Route
{
    public function __construct(
        public string $method,
        public string $path,
        public string $controller,
        public string $action
    ) {}

    public function match(string $requestMethod, string $uri): ?array
    {
        if ($this->method !== $requestMethod) {
            return null;
        }

        $pattern = preg_replace('#\{[^}]+\}#', '([^/]+)', $this->path);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return null;
    }
}
