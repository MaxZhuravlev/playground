<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/21/12
 * Time: 5:29 PM
 */

interface IWithCostAndDescription
{
    public function getCost();

    public function getDescription();
}

abstract class Beverage implements IWithCostAndDescription
{
    protected $_description = 'Unknown beverage';

    public function getDescription()
    {
        return ($this->_description);
    }

    //public abstract function getCost();
}


abstract class CondimentDecorator implements IWithCostAndDescription
{
    //public abstract function getDescription();

    //public abstract function getCost();
}

class Espresso extends Beverage
{

    public function __construct()
    {
        $this->_description = 'Espresso';
    }

    public function getCost()
    {
        return (1.99);
    }
}

class HouseBlend extends Beverage
{

    public function __construct()
    {
        $this->_description = 'House Blend Coffee';
    }

    public function getCost()
    {
        return (0.89);
    }
}

class Mocha extends CondimentDecorator
{
    /**
     * @var IWithCostAndDescription
     */
    public $beverage;

    /**
     * @param $beverage IWithCostAndDescription
     */
    public function __construct($beverage)
    {
        $this->beverage = $beverage;
    }

    public function getDescription()
    {
        return implode(', ', array($this->beverage->getDescription(), 'Mocha'));
    }

    public function getCost()
    {
        return (0.2 + $this->beverage->getCost());
    }
}

class Soy extends CondimentDecorator
{
    /**
     * @var IWithCostAndDescription
     */
    public $beverage;

    /**
     * @param $beverage IWithCostAndDescription
     */
    public function __construct($beverage)
    {
        $this->beverage = $beverage;
    }

    public function getDescription()
    {
        return implode(', ', array($this->beverage->getDescription(), 'Soy'));
    }

    public function getCost()
    {
        return (0.1 + $this->beverage->getCost());
    }
}

class Whip extends CondimentDecorator
{
    /**
     * @var IWithCostAndDescription
     */
    public $beverage;

    /**
     * @param $beverage IWithCostAndDescription
     */
    public function __construct($beverage)
    {
        $this->beverage = $beverage;
    }

    public function getDescription()
    {
        return implode(', ', array($this->beverage->getDescription(), 'Whip'));
    }

    public function getCost()
    {
        return (0.3 + $this->beverage->getCost());
    }
}


class StarbuzzCoffee
{
    public static function main($args = null)
    {
        $beverage = new Espresso();
        echo("\n{$beverage->getDescription()} \${$beverage->getCost()}");

        $beverage = new HouseBlend();
        echo("\n{$beverage->getDescription()} \${$beverage->getCost()}");

        $beverage = new Espresso();
        $beverage = new Mocha($beverage);
        $beverage = new Soy($beverage);
        echo("\n{$beverage->getDescription()} \${$beverage->getCost()}");
    }
}

StarbuzzCoffee::main();



