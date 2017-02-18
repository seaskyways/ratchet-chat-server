<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 10:57 AM
 */

namespace MyApp\Command;


use MyApp\Chat\Chat;
use MyApp\Data\DataMessage;
use MyApp\Model\Message;
use Ratchet\ConnectionInterface;
use Rx\Subject\BehaviorSubject;


class SendMessageCommand
{
    use ChatCommand;

    private $messageSubject;

    function __construct()
    {
        $this->messageSubject = new BehaviorSubject();
    }


    function getCommandTag(): string
    {
        return "send_message";
    }

    function execute(...$data)
    {
        $message = $data[0];
        $from = $data[1];
        $clients = $data[2];

        $senderId = IdGeneratorCommand::getIdOfConnection($from);

        $msg = command("message", [
            "message" => $message,
            "sender_name" => IdGeneratorCommand::getPersonName(Chat::$clients[$from]["id"])
        ]);

        foreach ($clients as $client) {
            $receiverId = IdGeneratorCommand::getIdOfConnection($client);
            $this->messageSubject->onNext(new DataMessage($message, $senderId, $receiverId));
        }
    }

    function resendMessages(ConnectionInterface $conn)
    {
        foreach ($this->msgLog as $msg) {
            $conn->send($msg);
        }
    }

    function subscribeNewMessages(){
        $this->messageSubject->subscribeCallback(function ($message){

        });
    }

}