<?php
/**
 * Первый Observer,
 * полностью основан на примере из HeadFirst Design Patterns.
 */


ini_set('display_errors',1);

include "/home/maxlord/public_html/qnits/yiiapp.php";

/**
 * Наблюдаемый объект.
 * При изменении чего-либо, он сообщает об этом наблюдателям, которых может быть много.
 */
interface Subject
{
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
     * @return void
     */
    public function notifyObservers();
}

/**
 * Наблюдатель.
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
interface Observer {
    /**
     * @abstract
     * @param float $temperature
     * @param float $humidity
     * @param float $pressure
     * @return void
     */
    public function update($temperature, $humidity, $pressure);
}

/**
 * Общая часть от нескольких наших наблюдателей. Но могут быть и другие.
 */
interface DisplayElement{
    /**
     * @abstract
     * @return void
     */
    public function display();
}

class WeatherStation{
    function __construct(){
        $weatherData = new WeatherData();
        $currentDisplay = new CurrentConditionsDisplay($weatherData);
        //$statisticsDisplay = new StatisticsDisplay($weatherData);
        //$forecastDisplay = new ForecastDisplay($weatherData);

        $weatherData->setMeasurements(10,20,10);
        $weatherData->setMeasurements(20,20,20);
        $weatherData->setMeasurements(30,20,30);
    }
}


/**
 * Наш субъект
 */
class WeatherData implements Subject{
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
     * @var array $observers
     */
    private $observers=array();

    public function __construct(){
        //$this->observers=array();
    }

    /**
     * @param Observer $o
     * @return void
     */
    public function registerObserver($o){
        // новые наблюдатели просто добавляются в конец списка
        $this->observers[]=$o;
    }

    /**
     * @param Observer $o
     * @return void
     */
    public function removeObserver($o){
        // если наблюдатель хочет отменить регистрацию, мы просто удаляем его из списка
        foreach($this->observers as $i=>$ob){
            if($o===$ob){
                unset($this->observers[$i]);
            }
        }
    }

    /**
     * @return void
     */
    public function notifyObservers(){
        // Оповещение наблюдателей об изменении состояния через метод update(), реализуемый всеми наблюдателями
        foreach($this->observers as $ob){
            $ob->update($this->temperature,$this->humidity,$this->pressure);
        }
    }

    public function measurementsChanged(){
        $this->notifyObservers();
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
 * Наш наблюдатель
 */
class CurrentConditionsDisplay implements Observer, DisplayElement {
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
     * @var \Subject $weatherData
     */
    private $weatherData;

    /**
     * @param Subject $weatherData
     */
    public function __construct($weatherData){
        $this->weatherData=$weatherData;
        $weatherData->registerObserver($this);
    }

    /**
     * @param float $temperature
     * @param float $humidity
     * @param float $pressure
     * @return void
     */
    public function update($temperature, $humidity, $pressure){
        $this->temperature=$temperature;
        $this->humidity=$humidity;
        $this->pressure=$pressure;
        $this->display();
    }

    public function display(){
        echo("<br/>Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
        $this->weatherData->removeObserver($this);
    }

}

// Whoa!
$station=new WeatherStation();