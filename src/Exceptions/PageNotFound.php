<?php

declare(strict_types=1);

namespace Mantis\ToDoLists\Exceptions;

use Mantis\Exceptions\ClientException;

class PageNotFound extends ClientException
{
    public function __construct(string $name)
    {
        parent::__construct(
            "Page {$name} not found",
            ERROR_PLUGIN_PAGE_NOT_FOUND,
            [plugin_get_current(), $name]
        );
    }
}
