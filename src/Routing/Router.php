<?php

declare(strict_types=1);

namespace Mantis\ToDoLists\Routing;

use Mantis\ToDoLists\Exceptions\PageNotFound;

class Router
{
    /**
     * @var array
     */
    protected $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @throws \Mantis\ToDoLists\Exceptions\PageNotFound
     */
    public function route(string $name): Route
    {
        if (!$this->hasRoute($name)) {
            throw new PageNotFound($name);
        }

        return new Route($this->routes[$name]);
    }

    protected function hasRoute(string $name): bool
    {
        return isset($this->routes[$name]);
    }
}
