<?php

declare(strict_types=1);

namespace Mantis\ToDoLists\Http;

use Mantis\ToDoLists\Database\TasksRepository;

class Controller
{
    /**
     * @var \Mantis\ToDoLists\Database\TasksRepository
     */
    protected $repository;

    public function __construct(TasksRepository $repository = null)
    {
        $this->repository = $repository ?: new TasksRepository();
    }

    /**
     * @return void
     */
    public function create(Request $request)
    {
        $descriptions = array_filter(explode(PHP_EOL, $request['description']));

        foreach ($descriptions as $description) {
            $this->repository->insert([
                'bug_id' => $request['bug_id'],
                'description' => $description,
            ]);
        }
    }

    /**
     * @return void
     */
    public function update(Request $request)
    {
        $parameters = $request->parameters()->only('id', 'finished', 'description');

        if ($description = $request->header('hx-prompt')) {
            $parameters['description'] = $description;
        }

        $this->repository->update($parameters);
    }

    /**
     * @return void
     */
    public function delete(Request $request)
    {
        $this->repository->delete($request['id']);
    }
}
