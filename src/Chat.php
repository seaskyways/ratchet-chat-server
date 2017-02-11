<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 01/02 - Feb/17
 * Time: 3:19 PM
 */
namespace MyApp;


use Ratchet\{
    ConnectionInterface, MessageComponentInterface
};
use SplObjectStorage;


require dirname(__DIR__) . '/vendor/autoload.php';

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $idCommander, $sendMessageCommander, $saveNameCommander;
    protected $nameMap = array();
    protected $wholeChat = array();

    function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->idCommander = new IdGeneratorCommand;
        $this->sendMessageCommander = new SendMessageCommand;
        $this->saveNameCommander = new NameCommand;
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New Connection ! ({$conn->resourceId})" . PHP_EOL;
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Just removed {$conn->resourceId}" . PHP_EOL;
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $asJson = json_decode($msg, true);
        } catch (\Exception $exception) {
            echo $exception->getTraceAsString();
            return;
        }

        if (!array_key_exists("command", $asJson)) {
            return;
        }

        $commandTag = $asJson["command"];

        $data = 0;
        if (array_key_exists("data", $asJson)) {
            $data = $asJson["data"];
        }

        if ($this->idCommander->matches($commandTag)) {

            $this->idCommander->execute($from, $data);
            $this->sendMessageCommander->resendMessages($from);

            return;
        } elseif ($this->sendMessageCommander->matches($commandTag)) {

            $this->sendMessageCommander->execute($data, $from, $this->clients);
            array_push($this->wholeChat, $data);
            return;

        } elseif ($this->saveNameCommander->matches($commandTag)) {
            $this->saveNameCommander->execute($data["id"], $data["name"]);
        }
    }
}

