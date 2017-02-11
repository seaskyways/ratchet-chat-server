<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 10:39 AM
 */

namespace MyApp;

class IdGeneratorCommand
{
    use ChatCommand;

    public $tokenMap;
    private $lastId = 1;
    public $doOnExecute;

    function __construct()
    {
        $this->tokenMap = array();
        $this->doOnExecute = function () {
        };
    }


    function getCommandTag(): string
    {
        return "get_id";
    }

    function execute(...$data)
    {

        $conn = $data[0];
        $lastToken = $data[1];
        $id = null;

        if (empty($lastToken)) {

            $token = $this->generateToken();
            $this->tokenMap[$token] = $this->lastId;

            $result = json_encode([
                "command" => "connection_info",
                "data" => [
                    "connection_id" => $this->lastId,
                    "token" => $token,
                    "should_invalidate" => true
                ]
            ]);

            $conn->send($result, null);

            echo "Sent new token : " . $token . PHP_EOL;
            $id = $this->lastId;
            $this->lastId++;
        } else {

            foreach ($this->tokenMap as $t => $_) {
                if ($lastToken == $t) {
                    $id = $this->tokenMap[$lastToken];
                    break;
                }
            }
            if (empty($id)) {
                echo "Couldn't find id for token " . $lastToken . PHP_EOL;
                $this->execute($data[0], null);
                return;
            } else {
                echo "User with id: $id just rejoined !";

                $data[0]->send(json_encode([
                    "command" => "connection_info",
                    "data" => [
                        "connection_id" => $id,
                        "token" => $lastToken,
                        "should_invalidate" => false,
                        "name" => $this->getPersonName($id)
                    ]
                ]));
            }
        }

        $obj = Chat::$clients[$conn];
        $obj["id"] = $id;
        Chat::$clients[$conn] = $obj;
    }

    private function generateToken()
    {
        $token = "";

        while (strlen($token) < 26) {
            $rand = random_int(33, 126);
            $char = chr($rand);
            $token .= $char;
        }

        return $token;
    }

    static public function getPersonName($id): string
    {
        if (array_key_exists($id, $GLOBALS["nameMap"])) {
            return $GLOBALS["nameMap"][$id];
        } else {
            return "";
        }
    }
}