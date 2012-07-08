<?php
/**
 * Второй Observer,
 * по аналогии с первым, с использованием Yii events.
 *
 * Плюсы
 * Этот способ гибче.
 * Наблюдаемому субъекту нужно ещё меньше знать о наблюдателе - не нужно знать как называется метод, которым наблюдатель хочет обработать событие.
 * Наблюдатель может назначать сразу несколько методов обработки события, в определенном порядке, и может отзывать конкретные действия, а не только все сразу.
 *
 *
 * Минусы
 * Этоn способ "отписки наблюдателей" не срабатывает, если мы пытаемся удалиться в процессе выполнения обработчиков onMeasurementsChanged! - сбивается итератор обработчиков.
 *
 * В данной версии интерфейсы вынесены наружу. И добавлено несколько наблюдателей.
 */


include "/home/maxlord/public_html/qnits/yiiapp.php";

/**
 * Наш наблюдатель
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
interface Observer21 {
    /**
     * @param Subject21 $subject
     * @return void
     */
    public function observeWeather($subject);

    /**
     * @param Subject $subject
     * @return void
     */
    public function stopObservingWeather($subject);


    public function update($event);

    public function update2($event);

}


/**
 * Общая часть от нескольких наших наблюдателей. Но могут быть и другие.
 */
interface DisplayElement21{
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
interface Subject21{

    /**
     * @desc технический момент,
     * для реализации возможности назначать выполнение новых методов, при наступлении события,
     * должен быть объявлен одноименный метод, с таким содержимым:
     * @param $event
     * @return void
     */
    public function onMeasurementsChanged($event);

    /**
     * @desc наблюдаемое событие, о котором надо оповещать.
     * @return void
     */
    public function measurementsChanged();

    /**
     * Этот метод для теста.
     * @param float $temperature
     * @param float $humidity
     * @param float $pressure
     * @return void
     */
    public function setMeasurements($temperature, $humidity, $pressure);

}

/**
 * Наш наблюдаемый субъект
 * При изменении Measurements, он сообщает об этом наблюдателям, которых может быть много.
 */
class WeatherData21 extends CModel{
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
class Display1_21 implements Observer21, DisplayElement21 {
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
     * @param Subject $subject
     * @return void
     */
    public function observeWeather($subject){
        /*
         * Наблюдатель говорит - когда у наблюдаемого произойдет такое-то событие, вызовите мой метод update и update2
         */
        $subject->onMeasurementsChanged = array($this, 'update');
        $subject->onMeasurementsChanged = array($this, 'update2');
    }

    /**
     * @param Subject $subject
     * @return void
     */
    public function stopObservingWeather($subject){
        /*
         * Наблюдатель говорит - когда у наблюдаемого произойдет такое-то событие, не надо вызывать мой метод update и update2
         * Этоn способ не срабатывает, если мы пытаемся удалиться в процессе выполнения обработчиков onMeasurementsChanged! - сбивается итератор обработчиков.
         */
        echo "<br/> Dispay1 Detach observer handler method update: ".$subject->detachEventHandler('onMeasurementsChanged',array($this, 'update'));
        echo "<br/> Dispay1 Detach observer handler method update2: ".$subject->detachEventHandler('onMeasurementsChanged',array($this, 'update2'));
        // можно отключить даже обработчики других наблюдателей:
        //$subject->getEventHandlers('onMeasurementsChanged')->clear();
    }


    public function update($event){
        $subject=$event->sender;
        $this->temperature=$subject->temperature;
        $this->humidity=$subject->humidity;
        $this->pressure=$subject->pressure;
        $this->display();
    }

    public function update2($event){
        $subject=$event->sender;
        echo "<br/> update 2, Whoa!";
        //var_dump($user); exit;
    }

