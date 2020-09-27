<?php

require_once 'autoload.php';

class ToDoListsPlugin extends MantisPlugin
{
    const VERSION = '2.0.2';

    /**
     * @var ToDoLists\TasksRepository
     */
    protected $repository;

    public function register()
    {
        $this->name = plugin_lang_get('name');
        $this->description = plugin_lang_get('description');
        $this->page = 'config_page';

        $this->version = self::VERSION;
        $this->requires = ['MantisCore' => '2.0.0'];

        $this->author = 'Andrzej Kupczyk';
        $this->contact = 'kontakt@andrzejkupczyk.pl';
        $this->url = 'https://github.com/andrzejkupczyk/mantisbt-todolists';
    }

    public function init()
    {
        $this->repository = new ToDoLists\TasksRepository();
    }

    public function config()
    {
        return [
            'manage_threshold' => DEVELOPER,
            'view_threshold' => REPORTER,
        ];
    }

    public function hooks()
    {
        return [
            'EVENT_LAYOUT_RESOURCES' => 'resources',
            'EVENT_VIEW_BUG_DETAILS' => 'display',
            'EVENT_CORE_HEADERS' => 'cspHeaders',
        ];
    }

    /**
     * @link https://www.mantisbt.org/wiki/doku.php/mantisbt:plugins_overview#schema_management
     * @link https://adodb.org/dokuwiki/doku.php?id=v5:dictionary:dictionary_index
     */
    public function schema()
    {
        return [
            ['CreateTableSQL', [
                plugin_table('tasks'),
                'id I UNSIGNED PRIMARY NOTNULL AUTOINCREMENT,
                bug_id I UNSIGNED NOTNULL DEFAULT \'0\',
                description C(120) NOTNULL DEFAULT \'\',
                finished I2 DEFAULT \'0\'',
                ['mysql' => 'ENGINE=MyISAM DEFAULT CHARSET=utf8', 'pgsql' => 'WITHOUT OIDS'],
            ]],
            ['CreateIndexSQL', [
                'idx_tasks_bug_id',
                plugin_table('tasks'),
                'bug_id',
            ]],
        ];
    }

    /**
     * @param string  $event
     * @param integer $bugId
     */
    public function display($event, $bugId)
    {
        $tasks = $this->repository->findByBug($bugId);
        include_once 'pages/partials/todolist.php';
    }

    /**
     * @param  string   $event
     * @return string
     */
    public function resources($event)
    {
        return '<link rel="stylesheet" type="text/css" href="' . plugin_file('todolists.css') . '" />' .
            '<script type="text/javascript" src="' . plugin_file('vue-min.js') . '"></script>' .
            '<script type="text/javascript" src="' . plugin_file('vue-resource-min.js') . '"></script>';
    }

    public function cspHeaders()
    {
        http_csp_add('script-src', "'self' 'unsafe-inline' 'unsafe-eval'");
    }
}
