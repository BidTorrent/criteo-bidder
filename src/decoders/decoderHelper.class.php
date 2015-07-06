<?php

class DecoderHelper {

    var $privateKeyFile;

    function __construct($privateKeyFile) {
        $this->privateKeyFile = $privateKeyFile;
    }

    function Set(&$obj, $keys, $value) {
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

    function Sign($price, $requestId, $publisherId, $bidfloor) {
        $key = file_get_contents($this->privateKeyFile);
        $data = number_format($price, 6).
                $requestId.
                $publisherId.
                number_format($bidfloor, 6);
        openssl_sign($data, $result, $key);
        return base64_encode($result);
    }

    function Get($obj, $keys) {
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