<?php

form_security_validate('plugin_ToDoLists_config_update');
access_ensure_global_level(config_get('manage_plugin_threshold'));

/**
 * Sets plugin config option if value is different from current/default
 * @param  string $name  option name
 * @param  string $value value to set
 * @return void
 */
function setOptionIfNeeded($name, $value)
{
    if ($value != plugin_config_get($name)) {
        plugin_config_set($name, $p_value);
    }
}

$manageThreshold = gpc_get_int('manage_threshold');
$viewThreshold = gpc_get_int('view_threshold');

setOptionIfNeeded('manage_threshold', $manageThreshold);
setOptionIfNeeded('view_threshold', $viewThreshold);

form_security_purge('plugin_ToDoLists_config_update');

print_successful_redirect(plugin_page('config_page', true));
