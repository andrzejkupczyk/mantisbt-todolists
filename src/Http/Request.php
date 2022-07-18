<?php

declare(strict_types=1);

namespace Mantis\ToDoLists\Http;

use ArrayAccess;
use RuntimeException;

class Request implements ArrayAccess
{
    /**
     * @var \Mantis\ToDoLists\Http\SanitizedUserInput
     */
    protected $parameters;

    public function __construct(SanitizedUserInput $parameters = null)
    {
        $this->parameters = $parameters ?: new SanitizedUserInput();
    }

    public function method(): string
    {
        $method = $this->header('x-http-method-override')
            ?? $_SERVER['REQUEST_METHOD'];

        return strtolower($method);
    }

    public function parameters(): SanitizedUserInput
    {
        return $this->parameters;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function header(string $name)
    {
        return $this->headers()[strtolower($name)] ?? null;
    }

    /**
     * @return string[]
     */
    public function headers(): array
    {
        return array_change_key_case(getallheaders());
    }

    public function offsetExists($offset)
    {
        return $this->parameters()->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->parameters()->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->throwCannotModify();
    }

    public function offsetUnset($offset)
    {
        $this->throwCannotModify();
    }

    private function throwCannotModify()
    {
        throw new RuntimeException('Request parameters cannot be modified');
    }
}
