<?php
/**
 * Created by Max Zhuravlev
 * Date: 8/4/12
 * Time: 3:31 PM
 *
 * Паттерн итератор предоставляет механизм последовательного перебора элементов коллекции без раскрытия реализации коллекции.
 *
 * Перебор элементов выполняется объектом итератора, а не самой коллекцией.
 * Это упрощает интерфейс и реализацию коллекции, а также способствует более логичному распределению обязанностей.
 */

/**
 * Наличие общего интерфейса удобно для клиента, поскольку клиент отделяется от реализации коллекции объектов.
 *
 * ConcreteAggregate содержит коллекцию объектов и реализует метод, который возвращает итератор для этой коллекции.
 */
interface IAggregate
{
    /**
     * Каждая разновидность ConcreteAggregate отвечает за создание экземпляра Concrete Iterator,
     * который может использоваться для перебора своей коллекции объектов.
     */
    public function createIterator();
}

/**
 * Интерфейс Iterator должен быть реализован всеми итераторами.
 *
 * ConcreteIterator отвечает за управление текущей позицией перебора.
 */
interface IIterator
{
    public function hasNext();

    public function next();

    public function remove();
}

class ConcreteAggregate implements IAggregate
{
    public function createIterator()
    {

    }
}

class ConcreteIterator implements IIterator
{
    public function hasNext()
    {

    }

    public function next()
    {

    }

    public function remove()
    {

    }
}