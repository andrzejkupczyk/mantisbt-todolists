<?php

namespace ToDoLists;

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
     * @var string
     */
    protected $method;

    /**
     * @var TasksRepository
     */
    protected $repository;

    /**
     * @param string $method
     */
    public function __construct($method = null)
    {
        $this->repository = new TasksRepository();
        $this->method = strtolower($method ?: $_SERVER['REQUEST_METHOD']) . 'Request';
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (isset($this->data['task'][$name])) {
            return $this->data['task'][$name];
        } elseif (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            throw new Exception();
        }
    }

    public function handle()
    {
        try {
            if (!method_exists($this, $this->method)) {
                throw new Exception();
            }
            if (!$data = file_get_contents('php://input')) {
                throw new Exception();
            }
            $this->data = json_decode($data, true);
            call_user_func([$this, $this->method]);
        } catch (Exception $e) {
            $this->sendJSON($e->getMessage(), 400);
        }
    }

    protected function deleteRequest()
    {
        $this->repository->delete($this->id);
    }

    protected function postRequest()
    {
        $task = $this->repository->insert([
            'bug_id' => $this->bug_id,
            'description' => $this->description,
        ]);
        $this->sendJSON($task);
    }

    protected function putRequest()
    {
        $this->repository->update([
            'id' => $this->id,
            'finished' => $this->finished,
            'description' => $this->description,
        ]);
    }

    /**
     * @param mixed $data
     * @param integer $code
     */
    private function sendJSON($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data);
    }
}
