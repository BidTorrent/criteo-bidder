<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    include('decoders/decoder.class.php');
    include('userResolvers/userResolver.class.php');
    include('bidders/criteoBidder.class.php');
    include('bidders/wrapperBidder.class.php');

    $decoder = new CriteoBidTorrentDecoder('keys/key-1-private.pem');
    $userResolver = new UserResolver();
    $innerBidder = new CriteoBidder('http://rtb-validation.fr.eu.criteo.com/delivery/auction/request?profile=61&debug=1');

    $bidder = new WrapperBidder(
        $userResolver,
        $decoder,
        $innerBidder
    );

    $request = json_decode(file_get_contents("php://input"), true);
    $response = $bidder->GetResponse($request);

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: application/json');

    echo json_encode($response);
?>