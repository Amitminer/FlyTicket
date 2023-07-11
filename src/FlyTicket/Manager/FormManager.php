<?php

namespace FlyTicket\Manager;

use FlyTicket\Main;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use OmniLibs\libs\davidglitch04\libEco\libEco;
use OmniLibs\libs\jojoe77777\FormAPI\ {
    CustomForm,
    SimpleForm
};
use pocketmine\item\{
    Item,
    StringToItemParser,
    LegacyStringToItemParser,
    LegacyStringToItemParserException
};
use FlyTicket\Manager\MessageManager;

class FormManager {

    /**
    * @param Player $player
    */
    public function FlyTicketForm(Player $player) {
    $form = new CustomForm(function(Player $player, $data) {
        if (isset($data[1])) {
            $minute = $data[1];
            $second = $minute * 60;
            $price = $minute * Main:: getInstance()->getConfig()->get("Price");
            $message = str_replace(["{total}", "{minute}"], [$price, $minute], MessageManager::purchase());
            $item_name = Main:: getInstance()->getConfig()->get("ItemName");
            $itemID = Main:: getInstance()->getConfig()->get("ItemID");
            $item = self::stringToItem($itemID);
            $item->setCustomName("§a" . $item_name)->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(0), 10));
            $item->getNamedTag()->setInt("second", $second);
            $item->getNamedTag()->setString("date", date("d-m-Y-H-i-s-"));
            
            $lore = ["§b" . $minute . " §cminute(s) fly ticket"];
            $item->setLore($lore);
            
            $priceToSend = $price;
            $minuteToSend = $minute;

            libEco::reduceMoney($player, $price, function(bool $success) use ($player, $priceToSend, $minuteToSend, $message, $item) : void {
                if($success){
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(MessageManager::InvFull());
                        return;
                    }
                    $player->sendMessage($message);
                    $player->getInventory()->addItem($item);
                    $this->getTime($minuteToSend);
                } else{
                    $player->sendMessage(MessageManager::NoMoney());
                }
            });
        } else {
            // Handle the case when the data is missing or incomplete
        }
    });
    $min = Main::getInstance()->getConfig()->get("Min");
    $max = Main::getInstance()->getConfig()->get("Max");
    $content = Main::getInstance()->getConfig()->get("content");
    $price = Main::getInstance()->getConfig()->get("Price");
    $label = $content.$price;
    $title = Main::getInstance()->getConfig()->get("FormTitle");
    $form->setTitle($title);
    $form->addLabel($label);
    $form->addSlider("§eSelect Minute",$min,$max,$min);
    $player->sendForm($form);
  }
  public static function getTime(int $time){
      return $time;
  }
  public static function stringToItem(string $input){
        $string = strtolower(str_replace([' ', 'minecraft:'], ['_', ''], trim($input)));
        try {
            $item = StringToItemParser::getInstance()->parse($string) ?? LegacyStringToItemParser::getInstance()->parse($string);
        } catch (LegacyStringToItemParserException $e) {
            return null;
        }
        return $item;
    }
}