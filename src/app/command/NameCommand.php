<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 12:41 PM
 */

namespace MyApp\Command;


use MyApp\Chat\Chat;

class NameCommand
{
    use ChatCommand;

    function __construct()
    {
        $GLOBALS["nameMap"] = array();
    }

    function getCommandTag(): string
    {
        return "save_name";
    }

    function execute(...$data)
    {
        $id = $data[0];
        $name = $data[1];
        $oldName = $GLOBALS["nameMap"][$id] ?? $id;

        if (strtolower($name) == strtolower($oldName)) return;

        $GLOBALS["nameMap"][$id] = $name;

        foreach (Chat::$clients as $c) {
            $c->send(command("message", [
                "message" => "$oldName changed name to $name",
                "sender_name" => "server"
            ]));
        }
    }
}