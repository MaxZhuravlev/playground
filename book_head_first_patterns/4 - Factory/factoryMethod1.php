<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/22/12
 * Time: 6:04 PM
 */


abstract class PizzaStore
{
    /**
     * @param string $type
     * @return Pizza
     */
    public function orderPizza($type)
    {
        $pizza = $this->createPizza($type);

        $pizza->prepare();
        $pizza->bake();
        $pizza->cut();
        $pizza->box();

        return ($pizza);
    }

    /**
     * @abstract
     * @param  string $type
     * @return Pizza
     */
    public abstract function createPizza($type);
    //public abstract function getDescription();

    //public abstract function getCost();
}


abstract class Pizza
{
    protected $_name;
    protected $_dough;
    protected $_sauce;
    protected $_toppings = array();

    public function prepare()
    {
        echo "\n prepare..";
    }

    public function bake()
    {
        echo "\n bake..";
    }

    public function cut()
    {
        echo "\n cut..";
    }

    public function box()
    {
        echo "\n box..";
    }

    public function getName()
    {
        return ($this->_name);
    }
}

class NYStyleCheesePizza extends Pizza
{
    public function __construct()
    {
        $this->_name = "\n NY style cheese pizza";
        $this->_dough = "\n NY dough";
        $this->_sauce = "\n NY sauce";
        $this->_toppings = array("\n NY topping");
    }
}

class ChicagoStyleCheesePizza extends Pizza
{
    public function __construct()
    {
        $this->_name = "\n Chicago style cheese pizza";
        $this->_dough = "\n Chicago dough";
        $this->_sauce = "\n Chicago sauce";
        $this->_toppings = array("\n Chicago topping");
    }
}

class NYStylePizzaStore extends PizzaStore
{
    public function createPizza($type)
    {
        if ($type == 'cheese') {
            return new NYStyleCheesePizza();
        } elseif ($type == 'any other') {
            // таким образом можно перечислять другие типы пицц
            return null;
        }
    }
}

class ChicagoStylePizzaStore extends PizzaStore
{
    public function createPizza($type)
    {
        if ($type == 'cheese') {
            return new ChicagoStyleCheesePizza();
        } elseif ($type == 'any other') {
            // таким образом можно перечислять другие типы пицц
            return null;
        }
    }
}


class PizzaTestDrive
{
    public function run()
    {
        $nyStore = new NYStylePizzaStore();
        $chicagoStore = new ChicagoStylePizzaStore();

        $pizza = $nyStore->orderPizza('cheese');
        echo("{$pizza->getName()}");

        echo "\n\n";

        $pizza = $chicagoStore->orderPizza('cheese');
        echo("{$pizza->getName()}");
    }
}

$test = new PizzaTestDrive();
$test->run();