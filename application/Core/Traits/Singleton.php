<?php

namespace Core\Traits;

trait Singleton
{
    /**
     * @return self
     */
    public static function getInstance()
    {
        static $instance = null;
        return isset($instance)
            ? $instance
            : $instance = new static;
    }

    /**
     * Singleton constructor.
     */
    final private function __construct()
    {
        $this->init();
    }

    protected function init()
    {
    }
}