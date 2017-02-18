<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 10:39 AM
 */

namespace MyApp\Command;

use MyApp\Chat\Chat;
use MyApp\Model\User;
use Ratchet\ConnectionInterface;

class IdGeneratorCommand
{
    use ChatCommand;

    public $doOnExecute;

    function __construct()
    {
        $this->doOnExecute = function () {
        };
    }

    private function getIdsTokens()
    {
        return User::select(User::$column_token)
            ->select("id")
            ->find_array();
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
            $newUser = User::create()
                ->set(User::$column_token, $token);

            $newUser->save();
            $id = $newUser->id();


            $result = command("connection_info", [
                "connection_id" => $id,
                "token" => $token,
                "should_invalidate" => true
            ]);

            $conn->send($result);

            echo "Sent new token : " . $token . PHP_EOL;

        } else {

            $userOfToken = User::where_equal(User::$column_token, $lastToken)
                ->find_one();


            if (empty($userOfToken)) {
                echo "Couldn't find id for token " . $lastToken . PHP_EOL;
                $this->execute($data[0], null);
            } else {
                $id = $userOfToken->id();

                echo "User with id: $id just rejoined !" . PHP_EOL;

                $data[0]->send(command("connection_info", [
                        "connection_id" => $id,
                        "token" => $lastToken,
                        "should_invalidate" => false,
                        "name" => $this->getPersonName($id)
                    ]
                ));
            }
        }

        $obj = Chat::$clients[$conn] = [];
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
        $name = User::select(User::$column_name)->find_one($id)->name;
        if (empty($name)) {
            return "$id";
        } else {
            return $name;
        }
    }

    static public function getIdOfConnection(ConnectionInterface $conn)
    {
        return Chat::$clients[$conn]["id"];
    }

    static public function getConnectionById(int $id){
        foreach (Chat::$clients as $client){
            if ($client["id"] == $id){
                return $client;
            }
        }
        return null;
    }
}