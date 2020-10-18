<?php

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

    /**
     * @param \Mantis\ToDoLists\TasksRepository $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (isset($this->data['task'][$name])) {
            return $this->data['task'][$name];
        } elseif (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            throw new Exception("Property {$name} is undefined");
        }
    }

    public function handle()
    {
        $method = $_SERVER['REQUEST_METHOD'] . 'Request';

        try {
            if (!method_exists($this, $method)) {
                throw new Exception("Method {$_SERVER['REQUEST_METHOD']} not allowed", 405);
            }
            if (!$data = file_get_contents('php://input')) {
                throw new Exception();
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
    }

    protected function postRequest()
    {
        $tasksToAdd = array_filter(explode(PHP_EOL, $this->description));
        $tasksAdded = [];

        foreach ($tasksToAdd as $description) {
            $tasksAdded[] = $this->repository->insert([
                'bug_id' => $this->bug_id,
                'description' => $description,
            ]);
        }

        $this->sendJSON($tasksAdded);
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
     * @param int $code
     */
    private function sendJSON($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data);
    }
}
