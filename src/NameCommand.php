<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 12:41 PM
 */

namespace MyApp;


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

        $GLOBALS["nameMap"][$id] = $name;

        echo "Names are " . json_encode($GLOBALS["nameMap"]) . PHP_EOL;
    }

    function doIfMatches(string $commandTag, array $data)
    {
        // TODO: Implement doIfMatches() method.
    }
}