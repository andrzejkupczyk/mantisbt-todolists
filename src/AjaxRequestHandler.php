<?php

declare(strict_types=1);

namespace Mantis\ToDoLists;

use Exception;

/**
 * @todo Separate request object from request handling
 *
 * @property int id
 * @property int bug_id
 * @property bool finished
 * @property string description
 */
class AjaxRequestHandler
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Mantis\ToDoLists\TasksRepository
     */
    protected $repository;

    public function __construct(TasksRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __get(string $name)
    {
        return $this->data[$name];
    }

    public function handle()
    {
        $method = "{$this->method()}Request";

        if (!method_exists($this, $method)) {
            throw new Exception("Method `{$this->method()}` not allowed", 405);
        }
        $this->data = $this->input();
        call_user_func([$this, $method]);

        event_signal('EVENT_TODOLISTS_REQUEST_HANDLED', [
            'bugId' => $this->bug_id,
        ]);
    }

    public function header(string $name)
    {
        return $this->headers()[strtolower($name)] ?? null;
    }

    public function headers(): array
    {
        return array_change_key_case(getallheaders());
    }


    public function input(): array
    {
        return [
            'id' => gpc_get_int('id', null),
            'bug_id' => gpc_get_int('bug_id'),
            'finished' => gpc_get_bool('finished', false),
            'description' => $this->header('hx-prompt')
                ?? gpc_get_string('description'),
        ];
    }

    public function method(): string
    {
        $method = $this->header('x-http-method-override')
            ?? $_SERVER['REQUEST_METHOD'];

        return strtolower($method);
    }
    protected function deleteRequest()
    {
        $this->repository->delete($this->id);
    }

    protected function postRequest()
    {
        $tasksToAdd = array_filter(explode(PHP_EOL, $this->description));

        foreach ($tasksToAdd as $description) {
            $this->repository->insert([
                'bug_id' => $this->bug_id,
                'description' => $description,
            ]);
        }
    }

    protected function putRequest()
    {
        $this->repository->update([
            'id' => $this->id,
            'finished' => $this->finished,
            'description' => $this->description,
        ]);
    }
}
