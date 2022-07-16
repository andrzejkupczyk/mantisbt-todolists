<?php declare(strict_types=1);
if (!access_has_project_level(plugin_config_get('view_threshold'))) {
    return;
}
?>
<tr
    class="<?= plugin_get_current() ?>"
    hx-target="#<?= plugin_get_current() ?>"
>
    <td class="category">
        <?= plugin_lang_get('things_to_do') ?>
    </td>
    <td colspan="5">
        <?php if ($canManage): ?>
        <form
            hx-post="<?= plugin_page('ajax_page') ?>"
            hx-vals='{"bug_id": <?= $bugId ?>}'
        >
            <textarea
                name="description"
                rows="1"
                class="input-sm"
                placeholder="<?= plugin_lang_get('add_new_task') ?>"
            ></textarea>
            <button class="btn btn-primary btn-sm btn-white btn-round">
                <?= plugin_lang_get('add') ?>
            </button>
        </form>
        <?php endif ?>
        <div id="<?= plugin_get_current() ?>">
            <?php include 'list_items.php'; ?>
        </div>
    </td>
</tr>
