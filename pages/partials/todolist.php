<tr <?= helper_alternate_class() ?>>
    <td class="category"><?= plugin_lang_get('things_to_do') ?></td>
    <td colspan="5">
        <form id="<?= plugin_get_current() ?>" class="<?= plugin_get_current() ?>-form" action="<?= plugin_page('ajax_page') ?>" method="post">
            <input v-model="newTask.bug_id" type="hidden" value="<?= $bugId ?>">
            <ul v-show="tasks.length">
                <li v-for="task in tasks | orderBy 'finished'" track-by="id">
                    <label v-bind:class="{'finished': task.finished}">
                        <input v-on:change="saveTask(task)" v-model="task.finished" type="checkbox">
                        <span>{{ task.description }}</span>
                    </label>
                    <a v-on:click="editTask(task, $event)" href="#"><img alt="Edit" title="<?= plugin_lang_get('edit_task') ?>" class="edit-icon" src="images/update.png"></a>
                    <a v-on:click="deleteTask(task, $event)" href="#"><img alt="X" title="<?= plugin_lang_get('delete_task') ?>" class="delete-icon" src="images/delete.png"></a>
                </li>
            </ul>
            <input v-on:keydown.enter="addTask" v-model="newTask.description" type="text" class="<?= plugin_get_current() ?>-add-new" placeholder="<?= plugin_lang_get('add_new_task') ?>" size="40" maxlength="120" />
        </form>
        <script type="text/javascript" src="<?= plugin_file('todolists.js') ?>"></script>
        <script type="text/javascript">
        ToDoList.$set('lang', {
            titleEditTask: "<?= plugin_lang_get('title_edit_task') ?>",
            deleteConfirmation: "<?= plugin_lang_get('delete_confirmation') ?>"
        });
        </script>
        <?php if ($tasks): ?>
        <?php html_javascript_link('addLoadEvent.js'); ?>
        <script type="text/javascript">
        addLoadEvent(function() {
            this.ToDoList.$set("tasks", <?= json_encode($tasks) ?>);
        });
        </script>
        <?php endif; ?>
    </td>
</tr>