<?php

declare(strict_types=1);

namespace Mantis\ToDoLists;

use Exception;

/**
 * @property int id
 * @property int bug_id
 * @property int finished
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

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        if (isset($this->data['task'][$name])) {
            return $this->data['task'][$name];
        }

        throw new Exception("Task's `$name` property is undefined");
    }

    public function handle()
    {
        $method = "{$this->httpMethod()}Request";

        try {
            if (!method_exists($this, $method)) {
                throw new Exception("Method `{$this->httpMethod()}` not allowed", 405);
            }
            if (!$data = file_get_contents('php://input')) {
                throw new Exception('Invalid request body', 422);
            }
            $this->data = json_decode($data, true);
            call_user_func([$this, $method]);
        } catch (Exception $e) {
            $this->sendJSON($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    protected function deleteRequest()
    {
        $this->repository->delete($this->id);

        $this->sendJSON(null, 204);
    }

    protected function postRequest()
    {
        $tasksToAdd = array_filter(explode(PHP_EOL, $this->description));
        $addedTasks = [];

        foreach ($tasksToAdd as $description) {
            $addedTasks[] = $this->repository->insert([
                'bug_id' => $this->bug_id,
                'description' => $description,
            ]);
        }

        $this->sendJSON(array_filter($addedTasks), 201);
    }

    protected function putRequest()
    {
        $task = $this->repository->update([
            'id' => $this->id,
            'finished' => $this->finished,
            'description' => $this->description,
        ]);

        $this->sendJSON($task);
    }

    /**
     * @param array|string $data
     */
    private function sendJSON($data, int $code = 200)
    {
        $data = is_string($data) ? $data : json_encode($data);

        header('Content-Type: application/json');
        header('Content-length: ' . strlen($data));

        http_response_code($code);
        echo $data;
    }

    private function httpMethod(): string
    {
        $headers = getallheaders();

        return $headers['X-HTTP-Method-Override'] ?? $_SERVER['REQUEST_METHOD'];
    }
}
