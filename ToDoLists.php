<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Mantis\ToDoLists\TasksRepository;

class ToDoListsPlugin extends MantisPlugin
{
    const VERSION = '2.6.0';

    /**
     * @var \Mantis\ToDoLists\TasksRepository
     */
    protected $repository;

    public function register()
    {
        $this->name = plugin_lang_get('name');
        $this->description = plugin_lang_get('description');
        $this->page = 'config';

        $this->version = self::VERSION;
        $this->requires = ['MantisCore' => '2.0.0'];

        $this->author = 'Andrzej Kupczyk';
        $this->contact = 'kontakt@andrzejkupczyk.pl';
        $this->url = 'https://github.com/andrzejkupczyk/mantis-todolists';
    }

    public function init()
    {
        $this->repository = new TasksRepository();
    }

    public function config(): array
    {
        return [
            'manage_threshold' => DEVELOPER,
            'view_threshold' => REPORTER,
        ];
    }

    public function events(): array
    {
        return [
            'EVENT_TODOLISTS_TASK_CREATED' => EVENT_TYPE_EXECUTE,
            'EVENT_TODOLISTS_TASK_UPDATED' => EVENT_TYPE_EXECUTE,
        ];
    }

    public function hooks(): array
    {
        $events = [
            'EVENT_BUG_DELETED' => 'deleteTasks',
            'EVENT_TODOLISTS_TASK_CREATED' => 'addLogEntry',
            'EVENT_TODOLISTS_TASK_UPDATED' => 'addLogEntry',
        ];

        if (is_page_name('view.php') || is_page_name('bug_reminder')) {
            $events += [
                'EVENT_CORE_HEADERS' => 'cspHeaders',
                'EVENT_LAYOUT_PAGE_FOOTER' => 'scripts',
                'EVENT_VIEW_BUG_DETAILS' => 'displayTasks',
                'EVENT_LAYOUT_RESOURCES' => 'styles',
            ];
        }

        return $events;
    }

    /**
     * @see https://www.mantisbt.org/wiki/doku.php/mantisbt:plugins_overview#schema_management
     * @see https://adodb.org/dokuwiki/doku.php?id=v5:dictionary:dictionary_index
     */
    public function schema(): array
    {
        $tableName = plugin_table(TasksRepository::TABLE_NAME);

        return [
            ['CreateTableSQL', [
                $tableName,
                'id I UNSIGNED PRIMARY NOTNULL AUTOINCREMENT,
                bug_id I UNSIGNED NOTNULL DEFAULT \'0\',
                description C(120) NOTNULL DEFAULT \'\',
                finished I2 DEFAULT \'0\'',
                ['mysql' => 'ENGINE=MyISAM DEFAULT CHARSET=utf8', 'pgsql' => 'WITHOUT OIDS'],
            ]],
            ['CreateIndexSQL', [
                'idx_tasks_bug_id',
                $tableName,
                'bug_id',
            ]],
        ];
    }

    public function deleteTasks(string $event, int $bugId)
    {
        $this->repository->deleteAssociatedToBug($bugId);
    }

    public function displayTasks(string $event, int $bugId)
    {
        $tasks = $this->repository->findByBug($bugId);

        include_once 'pages/partials/todolist.php';
    }

    public function addLogEntry(string $event, array $data)
    {
        $fieldName = strtolower(str_replace('EVENT_TODOLISTS_', '', $event));

        history_log_event_direct($data['bug_id'], plugin_lang_get_defaulted($fieldName), '', $data['description']);
    }

    public function styles(): string
    {
        return '<link rel="stylesheet" type="text/css" href="' . plugin_file('todolists.css') . '" />';
    }

    public function scripts(): string
    {
        return '<script type="text/javascript" src="' . plugin_file('htmx.min.js') . '"></script>' .
            '<script type="text/javascript" src="' . plugin_file('todolists.min.js') . '"></script>';
    }

    public function cspHeaders()
    {
        http_csp_add('script-src', "'unsafe-eval'");
    }
}
