<?php declare(strict_types=1);

auth_reauthenticate();
access_ensure_project_level(config_get('manage_plugin_threshold'));

layout_page_header(plugin_lang_get('configuration'));
layout_page_begin('manage_overview_page.php');

print_manage_menu('manage_plugin_page.php');

$t_manage_threshold = plugin_config_get('manage_threshold');

?>

  <div class="col-md-12 col-xs-12">
    <div class="space-10"></div>
    <div class="form-container">
      <form action="<?= plugin_page('config_update') ?>" method="post">
          <?= form_security_field('plugin_ToDoLists_config_update') ?>
        <div class="widget-box widget-color-blue2">
          <div class="widget-header widget-header-small">
            <h4 class="widget-title lighter">
              <i class="ace-icon fa fa-tasks"></i>
                <?= plugin_lang_get('name') . ': ' . plugin_lang_get('configuration') ?>
            </h4>
          </div>
          <div class="widget-body">
            <div class="widget-main no-padding">
              <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped">

                  <tr>
                    <th class="category width-50">
                        <?= plugin_lang_get('manage_threshold') ?><br />
                      <span class="small"><?= plugin_lang_get('manage_threshold_desc') ?></span>
                    </th>
                    <td class="center">
                      <select name="manage_threshold">
                        <?php print_enum_string_option_list('access_levels', plugin_config_get('manage_threshold')); ?>
                      </select>
                    </td>
                  </tr>

                  <tr>
                    <th class="category width-50">
                      <?= plugin_lang_get('view_threshold') ?><br />
                      <span class="small"><?= plugin_lang_get('view_threshold_desc') ?></span>
                    </th>
                    <td class="center">
                      <select name="view_threshold">
                        <?php print_enum_string_option_list('access_levels', plugin_config_get('view_threshold')); ?>
                      </select>
                    </td>
                  </tr>

                  <tr>
                    <th class="category width-50">
                      Szablony
                    </th>
                    <td class="center">
                        <select id="category_id" name="category_id" class="autofocus input-sm required">
                          <?php print_category_option_list(); ?>
                        </select>
                    </td>
                  </tr>

                </table>
              </div>
            </div>
            <div class="widget-toolbox padding-8 clearfix">
              <input
                type="submit"
                class="btn btn-primary btn-white btn-round"
                value="<?= lang_get('change_configuration') ?>"
              />
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

<?php

layout_page_end();
