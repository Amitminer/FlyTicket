<?php

namespace FlyTicket\Manager;

use FlyTicket\Main;

/**
 * Class MessageManager
 * Provides methods to retrieve messages from the plugin's configuration.
 */
class MessageManager
{
    /**
     * MessageManager constructor.
     */
    public function __construct()
    {
        // NOOP
    }

    /**
     * Retrieves the purchase message from the configuration.
     *
     * @return string The purchase message.
     */
    public static function purchase(): string
    {
        return self::getConfigData("purchase-message");
    }

    /**
     * Retrieves the usage message from the configuration.
     *
     * @return string The usage message.
     */
    public static function usage(): string
    {
        return self::getConfigData("usage-message");
    }

    /**
     * Retrieves the fly time message from the configuration.
     *
     * @return string The fly time message.
     */
    public static function flytimemsg(): string
    {
        return self::getConfigData("fly-time-message");
    }

    /**
     * Retrieves the already active message from the configuration.
     *
     * @return string The already active message.
     */
    public static function alreadyActive(): string
    {
        return self::getConfigData("already-active");
    }

    /**
     * Retrieves the warning title from the configuration.
     *
     * @return string The warning title.
     */
    public static function warning(): string
    {
        return self::getConfigData("warning-title");
    }

    /**
     * Retrieves the inventory full message from the configuration.
     *
     * @return string The inventory full message.
     */
    public static function InvFull(): string
    {
        return self::getConfigData("inv-full");
    }

    /**
     * Retrieves the insufficient money message from the configuration.
     *
     * @return string The insufficient money message.
     */
    public static function NoMoney(): string
    {
        return self::getConfigData("no-enough-money");
    }

    /**
     * Retrieves the no console message from the configuration.
     *
     * @return string The no console message.
     */
    public static function NoConsole(): string
    {
        return self::getConfigData("no-console");
    }

    /**
     * Retrieves the on expire message from the configuration.
     *
     * @return string The on expire message.
     */
    public static function OnExpire(): string
    {
        return self::getConfigData("on-expire");
    }

    /**
     * Retrieves the fly usage message from the configuration.
     *
     * @return string The fly usage message.
     */
    public static function fly_usage(): string
    {
        return self::getConfigData("fly-usage");
    }

    /**
     * Retrieves the configuration data based on the key.
     *
     * @param string $configData The key to retrieve the configuration data.
     * @return string The configuration data.
     */
    private static function getConfigData(string $configData): string
    {
        $config = Main::getInstance()->getConfig()->get($configData);
        return (string) $config;
    }
}
