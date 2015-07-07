<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    include('decoders/decoder.class.php');
    include('bidders/testBidder.class.php');
    include('bidders/wrapperBidder.class.php');

    $decoder = new Decoder('keys/key-2-private.pem');
    $innerBidder = new TestBidder();

    $bidder = new WrapperBidder(
        $decoder,
        $innerBidder
    );

    $request = json_decode(file_get_contents("php://input"), true);
    $response = $bidder->GetResponse($request);
    
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo json_encode($response);
?>