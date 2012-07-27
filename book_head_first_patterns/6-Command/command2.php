<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/24/12
 * Time: 9:56 AM
 *
 * Command2 без отдельного Invoker2-а
 */


/**
 * Абстрактный класс команды
 */
abstract class Command2
{
    public abstract function execute();

    public abstract function unexecute();
}

/**
 * Класс конкретной команды
 * Связывает операции с Receiver2.
 * Invoker2 выдает запрос, вызывая метод execute(), а concreteCommand2 выполняет его, активируя операции у получателя.
 */
class ConcreteCommand2 extends Command2
{
    /**
     * @var string Текущая операция команды
     */
    public $paramA;

    /**
     * @var mixed $paramB Текущий операнд ??
     */
    public $paramB;

    /**
     * @var Receiver2
     */
    public $Receiver2;

    public function __construct($Receiver2, $paramA, $paramB)
    {
        $this->Receiver2 = $Receiver2;
        $this->paramA = $paramA;
        $this->paramB = $paramB;
    }

    public function execute()
    {
        $this->Receiver2->Operation($this->paramA, $this->paramB);
    }

    public function unexecute()
    {
        $this->Receiver2->Operation($this->untiParamA($this->paramA), $this->paramB);
    }

    private function untiParamA($paramA)
    {
        switch ($paramA) {
            case '+':
                $undo = '-';
                break;
            case '-':
                $undo = '+';
                break;
            case '*':
                $undo = '/';
                break;
            case '/':
                $undo = '*';
                break;
            default :
                $undo = ' ';
                break;
        }
        return ($undo);
    }
}

/**
 * Класс получатель и исполнитель команд
 * Умеет выполнять операции, необходимые для выполнения запроса
 */
class Receiver2
{
    /**
     * @var int  Текущий результат выполения команд
     */
    private $curr = 0;

    public function Operation($paramA, $paramB)
    {
        switch ($paramA) {
            case '+':
                $this->curr += $paramB;
                break;
            case '-':
                $this->curr -= $paramB;
                break;
            case '*':
                $this->curr *= $paramB;
                break;
            case '/':
                $this->curr /= $paramB;
                break;
        }
        print("\nТекущий результат = $this->curr (после выполнения $paramA с $paramB)");
    }
}

/**
 * Хранит команду и в определенный момент отдает запрос на её выполнение, вызывая метод execute()
 */
class Invoker2
{
    /**
     * @var null / ConcreteCommand2
     */
    private $_concreteCommand2 = null;

    /**
     * @var array Массив операций
     */
    private $_Command2s = array();

    /**
     * @var int Текущая команда в массиве операций
     */
    private $_current = 0;

    public function executeCommand2()
    {
        if ($this->_concreteCommand2 instanceof ConcreteCommand2) {
            $this->_concreteCommand2->execute();
            $this->_Command2s[] = $this->_concreteCommand2;
            $this->_current++;
            $this->_concreteCommand2 = null;
        }
    }

    /**
     * @param ConcreteCommand2 $concreteCommand2
     */
    public function setCommand2($concreteCommand2)
    {
        $this->_concreteCommand2 = $concreteCommand2;
    }

    public function redo($levels)
    {
        print("\n Повторить $levels операций");
        for ($i = 0; $i < $levels; $i++) {
            if ($this->_current < count($this->_Command2s)) {
                $this->_Command2s[$this->_current++]->execute();
            }
        }
    }

    public function undo($levels)
    {
        print("\n Отменить $levels операций");
        for ($i = 0; $i < $levels; $i++) {
            if ($this->_current > 0) {
                $this->_Command2s[--$this->_current]->unexecute();
            }
        }
    }

}

/**
 * Класс вызывающий команды
 */
class Client2
{
    /**
     * @var Invoker2
     */
    protected $_Invoker2 = null;

    /**
     * @var Receiver2
     */
    protected $_Receiver2 = null;

    public function __construct()
    {
        $this->_Invoker2 = new Invoker2();
        $this->_Receiver2 = new Receiver2();
    }

    public function compute($paramA, $paramB)
    {
        $this->_Invoker2->setCommand2(new ConcreteCommand2($this->_Receiver2, $paramA, $paramB));
        $this->_Invoker2->executeCommand2();
    }

    public function undo($levels)
    {
        return ($this->_Invoker2->undo($levels));
    }

    public function redo($levels)
    {
        return ($this->_Invoker2->redo($levels));
    }

}


$Client2 = new Client2();
$Client2->compute('+', 100);
$Client2->compute('-', 50);
$Client2->compute('*', 10);
$Client2->compute('/', 2);

$Client2->undo(4);
$Client2->redo(3);
