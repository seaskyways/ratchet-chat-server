<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 10:57 AM
 */

namespace MyApp;


use Ratchet\ConnectionInterface;
use Symfony\Component\Debug\Exception\UndefinedFunctionException;

class SendMessageCommand
{
    use ChatCommand;

    private $msgLog = array();

    function getCommandTag(): string
    {
        return "send_message";
    }

    function execute(...$data)
    {
        $message = $data[0];
        $this->msgLog[] = $message;

        $from = $data[1];
        $clients = $data[2];
        $msg = command("message",["message" => $message]);

        foreach ($clients as $client) {
//            if ($from !== $client) {
                $client->send($msg);
//            }
        }
    }

    function resendMessages(ConnectionInterface $conn)
    {
        foreach ($this->msgLog as $msg) {
            $conn->send(json_encode([
                "command" => "message",
                "data" => [
                    "message" => $msg
                ]
            ]));
        }
    }

    function doIfMatches(string $commandTag, array $data)
    {
        throw new UndefinedFunctionException();
    }
}