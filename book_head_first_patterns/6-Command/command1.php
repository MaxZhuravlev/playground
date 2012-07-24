<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/24/12
 * Time: 9:56 AM
 *
 * Command без отдельного Invoker-а
 */


/**
 * Абстрактный класс команды
 */
abstract class Command
{
    public abstract function Execute();
    public abstract function UnExecute();
}

/**
 * Класс конкретной команды
 */
class ConcreteCommand extends Command{
    /**
     * @var string Текущая операция команды
     */
    public $paramA;

    /**
     * @var mixed $paramB Текущий операнд ??
     */
    public $paramB;

    /**
     * @var Receiver Класс для которого предназачена команда
     */
    public $receiver;

    public function __construct($receiver,$paramA,$paramB)
    {
        $this->receiver=$receiver;
        $this->paramA=$paramA;
        $this->paramB=$paramB;
    }

    public function Execute()
    {
        $this->receiver->Operation($this->paramA,$this->paramB);
    }

    public function UnExecute()
    {
        $this->receiver->Operation($this->Undo($this->paramA),$this->paramB);
    }

    private function Undo($paramA){
        switch($paramA){
            case '+': $undo = '-'; break;
            case '-': $undo = '+'; break;
            case '*': $undo = '/'; break;
            case '/': $undo = '*'; break;
            default : $undo = ' '; break;
        }
        return($undo);
    }
}

/**
 * Класс получатель и исполнитель команд
 */
class Receiver
{
    /**
     * @var int  Текущий результат выполения команд
     */
    private $curr=0;

    public function Operation($paramA,$paramB){
        switch($paramA){
            case '+': $this->curr+=$paramB; break;
            case '-': $this->curr-=$paramB; break;
            case '*': $this->curr*=$paramB; break;
            case '/': $this->curr/=$paramB; break;
        }
        print("\nТекущий результат = $this->curr (после выполнения $paramA с $paramB)");
    }
}

/**
 * Класс вызывающий команды
 */
class Client
{

    /**
     * @var Receiver Этот класс будет получать команды на исполнение
     */
    private $_receiver;

    /**
     * @var array Массив операций
     */
    private $_commands = array();

    /**
     * @var int Текущая команда в массиве операций
     */
    private $_current=0;

    public function __construct(){
        $this->_receiver=new Receiver();
    }

    public function Redo($levels){
        print("\n Повторить $levels операций");
        for($i=0;$i<$levels;$i++){
            if($this->_current<count($this->_commands)){
                $this->_commands[$this->_current++]->Execute();
            }
        }
    }

    public function Undo($levels){
        print("\n Отменить $levels операций");
        for($i=0;$i<$levels;$i++){
            if($this->_current>0){
                $this->_commands[--$this->_current]->UnExecute();
            }
        }
    }

    public function Compute($paramA,$paramB)
    {
        $command=new ConcreteCommand($this->_receiver,$paramA,$paramB);
        $command->Execute();

        $this->_commands[]=$command;
        $this->_current++;
    }

}

$client= new Client();
$client->Compute('+',100);
$client->Compute('-',50);
$client->Compute('*',10);
$client->Compute('/',2);

$client->Undo(4);
$client->Redo(3);
