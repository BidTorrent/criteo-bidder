<?php

class CriteoBidder {

    var $endPoint;

    function __construct($endPoint) {
        $this->endPoint = $endPoint;
    }

    function GetResponse($request) {

        $encoded_request = json_encode($request);

        header("X-CriteoBidder-Request: ".$encoded_request);

        $options = array(
          'http' => array(
            'method'  => 'POST',
            'content' => $encoded_request,
            'header'=>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
            )
        );

        $context  = stream_context_create($options);

        $encoded_response = file_get_contents($this->endPoint, false, $context);

        header("X-CriteoBidder-Response: ".$encoded_response);

        return json_decode($encoded_response, true);
    }
}

?>