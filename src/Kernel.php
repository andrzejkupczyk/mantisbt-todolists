<?php

declare(strict_types=1);

namespace WebGarden\ToDoLists;

use WebGarden\ToDoLists\Http\Request;
use WebGarden\ToDoLists\Routing\Router;

class Kernel
{
    /**
     * @var \WebGarden\ToDoLists\Routing\Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param \WebGarden\ToDoLists\Http\Request $request
     */
    public function handle($request)
    {
        $route = $this->router->route($request->method());

        list($controller, $action) = $route();

        return call_user_func([new $controller(), $action], $request);
    }
}
