<?php declare(strict_types=1);
if (!empty($tasks)): ?>
<ul class="<?= plugin_get_current() ?>-items <?= $canManage ? 'manageable' : '' ?>">
    <?php foreach ($tasks as $task): ?>
        <li
            id="<?= plugin_get_current() ?>-item-<?= $task['id'] ?>"
            class="<?= plugin_get_current() ?>-item <?= $task['finished'] ? 'finished' : 'unfinished'?>"
            hx-trigger="click throttle:1s"
        >
            <span
                class="description"
                <?php if ($canManage): ?>
                hx-post="<?= plugin_page('ajax_page') ?>"
                hx-headers='{"x-http-method-override": "put"}'
                hx-vals='<?= json_encode(array_merge($task, ['finished' => !$task['finished']])) ?>'
                <?php endif ?>
            >
                <?= $task['descriptionHtml'] ?>
            </span>
            <?php if ($canManage): ?>
            <span class="actions">
                <a
                    class="edit"
                href
                    title="<?= plugin_lang_get('edit_task') ?>"
                    <?php if (!$task['finished']): ?>
                    hx-post="<?= plugin_page('ajax_page') ?>"
                    hx-headers='{"x-http-method-override": "put"}'
                    hx-vals='<?= json_encode($task) ?>'
                    hx-prompt="<?= plugin_lang_get('enter_new_description') ?>"
                    <?php endif ?>
                >
                    <i class="fa fa-pencil"></i>
                </a>
                <a
                    href
                    title="<?= plugin_lang_get('delete_task') ?>"
                    hx-post="<?= plugin_page('ajax_page') ?>"
                    hx-headers='{"x-http-method-override": "delete"}'
                    hx-vals='<?= json_encode($task) ?>'
                    <?php if (!$task['finished']): ?>
                    hx-confirm="<?= plugin_lang_get('confirm_deletion') ?>"
                    <?php endif ?>
                >
                    <i class="fa fa-trash"></i>
                </a>
            </span>
            <?php endif ?>
        </li>
    <?php endforeach ?>
</ul>
<?php endif ?>
