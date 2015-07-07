<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    include('decoders/decoder.class.php');
    include('userResolvers/userResolver.class.php');
    include('bidders/criteoBidder.class.php');
    include('bidders/wrapperBidder.class.php');

    $decoder = new Decoder('keys/key-1-private.pem');
    $userResolver = new UserResolver();
    $innerBidder = new CriteoBidder('http://rtb-validation.fr.eu.criteo.com/delivery/auction/request?profile=61&debug=1');

    $bidder = new WrapperBidder(
        $userResolver,
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