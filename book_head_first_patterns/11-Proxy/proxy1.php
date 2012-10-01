<?php
/**
 * Created by Max Zhuravlev
 * Date: 10/1/12
 * Time: 10:15 AM
 *
 *
 */

namespace proxy1 {

    interface Subject
    {
        public function displayImage();
    }

    class RealSubject implements Subject
    {
        private $_filename = null;

        public function __construct($filename)
        {
            $this->_filename = $filename;
            $this->loadImageFromDisk();
        }

        private function loadImageFromDisk()
        {
            echo "\n !! Loading " . $this->_filename;
        }

        public function displayImage()
        {
            echo "\n Displaying " . $this->_filename;
        }
    }

    class ProxySubject implements Subject
    {
        /**
         * @var RealSubject
         */
        private $_realSubject;
        private $_filename = null;

        public function __construct($filename)
        {
            $this->_filename = $filename;
        }

        public function displayImage()
        {
            if (is_null($this->_realSubject)) {
                $this->_realSubject = new RealSubject($this->_filename);
            }
            $this->_realSubject->displayImage();
        }
    }

    class Client
    {
        public function __construct()
        {
            $image1 = new RealSubject('image1');
            $image2 = new RealSubject('image2');

            $image1->displayImage();
            $image1->displayImage();
            $image1->displayImage();
            $image2->displayImage();

            echo "\n\n Same thing, using proxy";
            $image1 = new ProxySubject('image1');
            $image2 = new ProxySubject('image2');

            $image1->displayImage();
            $image1->displayImage();
            $image1->displayImage();
            $image2->displayImage();
        }
    }

    $c = new Client();

}