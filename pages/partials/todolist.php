<?php declare(strict_types=1);
if (!access_has_project_level(plugin_config_get('view_threshold'))) {
    return;
}
$canManage = access_has_project_level(plugin_config_get('manage_threshold'));
?>
<tr class="<?= plugin_get_current() ?>">
    <td class="category">
        <?= plugin_lang_get('things_to_do') ?>
    </td>
    <td colspan="5">
        <?php if ($canManage): ?>
        <form>
            <textarea
                rows="1"
                class="input-sm"
                placeholder="<?= plugin_lang_get('add_new_task') ?>"
            ></textarea>
            <button class="btn btn-primary btn-sm btn-white btn-round">
                <?= plugin_lang_get('add') ?>
            </button>
        </form>
        <?php endif ?>
        <?php if (!empty($tasks)): ?>
        <ul class="<?= plugin_get_current() ?>-items <?= $canManage ? 'manageable' : '' ?>">
            <?php foreach ($tasks as $task): ?>
                <li class="<?= plugin_get_current() ?>-item <?= $task['finished'] ? 'finished' : 'unfinished'?>">
                    <span class="description">
                        <?= $task['description'] ?>
                    </span>
                    <?php if ($canManage): ?>
                    <span class="actions">
                        <a class="edit" title="<?= plugin_lang_get('edit_task') ?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a title="<?= plugin_lang_get('delete_task') ?>">
                            <i class="fa fa-trash"></i>
                        </a>
                    </span>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>
    </td>
</tr>
