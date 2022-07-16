<?php

declare(strict_types=1);

namespace Mantis\ToDoLists;

class HtmxHelper
{
    public static function triggerHeader($event)
    {
        if (is_array($event)) {
            $event = json_encode($event);
        }

        header("HX-Trigger: $event");
    }
}
