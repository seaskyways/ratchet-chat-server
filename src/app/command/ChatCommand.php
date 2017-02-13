<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 10:38 AM
 */

namespace MyApp\Command;

function command(string $command, $data = []): string
{
    return json_encode([
        "command" => $command,
        "data" => $data
    ]);
}

trait ChatCommand
{
    abstract function getCommandTag(): string;

    abstract function execute(...$data);

    function matches(string $commandTag): bool{
        return $commandTag === $this->getCommandTag();
    }

    function doIfMatches(string $commandTag,...$data) : bool
    {
        $match = $this->matches($commandTag);
        if ($match){
            $this->execute(...$data);
        }
        return $match;
    }
}