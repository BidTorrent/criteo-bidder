<?php

class CriteoBidTorrentDecoder
{
    var $bidfloor;
    var $helper;
    var $btid;
    
    function __construct($helper) {
        $this->helper = $helper;
    }
    
    function tryDecode($request, $userId, &$decodedRequest, &$errorMessage) {
        if ($request == null)
        {
            $errorMessage = 'Not able to read the json';
            return false;
        }
        
        $this->helper->Set($request, array('user', 'buyeruid'), $userId);
        $this->bidfloor = $this->helper->Get($request, array('imp', 0, 'bidfloor'));
        $this->btId = $this->helper->Get($response, array('site', 'publisher', 'id'));
        if ($this->btid == null)
            $this->btId = $this->helper->Get($response, array('app', 'publisher', 'id'));
        
        $decodedRequest = $request;
        return true;
    }

    function tryEncode($response, &$encodedResponse, &$errorMessage) {
        if ($response == null) {
            $errorMessage = "No response from CRITEO";
            return false;
        }

        if (!isset($response['seatbid']) || count($response['seatbid']) == 0) {
            $errorMessage = "Criteo answered with no bid";
            return false;
        }

        $price = $this->helper->Get($response, array('seatbid', 0, 'bid', 0, 'price'));
        $reqId = $this->helper->Get($response, array('id'));
        
        $this->helper->Set($response, array('seatbid', 0, 'bid', 0, 'signature'), $this->helper->Sign(
            $price, 
            $reqId, 
            $this->btId,
            $this->bidfloor
            ));
        
        $encodedResponse = $response;
        return true;
    }
}
?>