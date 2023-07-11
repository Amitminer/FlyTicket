<?php

namespace FlyTicket\Manager;

use FlyTicket\Main;

class MessageManager
{
    public function __construct() {
        // NOOP
    }

    public static function purchase(){
        $msg = self::getConfigData("purchase-message");
        return $msg;
    }
    public static function usage(){
        $msg = self::getConfigData("usage-message");
        return $msg;
    }
    public static function flytimemsg(){
        $msg = self::getConfigData("fly-time-message");
        return $msg;
    }
    public static function alreadyActive(){

        $msg = self::getConfigData("already-active");

        return $msg;
    }
    public static function warning(){

        $msg = self::getConfigData("warning-title");

        return $msg;
    }
    public static function InvFull(){

        $msg = self::getConfigData("inv-full");

        return $msg;
    }
    public static function NoMoney(){

        $msg = self::getConfigData("no-enough-money");

        return $msg;
    }
    public static function NoConsole(){
        $msg = self::getConfigData("no-console");
        return $msg;
    }
    public static function OnExpire(){
        $msg = self::getConfigData("on-expire");
        return $msg;
    }
    public static function fly_usage(){
        $msg = self::getConfigData("fly-usage");
        return $msg;
    }
    
    private static function getConfigData(string $configdata) {
        $config = Main::getInstance()->getConfig()->get($configdata);
        return $config;
     //   var_dump($config);
    }
}