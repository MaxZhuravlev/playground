<?php
/**
 * Created by Max Zhuravlev
 * Date: 9/17/12
 * Time: 10:22 AM
 *
 * Паттерн Компоновщик объединяет объекты в древовидные структуры для представления иерархии "часть/целое".
 * Компоновщик позволяет клиенту выполнять однородные операции с отдельными объектами и их совокупностями.
 * (То же самое что и composite1, но короче. Использует PHP ArrayObject)
 */

namespace composite2 {
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
            throw new \Exception("Unsupported operation");
        }

        /**
         * @param AComponent $component
         */
        public function remove($component)
        {
            throw new \Exception("Unsupported operation");
        }

        /**
         * @param int $int
         */
        public function getChild($int)
        {
            throw new \Exception("Unsupported operation");
        }

        public function operation1()
        {
            throw new \Exception("Unsupported operation");
        }
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
         * @var \ArrayObject AComponent[] $components для хранения потомков типа AComponent
         */
        public $components = null;

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
            if (is_null($this->components)) {
                $this->components = new \ArrayObject;
            }
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

            $iterator = $this->components->getIterator();
            while ($iterator->valid()) {
                $component = $iterator->current();
                $component->operation1();
                $iterator->next();
            }
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



