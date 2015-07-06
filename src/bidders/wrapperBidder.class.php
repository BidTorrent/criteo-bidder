<?php

class WrapperBidder{

    var $userResolver;
    var $decoder;
    var $bidder;
    
    function __construct ($userResolver, $decoder, $bidder) {
        $this->userResolver = $userResolver;
        $this->decoder = $decoder;
        $this->bidder = $bidder;
    }
    
    function GetResponse($request) {
        $userId = $this->userResolver->getUserId($_COOKIE);
        header("X-CriteoBidder-UserId: $userId");
        
        if (!$this->decoder->tryDecode($request, $userId, $bidRequest, $errorMessage)) {
            $this->ReturnNoBid($errorMessage);
        }
    
        header("X-CriteoBidder-Request: ".json_encode($bidRequest));
    
        $rawResponse = $this->bidder->GetResponse($bidRequest);
    
        header("X-CriteoBidder-Response: ".json_encode($rawResponse));
    
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