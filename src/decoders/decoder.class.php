<?php
class CriteoBidTorrentDecoder
{
    var $bidfloor;
    var $btid;

    var $privateKeyFile;

    function __construct($privateKeyFile) {
        $this->privateKeyFile = $privateKeyFile;
    }

    function tryDecode($request, $userId, &$decodedRequest, &$errorMessage) {
        if ($request == null)
        {
            $errorMessage = 'Not able to read the json';
            return false;
        }

        $this->Set($request, array('user', 'buyeruid'), $userId);
        $this->bidfloor = $this->Get($request, array('imp', 0, 'bidfloor'));
        $this->btId = $this->Get($request, array('site', 'publisher', 'id'));
        if ($this->btid == null)
            $this->btId = $this->Get($request, array('app', 'publisher', 'id'));

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

        $price = $this->Get($response, array('seatbid', 0, 'bid', 0, 'price'));
        $reqId = $this->Get($response, array('id'));

        $this->Set($response, array('seatbid', 0, 'bid', 0, 'signature'), $this->Sign(
            $price, 
            $reqId, 
            $this->btId,
            $this->bidfloor
            ));

        $encodedResponse = $response;
        return true;
    }
    
    private function Sign($price, $requestId, $publisherId, $bidfloor) {
        $key = file_get_contents($this->privateKeyFile);
        $data = number_format($price, 6, ".", "").
                $requestId.
                $publisherId.
                number_format($bidfloor, 6, ".", "");
        openssl_sign($data, $result, $key);
        return base64_encode($result);
    }

    private function Set(&$obj, $keys, $value) {
        if ($value == null)
            return;
        if (count($keys) == 1)
            $obj[$keys[0]] = $value;
        elseif (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($obj[$key]))
                $obj[$key] = array();
            $this->Set($obj[$key], $keys, $value);
        }
    }

    private function Get($obj, $keys) {
        if (!is_array($keys))
            return $this->Get($obj, array($keys));

        if (count($keys) == 0)
            return $obj;

        $currentKey = array_shift($keys);
        if (!isset($obj[$currentKey]))
            return null;

        return $this->Get($obj[$currentKey], $keys);
    }
}
?>