<?php

declare(strict_types=1);

namespace Mantis\ToDoLists\Routing;

class Route
{
    /**
     * @var callable
     */
    protected $action;

    /**
     * @param array|string $action
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    public function __invoke(): array
    {
        return is_string($this->action)
            ? explode('@', $this->action, 2)
            : $this->action;
    }
}
