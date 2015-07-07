<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    include('decoders/decoderHelper.class.php');
    include('decoders/criteoTestDecoder.class.php');
    include('userResolvers/userResolver.class.php');
    include('bidders/criteoBidder.class.php');
    include('bidders/wrapperBidder.class.php');

    $helper = new DecoderHelper('keys/key-1-private.pem');
    $decoder = new CriteoTestDecoder($helper);
    $userResolver = new UserResolver();
    $innerBidder = new CriteoBidder('http://rtb-validation.fr.eu.criteo.com/delivery/auction/request?profile=55&debug=1');

    $bidder = new WrapperBidder(
        $userResolver,
        $decoder,
        $innerBidder
    );

    $request = json_decode(file_get_contents("php://input"), true);
    $response = $bidder->GetResponse($request);
    echo json_encode($response);
?>