<?php
/**
 * Created by Max Zhuravlev
 * Date: 9/24/12
 * Time: 10:50 AM
 *
 * Более конкретный пример на основе паттерна "Состояние".
 * Паттерн Состояние управляет изменением поведения объекта при изменении его внутреннего состояния.
 * Внешне это выглядит так, словно объект меняет свой класс.
 */

namespace state2 {


    class Client
    {
        public function __construct()
        {
            $context = new Context();
            $context->dispense();
            $context->insertQuarter();
            $context->turnCrank();
            $context->insertQuarter();
            $context->turnCrank();
            $context->insertQuarter();
            $context->turnCrank();
        }
    }

    class Test
    {
        public static function go()
        {
            $client = new Client();
        }
    }

    /**
     *  Класс с несколькими внутренними состояниями
     */
    class Context
    {
        /**
         * @var AState
         */
        public $state;

        /**
         * Возможные состояния
         */
        const STATE_SOLD_OUT = 1;
        const STATE_NO_QUARTER_STATE = 2;
        const STATE_HAS_QUARTER_STATE = 3;
        const STATE_SOLD_STATE = 4;
        const STATE_WINNER_STATE = 5;

        /**
         * @var int Сколько жвачки в автомате?
         */
        public $count = 2;

        public function __construct()
        {
            $this->setState(Context::STATE_NO_QUARTER_STATE);
        }

        /**
         * Действия Context делегируются объектам состояний для обработки
         */
        public function insertQuarter()
        {
            $this->state->insertQuarter();
        }

        public function ejectQuarter()
        {
            $this->state->ejectQuarter();
        }

        public function turnCrank()
        {
            $this->state->turnCrank();
            $this->state->dispense();
        }

        public function dispense()
        {
            $this->state->dispense();
        }

        /**
         * Это один из способов реализации переключения состояний
         * @param $state выбранное состояние, возможные варианты перечислены в списке констант Context::STATE_..
         */
        public function setState($state)
        {
            if ($state == Context::STATE_SOLD_OUT) {
                $this->state = new ConcreteStateSoldOut($this);
            } elseif ($state == Context::STATE_NO_QUARTER_STATE) {
                $this->state = new ConcreteStateNoQuarter($this);
            } elseif ($state == Context::STATE_HAS_QUARTER_STATE) {
                $this->state = new ConcreteStateHasQuarter($this);
            } elseif ($state == Context::STATE_SOLD_STATE) {
                $this->state = new ConcreteStateSoldState($this);
            } elseif ($state == Context::STATE_WINNER_STATE) {
                $this->state = new ConcreteStateWinnerState($this);
            }
        }

        public function releaseBall()
        {
            if ($this->count > 0) {
                echo "Ball released";
                $this->count -= 1;
            } else {
                echo "No balls to release :(";
            }
        }

    }

    /**
     * Общий интерфейс всех конкретных состояний.
     * Все состояния реализуют один интерфейс, а следовтельно, являются взаимозаменяемыми.
     */
    class AState
    {
        /**
         * @var Context храним ссылку на контекст для удобного переключения состояний
         */
        protected $context;

        public function __construct(&$context)
        {
            $this->context =& $context;
        }

        /**
         * Обработка в разных состояниях может отличаться.
         * Если AState не просто интерфейс а абстрактный класс,
         * то он может содержать стандартные обработки, тогда классы конкретных состояний будут описывать только свои особенности относительно стандартного поведения.
         */
        public function insertQuarter()
        {
            echo "\n lol, you can't do that";
        }

        public function ejectQuarter()
        {
            echo "\n lol, you can't do that";
        }

        public function turnCrank()
        {
            echo "\n lol, you can't do that";
        }

        public function dispense()
        {
            echo "\n lol, you can't do that";
        }

    }

    /**
     * Далее идёт набор конкретных состояний, которые обрабатывают запросы от Context.
     * Каждый класс предоставляет собственную реализацию запроса.
     * Таким образом, при переходе объекта Context в другое состояние, меняется и его повденеие.
     */

    class ConcreteStateSoldOut extends AState
    {

        public function insertQuarter()
        {
            echo "\n sorry, i'm sold out, can't take quarters";
        }

    }

    class ConcreteStateNoQuarter extends AState
    {
        public function insertQuarter()
        {
            echo "\n got quarter, yeah!";
            // переключаем состояние
            $this->context->setState(Context::STATE_HAS_QUARTER_STATE);
        }
    }

    class ConcreteStateHasQuarter extends AState
    {
        public function ejectQuarter()
        {
            echo "\n take your money back";
            // переключаем состояние
            $this->context->setState(Context::STATE_NO_QUARTER_STATE);
        }

        public function turnCrank()
        {
            echo "\n you turned";
            $winner = rand(1, 10) == 10 ? 1 : 0;
            if ($winner) {
                $this->context->setState(Context::STATE_WINNER_STATE);
            } else {
                $this->context->setState(Context::STATE_SOLD_STATE);
            }
        }
    }


    class ConcreteStateSoldState extends AState
    {
        public function dispense()
        {
            echo "\n dispensing, yeah!";
            $this->context->releaseBall();
            if ($this->context->count == 0) {
                $this->context->setState(Context::STATE_SOLD_OUT);
            } else {
                // переключаем состояние
                $this->context->setState(Context::STATE_NO_QUARTER_STATE);
            }
        }
    }


    class ConcreteStateWinnerState extends AState
    {
        public function dispense()
        {
            echo "\n dispensing, yeah!";
            $this->context->releaseBall();
            if ($this->context->count == 0) {
                $this->context->setState(Context::STATE_SOLD_OUT);
            } else {
                echo "\n p.s. you are WINNER, you get extra ball!";
                $this->context->releaseBall();
                if ($this->context->count == 0) {
                    $this->context->setState(Context::STATE_SOLD_OUT);
                } else {
                    $this->context->setState(Context::STATE_NO_QUARTER_STATE);
                }
            }
        }
    }


    Test::go();
}



