<?php

namespace FlyTicket\Command;

use FlyTicket\Manager\FormManager;
use FlyTicket\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use FlyTicket\Manager\MessageManager;

class FlyTicketCommand extends Command
{

    public function __construct() {
        $config = Main:: getInstance()->getConfig();
        /** @param string $command_name The command name */
        $command_name = $config->get("command");
        /** @param string $command_description The command description */
        $command_description = $config->get("command-description");
        parent::__construct($command_name, $command_description, "/".$command_name);
        $this->setPermission("flyticket.command.use");
    }

    /**
     * @param CommandSender $player The command sender
     * @param string $commandLabel The command label
     * @param array $args The command arguments
     */
    public function execute(CommandSender $player, string $commandLabel, array $args) {
        if ($player instanceof Player) {
            /** @var Player $player The player who executed the command */
            $formM = new FormManager();
            $formM->FlyTicketForm($player);
        } else {
            $player->sendMessage(MessageManager::NoConsole());
        }
    }
}
