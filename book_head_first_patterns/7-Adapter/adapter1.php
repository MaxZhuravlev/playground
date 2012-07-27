<?php
/**
 * Created by Max Zhuravlev
 * Date: 7/25/12
 * Time: 10:54 AM
 */


class AdapterClient
{
    public function __construct()
    {
        /** @var $target AdapterTargetInterface */
        $target = new AdapterAdapter();
        $target->request();
    }
}

interface AdapterTargetInterface
{
    public function request();
}

class AdapterAdapter implements AdapterTargetInterface
{
    public function request()
    {
        $adaptee = new AdapterAdaptee();
        $adaptee->specificRequest(1);
    }
}

class AdapterAdaptee
{
    public function specificRequest($param)
    {
        echo $param;
    }
}


$client = new AdapterClient();