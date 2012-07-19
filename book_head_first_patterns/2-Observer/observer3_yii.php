<?php
/**
 * Простейшая реализация наблюдателя на yii.
 * Минимум кода.
 *
 * по прежнему никак не "отписаться в процессе получения подписки".
 */

include "/home/maxlord/public_html/qnits/yiiapp.php";


/**
 * Наш наблюдатель
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
interface Observer30
{

    public function update($event);
}


/**
 * Общая часть от нескольких наших наблюдателей. Но могут быть и другие.
 */
interface DisplayElement30
{
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
interface Subject30
{
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
class WeatherData30 extends CModel implements Subject30
{
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

    public function attributeNames()
    {
        array();
    }

    /**
     * @desc технический момент,
     * для реализации возможности назначать выполнение новых методов, при наступлении события,
     * должен быть объявлен одноименный метод, с таким содержимым:
     * @param $event
     * @return void
     */
    public function onMeasurementsChanged($event)
    {
        $this->raiseEvent('onMeasurementsChanged', $event);
    }

    /**
     * @desc наблюдаемое событие, о котором надо оповещать.
     * @return void
     */
    public function measurementsChanged()
    {
        /*
         * событие произошло, вызываем все действия, которые наблюдатели просили вызвать
         */
        if ($this->hasEventHandler('onMeasurementsChanged')) {
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
    public function setMeasurements($temperature, $humidity, $pressure)
    {
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->pressure = $pressure;
        $this->measurementsChanged();
    }

}

/**
 * Наш наблюдатель 1
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display1_30 implements Observer30, DisplayElement30
{
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
     * @param Subject30 $weatherData
     */
    public function __construct($weatherData)
    {
        $weatherData->onMeasurementsChanged($this, 'update');
    }

    public function update($event)
    {
        $subject = $event->sender;
        $this->temperature = $subject->temperature;
        $this->humidity = $subject->humidity;
        $this->pressure = $subject->pressure;
        $this->display();
    }

    public function display()
    {
        echo("<br/>Dispay1 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}

/**
 * Наш наблюдатель 1
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display2_30 implements Observer30, DisplayElement30
{
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
     * @param Subject30 $weatherData
     */
    public function __construct($weatherData)
    {
        $this->weatherData = $weatherData;
        $weatherData->onMeasurementsChanged($this, 'update');
    }

    public function update($event)
    {
        $subject = $event->sender;
        $this->temperature = $subject->temperature;
        $this->humidity = $subject->humidity;
        $this->pressure = $subject->pressure;
        $this->display();
    }

    public function display()
    {
        echo("<br/>Dispay2 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}

/**
 * Наш наблюдатель 1
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display3_30 implements Observer30, DisplayElement30
{
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
     * @param Subject30 $weatherData
     */
    public function __construct($weatherData)
    {
        $this->weatherData = $weatherData;
        $weatherData->onMeasurementsChanged($this, 'update');
    }

    public function update($event)
    {
        $subject = $event->sender;
        $this->temperature = $subject->temperature;
        $this->humidity = $subject->humidity;
        $this->pressure = $subject->pressure;
        $this->display();
    }

    public function display()
    {
        echo("<br/>Dispay3 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}

class WeatherStation30
{
    function __construct()
    {
        $weatherData = new WeatherData30();
        $display1 = new Display1_30($weatherData);
        $display2 = new Display2_30($weatherData);
        $display3 = new Display3_30($weatherData);
        //$statisticsDisplay = new StatisticsDisplay($weatherData);
        //$forecastDisplay = new ForecastDisplay($weatherData);
        $weatherData->setMeasurements(10, 20, 10);
        $weatherData->detachEventHandler('onMeasurementsChanged', array($display1, 'update'));

        $weatherData->setMeasurements(20, 20, 20);

        $weatherData->detachEventHandler('onMeasurementsChanged', array($display2, 'update'));
        $weatherData->setMeasurements(30, 20, 30);
    }
}