<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 17/02 - Feb/17
 * Time: 11:09 AM
 */

namespace MyApp\Data;


class DataMessage
{
    private $messageText, $senderId, $receiverId;

    /**
     * DataMessage constructor.
     * @param $messageText
     * @param $senderId
     * @param $receiverId
     */
    public function __construct($messageText, $senderId, $receiverId)
    {
        $this->messageText = $messageText;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
    }

    /**
     * @return mixed
     */
    public function getMessageText() : string
    {
        return $this->messageText;
    }

    /**
     * @return mixed
     */
    public function getSenderId() : int
    {
        return $this->senderId;
    }

    /**
     * @return mixed
     */
    public function getReceiverId() : int
    {
        return $this->receiverId;
    }


    function subscribeMessages(){

    }
}