<?php

auth_reauthenticate();
access_ensure_global_level(config_get('manage_plugin_threshold'));

$t_manage_threshold = plugin_config_get('manage_threshold');

html_page_top(plugin_lang_get('name'));
print_manage_menu();

?>

<br>
<form action="<?= plugin_page('config_update') ?>" method="post">
<?= form_security_field('plugin_ToDoLists_config_update') ?>
<table align="center" class="width50" cellspacing="1">

<tr>
<td class="form-title" colspan="2"><?= plugin_lang_get('name') ?>: <?= plugin_lang_get('configuration') ?></td>
</tr>

<tr <?= helper_alternate_class() ?>>
<td class="category" width="60%">
    <?= plugin_lang_get('manage_threshold') ?>
    <br /><span class="small"><?= plugin_lang_get('manage_threshold_desc') ?></span>
</td>
<td class="center" width="40%">
    <select name="manage_threshold">
    <?php print_enum_string_option_list('access_levels', plugin_config_get('manage_threshold')); ?>
    </select>
</td>
</tr>

<tr <?= helper_alternate_class() ?>>
<td class="category" width="60%">
    <?= plugin_lang_get('view_threshold') ?>
</td>
<td class="center" width="40%">
    <select name="view_threshold">
    <?php print_enum_string_option_list('access_levels', plugin_config_get('view_threshold')); ?>
    </select>
</td>
</tr>

<tr>
<td class="center" colspan="2"><input type="submit"/></td>
</tr>

</table>
</form>

<?php

html_page_bottom();
