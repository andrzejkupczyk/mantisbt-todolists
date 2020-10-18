<?php

use Mantis\ToDoLists\AjaxRequestHandler;
use Mantis\ToDoLists\TasksRepository;

if (!access_has_project_level(plugin_config_get('manage_threshold'))) {
    access_denied();
}

$router = new AjaxRequestHandler(new TasksRepository());
$router->handle();
