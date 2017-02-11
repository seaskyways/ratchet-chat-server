<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 10:38 AM
 */

namespace MyApp;

function command(string $command ,array $data = []) : string {
    return json_encode([
        "command" => $command,
        "data" => $data
    ]);
}

interface ChatCommand
{
    function matches(string $commandTag) : bool ;

    function execute(...$data);

    function doIfMatches(string $commandTag, array $data);
}