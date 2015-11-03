<?php

require_once 'autoload.php';

/**
 * To-Do lists Plugin
 *
 * @author Andrzej Kupczyk
 */
class ToDoListsPlugin extends MantisPlugin
{
    const VERSION = '1.1.0';

    /**
     * @var TasksRepository
     */
    protected $repository;

    public function register()
    {
        $this->name = plugin_lang_get('name');
        $this->description = plugin_lang_get('description');
        $this->page = 'config_page';

        $this->version = self::VERSION;
        $this->requires = [
            'MantisCore' => '1.2.0',
        ];

        $this->author = 'Andrzej Kupczyk';
        $this->contact = 'kontakt@andrzejkupczyk.pl';
        $this->url = 'http://andrzejkupczyk.pl';
    }

    public function init()
    {
        $this->repository = new ToDoLists\TasksRepository;
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
        ];
    }

    public function schema()
    {
        return [
            ['CreateTableSQL', [
                plugin_table('tasks'),
                'id I UNSIGNED PRIMARY NOTNULL AUTOINCREMENT,
                bug_id I UNSIGNED NOTNULL DEFAULT \'0\',
                description C(120) NOTNULL DEFAULT \'\',
                finished L DEFAULT 0',
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
}
