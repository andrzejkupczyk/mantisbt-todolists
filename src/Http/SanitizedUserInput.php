<?php

declare(strict_types=1);

namespace WebGarden\ToDoLists\Http;

use RuntimeException;

class SanitizedUserInput
{
    /**
     * @var array
     */
    protected $parameters = [];

    public function all(): array
    {
        $this->hydrate();

        return $this->parameters;
    }

    /**
     * @param string $name
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->parameters[$name];
        }

        throw new RuntimeException("Parameter `{$name}` does not exist");
    }

    /**
     * @param string $name
     */
    public function has($name): bool
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * @param string ...$names
     */
    public function only(...$names): array
    {
        return array_intersect_key($this->all(), array_flip($names));
    }

    protected function hydrate()
    {
        if ($this->isHydrated()) {
            return;
        }

        $this->parameters = [
            'id' => gpc_get_int('id', null),
            'bug_id' => gpc_get_int('bug_id'),
            'finished' => gpc_get_bool('finished', false),
            'description' => gpc_get_string('description'),
        ];
    }

    protected function isHydrated(): bool
    {
        return !empty($this->parameters);
    }
}
