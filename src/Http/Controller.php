<?php

declare(strict_types=1);

namespace WebGarden\ToDoLists\Http;

use Slim\Http\Request;
use WebGarden\ToDoLists\Database\TasksRepository;

class Controller
{
    /**
     * @var \WebGarden\ToDoLists\Database\TasksRepository
     */
    protected $tasks;

    public function __construct(TasksRepository $repository = null)
    {
        $this->tasks = $repository ?: new TasksRepository();
    }

    /**
     * @return void
     */
    public function create(Request $request)
    {
        $bugId = $request->getParam('bug_id');
        $descriptions = array_filter(explode(PHP_EOL, $request->getParam('description')));

        foreach ($descriptions as $description) {
            $this->tasks->insert([
                'bug_id' => $bugId,
                'description' => $description,
            ]);
        }

        $this->dispatchRequestHandled($request);
    }

    /**
     * @return void
     */
    public function update(Request $request)
    {
        $parameters = $request->getParams(['id', 'bug_id', 'finished', 'description']);

        if ($request->hasHeader('hx-prompt')) {
            $parameters['description'] = $request->getHeaderLine('hx-prompt');
        }

        $this->tasks->update($parameters);

        $this->dispatchRequestHandled($request);
    }

    /**
     * @return void
     */
    public function delete(Request $request)
    {
        $this->tasks->delete($request->getParam('id'));

        $this->dispatchRequestHandled($request);
    }

    /**
     * @return void
     */
    private function dispatchRequestHandled(Request $request)
    {
        event_signal('EVENT_TODOLISTS_REQUEST_HANDLED', $request->getParam('bug_id'));
    }
}
