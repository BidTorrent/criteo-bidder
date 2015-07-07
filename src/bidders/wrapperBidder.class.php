<?php

class WrapperBidder{

    var $decoder;
    var $bidder;

    function __construct ($decoder, $bidder) {
        $this->decoder = $decoder;
        $this->bidder = $bidder;
    }

    function GetResponse($request) {

        $userId = isset($cookies['uid']) ? $cookies['uid'] : '';

        header("X-CriteoBidder-UserId: $userId");

        if (!$this->decoder->tryDecode($request, $userId, $bidRequest, $errorMessage)) {
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