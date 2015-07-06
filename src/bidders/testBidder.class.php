<?php

class TestBidder {

    function __construct() {
    }
    
    function GetResponse($request) {
        
        $creative = isset($request['site']) ?
                "<iframe id='ac63b35f' name='ac63b35f' src='http://cas.fr.eu.criteo.com/delivery/r/afr.php?did=55967552f3fd1983d2fd1e67f3d23860&z=\${AUCTION_PRICE}&u=%7CtobaNQh5osYtBHWMWmrNqgyc2ye3d5I5wlNsJI%2FBr0Y%3D%7C&c1=MriEWuZJTjDISPFA0eU2oCb2hD0ICgkYds8alBDiggnQdpchtSVtCcD5LZRB_hY9wjGLbbis4L5J5Yw313MZME_K2h3JSki-RuDYVNJRLVv3RAv3Ih0EjbjBlZu7jwn9XvtjDIbpkIUVilFdCL239W3wDX9Ve65O2Qp2hWO5KH6BE9_dZos53nF6LlQlVabr7lK56c9fA-wqNwASvaGLktu32V7d4vaNdqdD1tHdP70&ct0=\${CLICK_URL}' framespacing='0' frameborder='no' scrolling='no' width='300' height='250'></iframe>"
              :  "<script type='text/javascript' src='http://cas.fr.eu.criteo.com/delivery/r/ajs.php?did=559401bb8f9c6c9bb27e9d9d863e6df0&z=\${AUCTION_PRICE}&u=%7CI7o9XoAMMjl3HUcPVb3V2ZrBz3DszK8XlyOeSO5CqTc%3D%7C&c1=4z_1vBnVXyU3s5S1ODdcxBEhMh_1PE6dCt88-QtmDLG9BBdwzMTh2CtSDEVErFQDw4w7x8OFfnXnkfpEV-cFqznHSWR1Th9Q31_9KWeD80wN3wI2CEdDgi7v4Ymyl6qk367XHc2xzTvWq2fT0Fh_a3M0w1pLIwZfkpBcYSIkcj9D2K1RK8M4LPj0EAF8FbWARXwcOLtMQ4vEzV1rvzU6aEmZ_iSXY5uz&ct0=\${CLICK_URL}'></script>";
    
        $response = array(
            "id" => $request['id'],
            "cur" => "USD",
            "seatbid" => array(
                array(
                    "bid" => array(
                        array(
                            "id" => "559401bb8f9c6c9bb27e9d9d863e6df0",
                            "impid" => $request['imp'][0]['id'],
                            "price" => 0.032,
                            "nurl" => "",
                            "adomain" => "miniinthebox.com",
                            "creative" => $creative
                        )
                    )
                )
            )
        );
        
        return $response;
    }
}

?>