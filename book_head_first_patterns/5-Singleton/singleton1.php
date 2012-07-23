<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/23/12
 * Time: 8:56 AM
 */

class Singleton
{
    protected static $instance;

    /**
     * Защищаем от создания через new Singleton
     */
    private function __construct()
    {

    }

    /**
     * Защищаем от создания через clone
     */
    private function __clone()
    {

    }

    /**
     * Защищаем от создания через wakeup
     */
    private function __wakeup()
    {

    }

    /**
     * @static
     * @return Singleton
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Singleton();
        }
        return (self::$instance);
    }

    public function doAction()
    {

    }
}

// применение
Singleton::getInstance()->doAction();