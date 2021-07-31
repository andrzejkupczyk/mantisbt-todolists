<?php declare(strict_types=1);
if (!access_has_project_level(plugin_config_get('view_threshold'))) {
    return;
}
?>
<tr
  id="<?= plugin_get_current() ?>"
  :is-readonly="<?= !access_has_project_level(plugin_config_get('manage_threshold')) ? 'true' : 'false' ?>"
  current-tasks="<?= string_attribute(json_encode($tasks)) ?>"
  translations="<?= string_attribute(json_encode([
    'enterNewDescription' => plugin_lang_get('enter_new_description'),
    'confirmDeletion' => plugin_lang_get('confirm_deletion'),
  ])) ?>"
>
  <td class="category">
    <?= plugin_lang_get('things_to_do') ?>
    <span class="<?= plugin_get_current() ?>-counter" v-cloak>
      {{ counter }}
    </span>
  </td>
  <td colspan="5">
    <form
      class="<?= plugin_get_current() ?>-form form-inline"
      action="<?= plugin_page('ajax_page') ?>"
      method="post"
      @submit.prevent
    >
      <input v-model="newTask.bug_id" type="hidden" value="<?= $bugId ?>">
      <textarea
        @keydown.enter.prevent="insertTask"
        v-model="newTask.description"
        v-if="!isReadonly"
        rows="1"
        class="<?= plugin_get_current() ?>-add-new input-sm"
        placeholder="<?= plugin_lang_get('add_new_task') ?>"
      ></textarea>
      <button class="btn btn-primary btn-sm btn-white btn-round" @click="insertTask" v-if="!isReadonly">
        <?= plugin_lang_get('add') ?>
      </button>
      <ul v-if="tasks.length" v-cloak>
        <li v-for="task in tasks | orderBy 'finished'" track-by="id">
          <label :class="{finished: task.finished}">
            <input
              @change="toggleFinished(task)"
              v-model="task.finished"
              :disabled="isReadonly"
              type="checkbox"
            >
            <span>{{ task.description }}</span>
          </label>
          <a
            @click="changeDescription(task, $event)"
            v-if="!isReadonly && !task.finished"
            title="<?= plugin_lang_get('edit_task') ?>"
          >
            <i class="fa fa-pencil"></i>
          </a>
          <a
            @click="deleteTask(task, $event)"
            v-if="!isReadonly"
            title="<?= plugin_lang_get('delete_task') ?>"
          >
            <i class="fa fa-trash"></i>
          </a>
        </li>
      </ul>
    </form>
    <script type="text/javascript" src="<?= plugin_file('todolists.js') ?>"></script>
  </td>
</tr>
