<?php
class Decoder
{
	const VERSION = 1;

    var $bidfloor;
    var $btid;

    var $privateKeyFile;

    function __construct($privateKeyFile) {
        $this->privateKeyFile = $privateKeyFile;
    }

    function tryDecode($request, $userId, $userIp, &$decodedRequest, &$errorMessage) {
        if ($request == null)
        {
            $errorMessage = 'Not able to read the json';
            return false;
        }

        $this->Set($request, array('user', 'buyeruid'), $userId);
        $this->Set($request, array('device', 'ip'), $userIp);
        $this->bidfloor = $this->Get($request, array('imp', 0, 'bidfloor'));
        $this->btid = $this->Get($request, array('site', 'publisher', 'id'));
        if ($this->btid == null)
            $this->btid = $this->Get($request, array('app', 'publisher', 'id'));

        $decodedRequest = $request;
        return true;
    }

    function tryEncode($response, $userIp, &$encodedResponse, &$errorMessage) {
        if ($response == null) {
            $errorMessage = "No response from CRITEO";
            return false;
        }

        if (!isset($response['seatbid']) || count($response['seatbid']) == 0) {
            $errorMessage = "Criteo answered with no bid";
            return false;
        }

        $auctionId = $this->Get($response, array('id'));
        $impId = $this->Get($response, array('seatbid', 0, 'bid', 0, 'impid'));
		$now = time();
        $price = $this->Get($response, array('seatbid', 0, 'bid', 0, 'price'));
		$signature = $this->Sign($price, $auctionId, $impId, $this->btid, $this->bidfloor, $now, $userIp);

        $this->Set($response, array('seatbid', 0, 'bid', 0, 'ext', 'address'), $userIp);
        $this->Set($response, array('seatbid', 0, 'bid', 0, 'ext', 'signature'), $signature);
		$this->set($response, array('seatbid', 0, 'bid', 0, 'ext', 'timestamp'), $now);
		$this->Set($response, array('seatbid', 0, 'bid', 0, 'ext', 'version'), self::VERSION);

        $encodedResponse = $response;
        return true;
    }
    
    private function Sign($price, $auctionId, $impId, $publisherId, $bidfloor, $now, $userIp) {
        if ($this->privateKeyFile == '' || !file_exists($this->privateKeyFile))
            return '';

		$data = implode('|', array(self::VERSION, number_format($price, 6, '.', ''), $now, $userIp, $auctionId, $impId, $publisherId, number_format($bidfloor, 6, '.', '')));
        $key = file_get_contents($this->privateKeyFile);

        $array = preg_split ('/$\R?^/m', $key);
        if (count($array)<2)
            $keyLastDigits = "error";
        else
            $keyLastDigits = substr($array[count($array)-2], -6);

        header("X-CriteoBidder-Signature: data=$data;key(lastdigits)=$keyLastDigits");

        openssl_sign($data, $result, $key);
        $signature = base64_encode($result);
        return $signature;
    }

    private function Set(&$obj, $keys, $value) {
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