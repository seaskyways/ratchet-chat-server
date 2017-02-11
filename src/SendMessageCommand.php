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

        $from = $data[1];
        $clients = $data[2];
        $msg = command("message", [
            "message" => $message,
            "sender_name" => IdGeneratorCommand::getPersonName(Chat::$clients[$from]["id"])
        ]);
        $this->msgLog[] = $msg;

        foreach ($clients as $client) {
            /** @noinspection PhpUndefinedMethodInspection */
            $client->send($msg);
        }
    }

    function resendMessages(ConnectionInterface $conn)
    {
        foreach ($this->msgLog as $msg) {
            $conn->send($msg);
        }
    }

}