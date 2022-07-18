<?php

declare(strict_types=1);

use Mantis\ToDoLists\Http\Controller;
use Mantis\ToDoLists\Http\Request;
use Mantis\ToDoLists\Kernel;
use Mantis\ToDoLists\Routing\Router;

if (!access_has_project_level(plugin_config_get('manage_threshold'))) {
    access_denied();
}

$routes = [
    'post' => [Controller::class, 'create'],
    'put' => [Controller::class, 'update'],
    'delete' => [Controller::class, 'delete'],
];

$app = new Kernel(new Router($routes));
$app->handle(new Request());

event_signal('EVENT_TODOLISTS_REQUEST_HANDLED', [
    'bugId' => gpc_get_int('bug_id'),
]);
