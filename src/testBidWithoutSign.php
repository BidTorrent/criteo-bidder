<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    include('decoders/decoder.class.php');
    include('userResolvers/userResolver.class.php');
    include('bidders/testBidder.class.php');
    include('bidders/wrapperBidder.class.php');

    $decoder = new Decoder('');
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