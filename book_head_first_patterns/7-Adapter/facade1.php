<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/27/12
 * Time: 6:25 PM
 *
 * based on en wikipedia example
 */


/* Complex parts */
class CPU
{
    public function freeze()
    {
        echo "\n freeze";
    }

    public function jump($position)
    {
        echo "\n jump to $position";
    }

    public function execute()
    {
        echo "\n execute";
    }
}

class Memory
{
    public function load($position, $data)
    {
        echo "\n load $position";
        print_r($data);
    }
}

class HardDrive
{
    public function read($lba, $size)
    {
        echo "\n lba $lba size $size";
    }
}

/* Facade */

class Computer
{
    protected $cpu;
    protected $memory;
    protected $hardDrive;

    public function __construct()
    {
        $this->cpu = new CPU();
        $this->memory = new Memory();
        $this->hardDrive = new HardDrive();
    }

    public function startComputer()
    {
        $this->cpu->freeze();
        $this->memory->load("BOOT_ADDRESS", $this->hardDrive->read("BOOT_SECTOR", "SECTOR_SIZE"));
        $this->cpu->jump("BOOT_ADDRESS");
        $this->cpu->execute();
    }
}

/* Client */

class Client
{
    public static function main()
    {
        $facade = new Computer();
        $facade->startComputer();
    }
}

Client::main();