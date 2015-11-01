<?php

if (!access_has_project_level(plugin_config_get('manage_threshold'))) {
    access_denied();
}

$router = new ToDoLists\AjaxRequestHandler();
$router->handle();
