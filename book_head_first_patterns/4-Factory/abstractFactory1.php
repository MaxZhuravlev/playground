<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/22/12
 * Time: 6:42 PM
 */


interface AbstractFactory
{
    public function  createProductA();

    public function createProductB();
}

class ConcreteFactory1 implements AbstractFactory
{
    public function createProductA()
    {
        return new ConcreteProductA1();
    }

    public function createProductB()
    {
        return new ConcreteProductB1();
    }
}

class ConcreteFactory2 implements AbstractFactory
{
    public function createProductA()
    {
        return new ConcreteProductA2();
    }

    public function createProductB()
    {
        return new ConcreteProductB2();
    }
}

interface AbstractProductA
{
    public function getProductAName();
}

interface AbstractProductB
{
    public function getProductBName();
}

class ConcreteProductA1 implements AbstractProductA
{
    public function getProductAName()
    {
        return ("A1");
    }
}

class ConcreteProductB1 implements AbstractProductB
{
    public function getProductBName()
    {
        return ("B1");
    }
}

class ConcreteProductA2 implements AbstractProductA
{
    public function getProductAName()
    {
        return ("A2");
    }
}

class ConcreteProductB2 implements AbstractProductB
{
    public function getProductBName()
    {
        return ("B2");
    }
}

/**
 * Класс инкапсулирует фабрику, конструюирующую семейство структур $a и $b, он знает о них только то что они реализуют абстрактный интерфейс AbstractFactory
 */
class Client
{
    /**
     * @param AbstractFactory $factory
     */
    public function __construct($factory)
    {
        $a = $factory->createProductA();
        $b = $factory->createProductB();
        echo "\n{$a->getProductAName()} and {$b->getProductBName()}";
    }
}

/**
 * Класс определяет с какой фабрикой работать клиенту.
 */
class ClientRunner
{
    public static function run()
    {
        new Client(self::createRandFactory());
    }

    public static function createRandFactory()
    {
        if (rand(0, 1) > 0.5) {
            return new ConcreteFactory1();
        } else {
            return new ConcreteFactory2();
        }
    }
}

ClientRunner::run();
ClientRunner::run();
ClientRunner::run();
ClientRunner::run();
ClientRunner::run();
ClientRunner::run();
ClientRunner::run();
ClientRunner::run();
