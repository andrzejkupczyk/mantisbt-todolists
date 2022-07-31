<?php

declare(strict_types=1);

namespace WebGarden\ToDoLists\Routing;

use WebGarden\ToDoLists\Exceptions\PageNotFound;

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
     * @throws \WebGarden\ToDoLists\Exceptions\PageNotFound
     * @param string $name
     */
    public function route($name): Route
    {
        if (!$this->hasRoute($name)) {
            throw new PageNotFound($name);
        }

        return new Route($this->routes[$name]);
    }

    /**
     * @param string $name
     */
    protected function hasRoute($name): bool
    {
        return isset($this->routes[$name]);
    }
}
