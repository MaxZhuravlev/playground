<?php
/**
 * Created by Max Zhuravlev
 *
 * Здесь добавлен внешний итератор для компоновщика.
 * Паттерн Компоновщик объединяет объекты в древовидные структуры для представления иерархии "часть/целое".
 * Компоновщик позволяет клиенту выполнять однородные операции с отдельными объектами и их совокупностями.
 */


namespace compositeIterator {
    /**
     * Клиент использует интерфейс AComponent для работы с объектами.
     * Интерфейс AComponent определяет интерфейс для всех компонентов: как комбинаций, так и листовых узлов.
     * AComponent может реализовать поведение по умолчанию для add() remove() getChild() и других операций
     */
    abstract class AComponent
    {

        /**
         * @param AComponent $component
         */
        public function add($component)
        {
            throw new CException("Unsupported operation");
        }

        /**
         * @param AComponent $component
         */
        public function remove($component)
        {
            throw new CException("Unsupported operation");
        }

        /**
         * @param int $int
         */
        public function getChild($int)
        {
            throw new CException("Unsupported operation");
        }

        public function operation1()
        {
            throw new CException("Unsupported operation");
        }

        /**
         * @return CompositeIterator
         */
        abstract function createIterator();
    }

    /**
     * Leaf наследует методы add() remove() getChild( которые могут не иметь смысла для листового узла.
     * Хотя листовой узер можно считать узлом с нулём дочерних объектов
     *
     * Leaf определяет поведение элементов комбинации. Для этого он реализует операции, поддерживаемые интерфейсом Composite.
     */
    class Leaf extends AComponent
    {

        public $customPropertyName;

        public $customPropertyDescription;

        public function __construct($name, $description = '')
        {
            $this->customPropertyName = $name;
            $this->customPropertyDescription = $description;
        }

        public function createIterator()
        {
            return new NullIterator();
        }

        public function operation1()
        {
            echo ("\n I'am leaf {$this->customPropertyName}, i don't want to do operation 1. {$this->customPropertyDescription}");
        }
    }

    /**
     * Интерфейс Composite определяет поведение компонентов, имеющих дочерние компоненты, и обеспечивает хранение последних.
     *
     * Composite также реализует операции, относящиеся к Leaf. Некоторые из них не могут не иметь смысла для комбинаций; в таких случаях генерируется исключение.
     */
    class Composite extends AComponent
    {

        public $iterator = null;

        /**
         * @var Stack AComponent[] $components для хранения потомков типа AComponent
         */
        public $components;

        public $customPropertyName;

        public $customPropertyDescription;

        public function __construct($name, $description = '')
        {
            $this->components = new Stack;
            $this->customPropertyName = $name;
            $this->customPropertyDescription = $description;
        }

        /**
         * @param AComponent $component
         */
        public function add($component)
        {
            $this->components->push($component);
        }

        public function remove($component)
        {
            throw new CException("unsupported");
        }

        public function getChild($int)
        {
            return ($this->components->peek($int));
        }

        public function operation1()
        {
            echo "\n\n $this->customPropertyName $this->customPropertyDescription";
            echo "\n --------------------------------";

            $iterator = $this->createIterator();
            while ($iterator->hasNext()) {
                $component = $iterator->next();
                $component->operation1();
            }
        }

        public function createIterator()
        {
            if (is_null($this->iterator)) {
                // todo: вот тут я передаю не то что нужно
                $this->iterator = new CompositeIterator(new ConcreteIterator($this->components->stack));
            }
            return ($this->iterator);
        }
    }


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
        /**
         * @abstract
         * @return boolean есть ли следующий элемент в коллекции
         */
        public function hasNext();

        /**
         * @abstract
         * @return mixed следующий элемент массива
         */
        public function next();

