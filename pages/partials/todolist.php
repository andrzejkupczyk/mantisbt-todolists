<?php declare(strict_types=1);
if (!access_has_project_level(plugin_config_get('view_threshold'))) {
    return;
}
?>
<tr
  class="<?= plugin_get_current() ?>"
  hx-target="#<?= plugin_get_current() ?>"
  hx-indicator="#<?= plugin_get_current() ?>-spinner"
>
  <td class="category">
      <?= plugin_lang_get('things_to_do') ?>
    <i
      id="<?= plugin_get_current() ?>-spinner"
      class="htmx-indicator fa fa-refresh fa-spin fa-fw"
    ></i>
  </td>
  <td colspan="3">
      <?php if ($canManage): ?>
        <form
          hx-post="<?= plugin_api_url('tasks') ?>"
          hx-vals='{"bug_id": <?= $bugId ?>}'
        >
            <textarea
              name="description"
              rows="1"
              class="input-sm"
              placeholder="<?= plugin_lang_get('add_new_task') ?>"
              required
            ></textarea>
          <button type="submit" class="btn btn-primary btn-sm btn-white btn-round">
              <?= plugin_lang_get('add') ?>
          </button>
        </form>
      <?php endif ?>
    <div id="<?= plugin_get_current() ?>">
        <?php include 'list_items.php'; ?>
    </div>
  </td>
</tr>
