<?php

class CriteoBidder {

    var $endPoint;
    
    function __construct($endPoint) {
        $this->endPoint = $endPoint;
    }
    
    function GetResponse($request) {
        $options = array(
          'http' => array(
            'method'  => 'POST',
            'content' => json_encode($request),
            'header'=>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
            )
        );

        $context  = stream_context_create($options);
        return json_decode(file_get_contents($this->endPoint, false, $context), true);
    }
}

?>