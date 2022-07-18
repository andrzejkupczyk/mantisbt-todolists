<?php

declare(strict_types=1);

namespace Mantis\ToDoLists;

use Mantis\ToDoLists\Http\Request;
use Mantis\ToDoLists\Routing\Router;

class Kernel
{
    /**
     * @var \Mantis\ToDoLists\Routing\Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function handle(Request $request)
    {
        $route = $this->router->route($request->method());

        list($controller, $action) = $route();

        return call_user_func([new $controller(), $action], $request);
    }
}
