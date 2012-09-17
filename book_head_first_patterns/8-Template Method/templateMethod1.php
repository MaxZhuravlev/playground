<?php
/**
 * Created by Max Zhuravlev
 * Date: 8/4/12
 * Time: 12:12 PM
 *
 * Паттерн Шаблонный метод задаёт "скелет" алгоритма в методе, оставляя опредление реализации некоторых шагов субклассам.
 * Субклассы могут переопределять некоторые части алгоритма без изменения его структуры.
 */


abstract class templateMethodAbstractClass
{

    /**
     * Шаблонный метод объявляется с ключевым словом final, чтобы субклассы не могли изменить последовательность шагов в алгоритме
     */
    final function templateMethod()
    {
        $this->primitiveOperation1();
        $this->hook1();
        $this->primitiveOperation2();
        if ($this->hook2()) {
            $this->primitiveOperation2();
        }
        $this->concreteCommonOperation();
    }

    abstract function primitiveOperation1();

    abstract function primitiveOperation2();

    protected function concreteCommonOperation()
    {
        // реализация
        echo "\n common operation";
    }

    /*
     * Конкретный метод, который не делает ничего.
     * Субклассы могут переопределять такие методы-перехватчики, ноне обязаны это делать.
     */
    public function hook1()
    {
    }

    /**
     * Другой пример перехватчика. Данный перехватчик учавствует в условной конструкции.
     * @return bool
     */
    public function hook2()
    {
        return (false);
    }
}


class templateMethodConcreteClass extends templateMethodAbstractClass
{
    function primitiveOperation1()
    {
        echo "\n 1";
    }

    function primitiveOperation2()
    {
        echo "\n 2";
    }

    function hook1()
    {
        echo "\n hook1";
    }

    function hook2()
    {
        return (round(rand(0, 1)));
    }

}

$q = new templateMethodConcreteClass();
$q->templateMethod();
$q->templateMethod();
$q->templateMethod();
$q->templateMethod();