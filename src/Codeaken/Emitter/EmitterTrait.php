<?php

namespace Codeaken\Emitter;

trait EmitterTrait
{
    private $listeners = [];

    public function on($event, callable $listener, $priority = 100)
    {
        if (!isset($this->listeners[$event])) {
            // This is the first time we have seen this event
            $this->listeners[$event] = [
                $priority => [ ]
            ];
        }

        if (!isset($this->listeners[$event][$priority])) {
            // This is the first time we have seen this priority for this event
            $this->listeners[$event][$priority] = [];
        }

        $this->listeners[$event][$priority][] = $listener;

        return $this;
    }

    public function emit($event)
    {
        // No point in continuing if there aren't any listeners for this event
        if (!isset($this->listeners[$event])) {
            return false;
        }

        // Get the arguments to pass on to the listener
        $args = [];
        if (func_num_args() > 1) {
            $args = array_slice(func_get_args(), 1);
        }

        // Sort priorities in the event array if the array is dirty
        ksort($this->listeners[$event]);

        // Call the listeners
        foreach ($this->listeners[$event] as $priority => $callables) {
            foreach ($callables as $callable) {
                call_user_func_array($callable, $args);
            }
        }

        return true;
    }
}
