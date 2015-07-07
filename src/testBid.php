<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    include('decoders/decoderHelper.class.php');
    include('decoders/criteoBidTorrentDecoder.class.php');
    include('userResolvers/userResolver.class.php');
    include('bidders/testBidder.class.php');
    include('bidders/wrapperBidder.class.php');

    $helper = new DecoderHelper('keys/key-2-private.pem');
    $decoder = new CriteoBidTorrentDecoder($helper);
    $userResolver = new UserResolver();
    $innerBidder = new TestBidder();

    $bidder = new WrapperBidder(
        $userResolver,
        $decoder,
        $innerBidder
    );

    $request = json_decode(file_get_contents("php://input"), true);
    $response = $bidder->GetResponse($request);
    echo json_encode($response);

?>