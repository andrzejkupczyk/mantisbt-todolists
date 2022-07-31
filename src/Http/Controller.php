<?php

declare(strict_types=1);

namespace WebGarden\ToDoLists\Http;

use WebGarden\ToDoLists\Database\TasksRepository;

class Controller
{
    /**
     * @var \WebGarden\ToDoLists\Database\TasksRepository
     */
    protected $repository;

    public function __construct(TasksRepository $repository = null)
    {
        $this->repository = $repository ?: new TasksRepository();
    }

    /**
     * @return void
     * @param \WebGarden\ToDoLists\Http\Request $request
     */
    public function create($request)
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
     * @param \WebGarden\ToDoLists\Http\Request $request
     */
    public function update($request)
    {
        $parameters = $request->parameters()->only('id', 'finished', 'description');

        if ($description = $request->header('hx-prompt')) {
            $parameters['description'] = $description;
        }

        $this->repository->update($parameters);
    }

    /**
     * @return void
     * @param \WebGarden\ToDoLists\Http\Request $request
     */
    public function delete($request)
    {
        $this->repository->delete($request['id']);
    }
}
