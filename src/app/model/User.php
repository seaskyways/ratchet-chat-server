<?php

namespace MyApp\Model;

use Model;

/**
 * @property string id
 * @property string name
 * @property string|null token
 */
class User extends Model
{
    public static $_table_use_short_name = true;
    public static $_table = "user";

    public static $column_name = "name";
    public static $column_token = "token";

    public function sentMessages(){
        return Model::factory("Message")
            ->where_equal('sender_id',$this->id)
            ->find_many();
    }

    public function receivedMessages(){
        /** @noinspection SpellCheckingInspection */
        return Model::factory("Message")
            ->where_equal('reciever_id',$this->id)
            ->find_many();
    }

    public static function createUser() : \ORMWrapper{
        return Model::factory("User")->create();
    }

}