        /**
         * Удаляет текущий элемент коллекции
         * @abstract
         * @return void
         */
        public function remove();
    }


    class ConcreteIterator implements IIterator
    {
        /**
         * @var AComponent[] $items
         */
        protected $items = array();

        /**
         * @var int $position хранит текущую позицию перебора в массиве
         */
        public $position = 0;

        /**
         * @param $items массив объектов, для перебора которых создается итератор
         */
        public function __construct($items)
        {
            $this->items = $items;
        }

        public function hasNext()
        {
            if ($this->position >= count($this->items) || count($this->items) == 0) {
                return (false);
            } else {
                return (true);
            }
        }

        public function next()
        {
            $menuItem = $this->items[$this->position];
            $this->position++;
            return ($menuItem);
        }

        public function remove()
        {
            if ($this->position <= 0) {
                throw new CException('Нельзя вызывать remove до вызова хотя бы одного next()');
            }
            if ($this->items[$this->position - 1] != null) {
                for ($i = $this->position - 1; $i < count($this->items); $i++) {
                    $this->items[$i] = $this->items[$i + 1];
                }
                $this->items[count($this->items) - 1] = null;
            }
        }
    }

    class CompositeIterator implements IIterator
    {
        /**
         * @var Stack $stack
         */
        public $stack = null;


        /**
         * @param $iterator
         */
        public function __construct($iterator)
        {
            if (is_null($this->stack)) {
                $this->stack = new Stack();
            }
            $this->stack->push($iterator);
        }

        public function hasNext()
        {
            if (empty($this->stack)) {
                return false;
            } else {
                if ($iterator = $this->stack->peek(null, false)) {
                    if (!$iterator->hasNext()) {
                        $this->stack->pop();
                        return ($this->hasNext());
                    } else {
                        return (true);
                    }
                } else {
                    return (false);
                }
            }
        }

        public function next()
        {
            if ($this->hasNext()) {
                $iterator = $this->stack->peek();
                $component = $iterator->next();
                if ($component instanceof Composite) {
                    $this->stack->push($component->createIterator());
                }
                return ($component);
            } else {
                return (null);
            }
        }

        public function remove()
        {
            throw new CException('Unsupported operation');
        }
    }


    class NullIterator implements IIterator
    {
        /**
         * @var AComponent[] $items
         */
        protected $items = array();

        /**
         * @var int $position хранит текущую позицию перебора в массиве
         */
        public $position = 0;

        /**
         * @param $items массив объектов, для перебора которых создается итератор
         */
        public function __construct()
        {
        }

        public function hasNext()
        {
            return (false);
        }

        public function next()
        {
            return (null);
        }

        public function remove()
        {
            throw new CException('Нельзя');
        }
    }


    class Client
    {
        /**
         * @var AComponent
         */
        public $topItem;

        public function __construct($topItem)
        {
            $this->topItem = $topItem;
        }

        public function printOperation1()
        {
            //$this->topItem->operation1();
        }

        public function printOperation2()
        {
            // это можно делать благодаря отдельному итератору
            $iterator = $this->topItem->createIterator();
            while ($iterator->hasNext()) {
                $component = $iterator->next();
                if (strstr($component->customPropertyName, 'leaf1')) {
                    $component->operation1();
                }
            }
            $this->topItem->operation1();
        }
    }

    class Test
    {
        public static function go()
        {
            $a = new Composite("c1");
            $b = new Composite("c2");
            $c = new Composite("c3");

            $topItem = new Composite("top item");
            $topItem->add($a);
            $topItem->add($b);
            $topItem->add($c);

            $a->add(new Leaf("c1-leaf1"));
            $a->add(new Leaf("c1-leaf2"));

            $b->add(new Leaf("c2-leaf1"));
            $b->add(new Leaf("c2-leaf2"));
            $b->add(new Leaf("c2-leaf3"));

            $c->add(new Leaf("c3-leaf1"));
            $c->add(new Leaf("c3-leaf2"));


            $client = new Client($topItem);
            $client->printOperation1();
            $client->printOperation2();
        }
    }

    /********************
     *    Stack Class    *
     *  Coded by Moonbat *
     *  October 5, 2008  *
     ********************/
    class Stack
    {

        var $stack;
        var $peeked = false;

        public function __construct()
        {
            $this->stack = array();
        }

        public function is_empty()
        {
            if (empty($this->stack)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function size()
        {
            return count($this->stack);
        }

        public function push($item)
        { // Shove an element on the stack
            if (array_push($this->stack, $item)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function pad_to($amount, $item = 0)
        { // Pad the stack with $item until stack size = $amount
            if ($amount < 0 || $amount < $this->size()) {
                return FALSE;
            } else {
                while ($this->size() != $amount) {
                    $this->push($item);
                }
                return TRUE;
            }
        }

        public function pad_extra($amount, $item = 0)
        { // Pad the stack with $item for $amount times
            if ($amount < 0) {
                return FALSE;
            } else {
                for ($i = 0; $i < $amount; $i++) {
                    $this->push($item);
                }
                unset($i);
                return TRUE;
            }
        }

        public function pop()
        { // Shoot off last item on the stack
            if (!$this->is_empty()) {
                array_pop($this->stack);
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function peek($position = null, $setPeaked = true)
        { // Get certain element on the stack
            if (is_null($position)) {
                if (!$this->peeked) {
                    $el = current($this->stack);
                } else {
                    $el = next($this->stack);
                }
                if ($el !== false) {
                    if ($el instanceof Stack) {
                        return ($el->peek($position, $setPeaked = true));
                    } else {
                        if ($setPeaked) {
                            $this->peeked = true;
                        }
                        return ($el);
                    }
                } else {
                    return (false);
                }
            }

            $elements = count($this->stack);
            if ($elements < $position) {
                unset($elements);
                return FALSE;
            } else {
                unset($elements);
                return $this->stack[$position];
            }
        }

        public function __destruct()
        {
            unset($this->stack);
        }

    }

    Test::go();
}




