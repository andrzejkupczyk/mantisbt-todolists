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
        if (!access_has_project_level(plugin_config_get('manage_threshold'))) {
            access_denied();
        }

        $this->repository = $repository ?: new TasksRepository();
    }

    /**
     * @param \Slim\Http\Request $request
     *
     * @return void
     */
    public function create($request)
    {
        $bugId = $request->getParam('bug_id');
        $descriptions = array_filter(explode(PHP_EOL, $request->getParam('description')));

        foreach ($descriptions as $description) {
            $this->repository->insert([
                'bug_id' => $bugId,
                'description' => $description,
            ]);
        }

        $this->dispatchRequestHandled($request);
    }

    /**
     * @param \Slim\Http\Request $request
     *
     * @return void
     */
    public function update($request)
    {
        $parameters = $request->getParams(['id', 'bug_id', 'finished', 'description']);

        if ($request->hasHeader('hx-prompt')) {
            $parameters['description'] = $request->getHeaderLine('hx-prompt');
        }

        $this->repository->update($parameters);

        $this->dispatchRequestHandled($request);
    }

    /**
     * @param \Slim\Http\Request $request
     *
     * @return void
     */
    public function delete($request)
    {
        $this->repository->delete($request->getParam('id'));

        $this->dispatchRequestHandled($request);
    }

    /**
     * @param \Slim\Http\Request $request
     *
     * @return void
     */
    private function dispatchRequestHandled($request)
    {
        event_signal('EVENT_TODOLISTS_REQUEST_HANDLED', $request->getParam('bug_id'));
    }
}
