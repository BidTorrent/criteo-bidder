<?php

class WrapperBidder{

    var $decoder;
    var $bidder;

    function __construct ($decoder, $bidder) {
        $this->decoder = $decoder;
        $this->bidder = $bidder;
    }

    function GetResponse($request) {

        $userId = isset($_COOKIE['uid']) ? $_COOKIE['uid'] : '';
        $userIp = $_SERVER['REMOTE_ADDR'];
        if ($userIp == '127.0.0.1')
            $userIp = '91.199.242.236';

        if (!$this->decoder->tryDecode($request, $userId, $userIp, $bidRequest, $errorMessage)) {
            $this->ReturnNoBid($errorMessage);
        }

        $rawResponse = $this->bidder->GetResponse($bidRequest);

        if (!$this->decoder->tryEncode($rawResponse, $response, $errorMessage)) {
            $this->ReturnNoBid($errorMessage);
        }

        return $response;
    }

    function ReturnNoBid($error) {
        header("X-CriteoBidder-Error: $error");
        header("HTTP/1.0 204 No Content");
        die();
    }
}

?>