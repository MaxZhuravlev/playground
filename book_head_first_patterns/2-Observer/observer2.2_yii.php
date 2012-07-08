<?php
/**
 * Второй Observer,
 * по аналогии с первым, с использованием Yii events.
 *
 * В данной версии интерфейсы вынесены наружу. И добавлено несколько наблюдателей.
 *
 * По прежнему остался минус с отпиской наблюдателя.Это баг иишного итератора CList.
 */

ini_set('display_errors',1);
include "/home/maxlord/public_html/qnits/yiiapp.php";
ini_set('display_errors',1);

/**
 * Наш наблюдатель
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
interface Observer22 {

    public function update($event);
}


/**
 * Общая часть от нескольких наших наблюдателей. Но могут быть и другие.
 */
interface DisplayElement22{
    /**
     * @abstract
     * @return void
     */
    public function display();
}

/**
 * Наш наблюдаемый субъект
 * При изменении Measurements, он сообщает об этом наблюдателям, которых может быть много.
 */
interface Subject22{
    /**
     * @param Observer $o
     * @return void
     */
    public function registerObserver($o);
    /**
     * @param Observer $o
     * @return void
     */
    public function removeObserver($o);

    /**
     * аналог метода notifyObservers в observer1
     * @desc технический момент,
     * для реализации возможности назначать выполнение обработчиков, при наступлении события,
     * должен быть объявлен одноименный метод, который будет вызывать эти обработчики
     * @param $event
     * @return void
     */
    public function onMeasurementsChanged($event);

}

/**
 * Наш наблюдаемый субъект
 * При изменении Measurements, он сообщает об этом наблюдателям, которых может быть много.
 */
class WeatherData22 extends CModel{
    /**
     * @var float $temperature
     */
    public $temperature;
    /**
     * @var float $humidity
     */
    public $humidity;
    /**
     * @var float $pressure
     */
    public $pressure;

	public function attributeNames(){
        array();
    }
    /**
     * @param Observer $o
     * @return void
     */
    public function registerObserver($o){        
        /*
         * Наблюдатель говорит - когда у наблюдаемого произойдет такое-то событие, вызовите мой метод update
         */
        $this->onMeasurementsChanged = array($o, 'update');
    }
    /**
     * @param Observer $o
     * @return void
     */
    public function removeObserver($o){
        // Это не срабатывает, если мы пытаемся удалиться в процессе выполнения обработчиков onMeasurementsChanged! - сбивается итератор обработчиков.
        $this->detachEventHandler('onMeasurementsChanged',array($o, 'update'));
    }

    /**
     * @desc технический момент,
     * для реализации возможности назначать выполнение новых методов, при наступлении события,
     * должен быть объявлен одноименный метод, с таким содержимым:
     * @param $event
     * @return void
     */
    public function onMeasurementsChanged($event){
        $this->raiseEvent('onMeasurementsChanged', $event);
    }

    /**
     * @desc наблюдаемое событие, о котором надо оповещать.
     * @return void
     */
    public function measurementsChanged(){
        /*
         * событие произошло, вызываем все действия, которые наблюдатели просили вызвать
         */
        if($this->hasEventHandler('onMeasurementsChanged')){
            $event = new CModelEvent($this);
            $this->onMeasurementsChanged($event);
        }
    }

    /**
     * Этот метод для теста.
     * @param float $temperature
     * @param float $humidity
     * @param float $pressure
     * @return void
     */
    public function setMeasurements($temperature, $humidity, $pressure){
        $this->temperature=$temperature;
        $this->humidity=$humidity;
        $this->pressure=$pressure;
        $this->measurementsChanged();
    }

}


/**
 * Наш наблюдатель 1
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display1_22 implements Observer22, DisplayElement22 {
    /**
     * @var \Subject22 $weatherData
     */
    private $weatherData;
    /**
     * @var float $temperature
     */
    private $temperature;
    /**
     * @var float $humidity
     */
    private $humidity;
    /**
     * @var float $pressure
     */
    private $pressure;

    /**
     * @param Subject22 $weatherData
     */
    public function __construct($weatherData){
        $this->weatherData=$weatherData;
        $weatherData->registerObserver($this);
    }

    public function update($event){
        $subject=$event->sender;
        $this->temperature=$subject->temperature;
        $this->humidity=$subject->humidity;
        $this->pressure=$subject->pressure;
        $this->display();
    }

    public function display(){
        echo("<br/>Dispay1 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}
/**
 * Наш наблюдатель 2
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display2_22 implements Observer22, DisplayElement22 {
    /**
     * @var \Subject22 $weatherData
     */
    private $weatherData;
    /**
     * @var float $temperature
     */
    private $temperature;
    /**
     * @var float $humidity
     */
    private $humidity;
    /**
     * @var float $pressure
     */
    private $pressure;

    /**
     * @param Subject22 $weatherData
     */
    public function __construct($weatherData){
        $this->weatherData=$weatherData;
        $weatherData->registerObserver($this);
    }

    public function update($event){
        $subject=$event->sender;
        $this->temperature=$subject->temperature;
        $this->humidity=$subject->humidity;
        $this->pressure=$subject->pressure;
        $this->display();
    }

    public function display(){
        echo("<br/>Dispay2 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
        //echo "<br/> Display2 - I stop observing.";
    }
}
/**
 * Наш наблюдатель 3
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display3_22 implements Observer22, DisplayElement22 {
    /**
     * @var \Subject22 $weatherData
     */
    private $weatherData;
    /**
     * @var float $temperature
     */
    private $temperature;
    /**
     * @var float $humidity
     */
    private $humidity;
    /**
     * @var float $pressure
     */
    private $pressure;

    /**
     * @param Subject22 $weatherData
     */
    public function __construct($weatherData){
        $this->weatherData=$weatherData;
        $weatherData->registerObserver($this);
    }

    public function update($event){
        $subject=$event->sender;
        $this->temperature=$subject->temperature;
        $this->humidity=$subject->humidity;
        $this->pressure=$subject->pressure;
        $this->display();
    }

    public function display(){
        echo("<br/>Dispay3 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}



class WeatherStation22{
    function __construct(){
        $weatherData = new WeatherData22();
        $display1 = new Display1_22($weatherData);
        $display2 = new Display2_22($weatherData);
        $display3 = new Display3_22($weatherData);
        //$statisticsDisplay = new StatisticsDisplay($weatherData);
        //$forecastDisplay = new ForecastDisplay($weatherData);
        $weatherData->setMeasurements(10,20,10);
        $weatherData->removeObserver($display1);

        $weatherData->setMeasurements(20,20,20);

        $weatherData->removeObserver($display3);
        $weatherData->setMeasurements(30,20,30);
    }
}


// Whoa!
$station=new WeatherStation22();