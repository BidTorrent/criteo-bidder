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
        $userIp = isset ($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

        header("X-CriteoBidder-UserId: $userId");
        header("X-CriteoBidder-UserIp: $userIp");

        if (!$this->decoder->tryDecode($request, $userId, $userIp, $bidRequest, $errorMessage)) {
            $this->ReturnNoBid($errorMessage);
        }

        $rawResponse = $this->bidder->GetResponse($bidRequest);

        if (!$this->decoder->tryEncode($rawResponse, $userIp, $response, $errorMessage)) {
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