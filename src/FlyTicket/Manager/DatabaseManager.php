<?php

namespace FlyTicket\Manager;

use FlyTicket\Main as Flyticket;
use poggit\libasynql\libasynql;
use poggit\libasynql\DataConnector;
use Closure;
use poggit\libasynql\libs\SOFe\AwaitGenerator\Await;

class DatabaseManager
{
    private $database;

    public function __construct() {

        $plugin = Flyticket::getInstance();
        $this->database = libasynql::create($plugin, $plugin->getConfig()->get("database"), [
            "sqlite" => "database/sqlite.sql",
            "mysql" => "database/mysql.sql"
        ]);
    }

    /**
    * Get the database connection.
    *
    * @param bool $load Whether to load the database connection or close it.
    */
    public function getDatabase(bool $load = true): void
    {
        if ($load) {
            $this->loadDatabase(); // Load the database
        } else {
            $this->closeDatabase(); // Close the database connection
        }
    }

    /**
    * Load the database and initialize necessary tables.
    */
    private function loadDatabase() {
        $this->database->executeGeneric("flyticket.createTable");
    }

    /**
    * Close the database connection.
    */
    private function closeDatabase() {
        if (isset($this->database)) {
            $this->database->close(); // Close the database connection
        }
    }

    /**
    * Add a player to the database.
    *
    * @param string $playerName The name of the player to add.
    */
    /**
    * Add a player to the database.
    *
    * @param string $playerName The name of the player to add.
    * @return bool True if the player was successfully added, false otherwise.
    */
    public function addPlayer(string $playerName): bool
    {
        $result = $this->database->executeInsert("flyticket.addPlayer", [
            "playerName" => $playerName
        ]);

        // Return true if the insert statement was successful, false otherwise
        return $result !== null;
    }

    /**
    * Get player data from the database.
    *
    * @param string $playerName The name of the player.
    * @return array|null The player data or null if not found.
    */
    public function playerExists(string $playerName) :\Generator {
        $this->database->executeSelect("flyticket.getPlayer", [
            "playerName" => $playerName
        ], yield, yield Await::REJECT);
        return yield Await::ONCE;
    }


    public function removePlayer(string $playerName): bool
    {
        $result = false;
        $this->database->executeChange("flyticket.removePlayer",
            [
                "playerName" => $playerName
            ],
            function(int $rowCount) use (&$result) {
                $result = $rowCount > 0;
            });

        return $result;
    }
}