    public function display(){
        echo("<br/>Dispay1 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}
/**
 * Наш наблюдатель 2
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display2_21 implements Observer21, DisplayElement21 {
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
     * @param Subject $subject
     * @return void
     */
    public function observeWeather($subject){
        /*
         * Наблюдатель говорит - когда у наблюдаемого произойдет такое-то событие, вызовите мой метод update и update2
         */
        $subject->onMeasurementsChanged = array($this, 'update');
        $subject->onMeasurementsChanged = array($this, 'update2');
    }

    /**
     * @param Subject $subject
     * @return void
     */
    public function stopObservingWeather($subject){
        /*
         * Наблюдатель говорит - когда у наблюдаемого произойдет такое-то событие, не надо вызывать мой метод update и update2
         */
        echo "<br/> Dispay2 Detach observer handler method update: ".$subject->detachEventHandler('onMeasurementsChanged',array($this, 'update'));
        echo "<br/> Dispay2 Detach observer handler method update2: ".$subject->detachEventHandler('onMeasurementsChanged',array($this, 'update2'));
        // можно отключить даже обработчики других наблюдателей:
        //$subject->getEventHandlers('onMeasurementsChanged')->clear();
    }


    public function update($event){
        $subject=$event->sender;
        $this->temperature=$subject->temperature;
        $this->humidity=$subject->humidity;
        $this->pressure=$subject->pressure;
        $this->display();
    }

    public function update2($event){
        $subject=$event->sender;
        echo "<br/> update 2, Whoa!";
        //var_dump($user); exit;
    }

    public function display(){
        echo("<br/>Dispay2 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}
/**
 * Наш наблюдатель 3
 * Который хочет чтобы ему сообщали об изменениях в наблюдаемом объекте.
 */
class Display3_21 implements Observer21, DisplayElement21 {
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
     * @param Subject $subject
     * @return void
     */
    public function observeWeather($subject){
        /*
         * Наблюдатель говорит - когда у наблюдаемого произойдет такое-то событие, вызовите мой метод update и update2
         */
        $subject->onMeasurementsChanged = array($this, 'update');
        $subject->onMeasurementsChanged = array($this, 'update2');
    }

    /**
     * @param Subject $subject
     * @return void
     */
    public function stopObservingWeather($subject){
        /*
         * Наблюдатель говорит - когда у наблюдаемого произойдет такое-то событие, не надо вызывать мой метод update и update2
         */
        echo "<br/> Dispay3 Detach observer handler method update: ".$subject->detachEventHandler('onMeasurementsChanged',array($this, 'update'));
        echo "<br/> Dispay3 Detach observer handler method update2: ".$subject->detachEventHandler('onMeasurementsChanged',array($this, 'update2'));
        // можно отключить даже обработчики других наблюдателей:
        //$subject->getEventHandlers('onMeasurementsChanged')->clear();
    }


    public function update($event){
        $subject=$event->sender;
        $this->temperature=$subject->temperature;
        $this->humidity=$subject->humidity;
        $this->pressure=$subject->pressure;
        $this->display();
    }

    public function update2($event){
        $subject=$event->sender;
        echo "<br/> update 2, Whoa!";
        //var_dump($user); exit;
    }

    public function display(){
        echo("<br/>Dispay3 Current conditions: $this->temperature, F degrees and $this->humidity% humidity");
    }
}



class WeatherStation21{
    function __construct(){
        $weatherData = new WeatherData21();
        $display1 = new Display1_21();
        $display1->observeWeather($weatherData);
        $display2 = new Display2_21();
        $display2->observeWeather($weatherData);
        $display3 = new Display3_21();
        $display3->observeWeather($weatherData);
        //$statisticsDisplay = new StatisticsDisplay($weatherData);
        //$forecastDisplay = new ForecastDisplay($weatherData);
        $weatherData->setMeasurements(10,20,10);
        $display1->stopObservingWeather($weatherData);
        $weatherData->setMeasurements(20,20,20);
        $display1->observeWeather($weatherData);
        $weatherData->setMeasurements(30,20,30);
    }
}


// Whoa!
$station=new WeatherStation21();