<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/21/12
 * Time: 1:34 PM
 *
 * Strategy pattern realisation, based on book "HeadFirst Design Patterns"
 */

interface QuackBehavior
{
    public function quack();
}

interface FlyBehavior
{
    public function fly();
}


class QuackNorml implements QuackBehavior
{
    public function quack()
    {
        echo "\nУтка крякает";
    }
}

class QuackSqueak implements QuackBehavior
{
    public function quack()
    {
        echo "\nУтка пищит как резиновая";
    }
}

class QuackMute implements QuackBehavior
{
    public function quack()
    {
        echo "\nУтка не издаёт звука!";
    }
}

class FlyWithWings implements FlyBehavior
{
    public function fly()
    {
        echo "\nУтка летит";
    }
}

class FlyNoFly implements FlyBehavior
{
    public function fly()
    {
        echo "\nУтка не летает";
    }
}


class Duck
{
    /**
     * @var FlyBehavior $flyBehavior
     */
    public $flyBehavior;
    /**
     * @var QuackBehavior $quackBehavior
     */
    public $quackBehavior;

    public function swim()
    {

    }

    public function display()
    {

    }

    public function performFly()
    {
        $this->flyBehavior->fly();
    }

    public function performQuack()
    {
        $this->quackBehavior->quack();
    }

    public function setFlyBehavior($q)
    {
        if ($q instanceof FlyBehavior) {
            $this->flyBehavior = $q;
        }
    }

    public function setQuackBehavior($q)
    {
        if ($q instanceof QuackBehavior) {
            $this->quackBehavior = $q;
        }
    }

}


class DuckMallard extends Duck
{

    public function __construct()
    {
        $this->setFlyBehavior(new FlyWithWings());
        $this->setQuackBehavior(new QuackNorml());
    }

    public function display()
    {
        echo "\nDuckMallard";
    }
}

class DuckRubber extends Duck
{

    public function __construct()
    {
        $this->setFlyBehavior(new FlyNoFly());
        $this->setQuackBehavior(new QuackSqueak());
    }

    public function display()
    {
        echo "\nDuckRubber";
    }
}

$mullard = new DuckMallard();
$mullard->performFly();
$mullard->performQuack();
$rubber = new DuckRubber();
$rubber->performFly();
$rubber->performQuack();


