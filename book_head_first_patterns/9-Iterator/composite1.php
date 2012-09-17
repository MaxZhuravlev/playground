<?php
/**
 * Created by Max Zhuravlev
 * Date: 8/4/12
 * Time: 4:53 PM
 *
 * Паттерн Компоновщик объединяет объекты в древовидные структуры для представления иерархии "часть/целое".
 * Компоновщик позволяет клиенту выполнять однородные операции с отдельными объектами и их совокупностями.
 */

namespace composite1 {
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

        /**
         * @var AComponent[] $components для хранения потомков типа AComponent
         */
        public $components;

        public $customPropertyName;

        public $customPropertyDescription;

        public function __construct($name, $description = '')
        {
            $this->customPropertyName = $name;
            $this->customPropertyDescription = $description;
        }

        /**
         * @param AComponent $component
         */
        public function add($component)
        {
            $this->components[] = $component;
        }

        public function remove($component)
        {
            foreach ($this->components as $i => $c) {
                if ($c === $component) {
                    unset($this->components[$i]);
                }
            }
        }

        public function getChild($int)
        {
            return ($this->components[$int]);
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
            return new ConcreteIterator($this->components);
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
        }
    }

    Test::go();
}



