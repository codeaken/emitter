<?php

namespace Codeaken\Emitter;

interface EmitterInterface
{
    public function on($event, callable $listener, $priority = 100);
    public function emit($event);
}
