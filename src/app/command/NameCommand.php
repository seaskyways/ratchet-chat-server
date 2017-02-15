<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 12:41 PM
 */

namespace MyApp\Command;


use MyApp\Chat\Chat;
use MyApp\Model\User;
use Rx\Subject\Subject;

class NameCommand
{
    use ChatCommand;

    public static $nameChangedSubject;

    function __construct()
    {
        NameCommand::$nameChangedSubject = Subject::create(function (){

        });

        $GLOBALS["nameMap"] = $nameMap = array();

        $users = User::find_array();
        foreach ($users as $user) {
            $nameMap[$user["id"]] = $user["name"];
        }
    }

    function getCommandTag(): string
    {
        return "save_name";
    }

    function execute(...$data)
    {
        $nameMap = $GLOBALS["nameMap"];

        $id = $data[0];
        $name = $data[1];
        $oldName = $nameMap[$id] ?? $id;

        if (strtolower($name) == strtolower($oldName)) return;

        $GLOBALS["nameMap"][$id] = $name;

        User::find_one($id)
            ->set("name", $name)
            ->save();

        foreach (Chat::$clients as $c) {
            $c->send(command("message", [
                "message" => "$oldName changed name to $name",
                "sender_name" => "server"
            ]));
        }
    }
}