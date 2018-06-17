<?php

declare(strict_types=1);

namespace MPouch;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{

	public function onEnable() : void{
		$this->getLogger()->info("MoneyPouch by EmeraldAssasinYT enabled");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param CommandSender $sender
	 * @param Command       $command
	 * @param string        $label
	 * @param array         $args
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(strtolower($command->getName()) === "infernalpouch"){
			if(count($args) < 2){
				$sender->sendMessage(TextFormat::GRAY . "[" . TextFormat::BLUE . "IN" . TextFormat::GRAY . "]" . TextFormat::GRAY . " /infernalpouch <player> <tier>");
				return false;
			}
			if(!$sender->hasPermission("infernalpouch.command.give")){
				$sender->sendMessage(TextFormat::GRAY . "[" . TextFormat::BLUE . "IN" . TextFormat::GRAY . "]" . TextFormat::GRAY . " You dont have permission");
				return false;
			}
			if($sender->hasPermission("infernal.command.give") || $sender->isOp()){
				$player = $sender->getServer()->getPlayer($args[0]);
				switch($args[1]){
					case "tier1":
						$t1 = Item::get(379, 101, 1);
						$t1->setCustomName(TextFormat::GREEN . "Infernal Pouch " . TextFormat::GRAY . "(Right Click)");
						$t1->setLore([
							"",
							TextFormat::GRAY . "Win " . TextFormat::GREEN . "$10,000 - $25,0000 ",
							TextFormat::GRAY . "Tier" . TextFormat::GREEN . "I",
							"",
							TextFormat::GREEN . "-" . TextFormat::GRAY . "-" . TextFormat::GREEN . "-",
							TextFormat::GREEN . "-" . TextFormat::GRAY . "-" . TextFormat::GREEN . "-",
							TextFormat::GREEN . "Infernal Network",
							""
						]);
						$player->sendMessage(TextFormat::GRAY . "[" . TextFormat::BLUE . "IN" . TextFormat::GRAY . "]" . TextFormat::GRAY . " You have received your money pouch!");
						$player->getInventory()->addItem($t1);
						break;
					case "tier2":
						$t2 = Item::get(379, 102, 1);
						$t2->setCustomName(TextFormat::GREEN . "Infernal Pouch " . TextFormat::GRAY . "(Right Click)");
						$t2->setLore([
							"",
							TextFormat::GRAY . "Win " . TextFormat::GREEN . "$25,000 - $50,000",
							TextFormat::GRAY . "Tier" . TextFormat::GREEN . "II",
							"",
							TextFormat::GREEN . "-" . TextFormat::GRAY . "-" . TextFormat::GREEN . "-",
							TextFormat::GREEN . "-" . TextFormat::GRAY . "-" . TextFormat::GREEN . "-",
							TextFormat::GREEN . "Infernal",
							""
						]);
						$player->sendMessage(TextFormat::GRAY . "[" . TextFormat::BLUE . "IN" . TextFormat::GRAY . "]" . TextFormat::GRAY . " You have received your money pouch!");
						$player->getInventory()->addItem($t2);
						break;
				}
			}
		}
		return true;
	}

	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onInteract(PlayerInteractEvent $event) : void{
		$player = $event->getPlayer();
		if($event->getItem()->getId() === 379){
			switch($event->getItem()->getDamage()){
				case 101:
					$tier1win = rand(10000, 25000);
					EconomyAPI::getInstance()->addMoney($player, $tier1win);
					$player->sendMessage(TextFormat::GRAY . "[" . TextFormat::BLUE . "IN" . TextFormat::GRAY . "]" . TextFormat::GRAY . TextFormat::GRAY . " You have won:" . TextFormat::BOLD . TextFormat::LIGHT_PURPLE . " $" . $tier1win);
					$player->getInventory()->removeItem(Item::get(379, 101, 1));
					return;
				case 102:
					$tier2win = rand(25000, 50000);
					EconomyAPI::getInstance()->addMoney($player, $tier2win);
					$player->sendMessage(TextFormat::GRAY . "[" . TextFormat::BLUE . "IN" . TextFormat::GRAY . "]" . TextFormat::GRAY . TextFormat::RESET . TextFormat::GRAY . " You have won:" . TextFormat::BOLD . TextFormat::LIGHT_PURPLE . " $" . $tier2win);
					$player->getInventory()->removeItem(Item::get(379, 102, 1));
					return;
			}
		}
	}

	/**
	 * @param BlockPlaceEvent $event
	 * @return void
	 */
	public function onPlace(BlockPlaceEvent $event) : void{
		if($event->getItem()->getId() === 379){
			if($event->getItem()->getDamage() === 101 && $event->getItem()->getDamage() === 102) $event->setCancelled();
		}
	}
}
