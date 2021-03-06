<?php

class TestBidder {

    function __construct() {
    }

    function GetResponse($request) {
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parse = parse_url($url);
        $scheme = isset($parse['scheme']) ? $parse['scheme'] . '://' : '//';
        $urlPrefix = $scheme.$parse['host'].substr($parse['path'], 0, strrpos($parse['path'], '/') + 1);

		$response = array
		(
			'cur' => 'USD',
			'id' => $request['id'],
			'seatbid' => array
			(
				array
				(
					'bid' => array
					(
						array
						(
							'id' => '559bde378d2fc1f55b9d68f718be4410',
							'impid' => $request['imp'][0]['id'],
							'price' => 0.02,
							'adid' => '22',
							'crid' => '2300751',
							'iurl' => 'http://static.criteo.net/images/_logo_privacy/laredoute_fr_logo.jpg',
							'adm' => '',
							'adomain' => array ('laredoute.fr')
						)
					)
				)
			)
		);

        $type = isset($_GET['type']) ? $_GET['type'] : '';

        if ($type == 'jsdirect')
			$adm = '<script type=\'text/javascript\' src=\'\'>alert(\'This is a test !\');</script>';
        elseif ($type == 'jsindirect')
			$adm = '<script type=\'text/javascript\' src=\'' . $urlPrefix . 'script.js' . '\'></script>';
        elseif ($type == 'jscriteo')
			$adm = '<script type=\'text/javascript\' src=\'http://cas.fr.eu.criteo.com/delivery/r/ajs.php?did=559bde378d2fc1f55b9d68f718be4410&z=${AUCTION_PRICE}&u=%7CKxl30IlBT9AoAln2B5ccT6RqtJaBgL3uEN35d2MS12U%3D%7C&c1=jP7idqKkb0nqtqZqftPNuloALPoDC0n5wswCp8kjRpkUdhIV0EcUP_BZ3s8W4buw3pzN2TtWuDcp4_qbH0kWeNrBLCf4VZl22Pw_qU9rdvVC2--xG-j90nq8QMTjJlBxxaaoogHxbbvSMeiYHtCum98Wm7V0Niy6hrJTwJjvwbi85TUFSUIuHyJGfqemSQt8FjRjep1ToB_k0c0Fc8Q2vkNw4lX_8VkGMnciBazrsXY&ct0=${CLICK_URL}\'></script>';
        else if (isset ($request['site']))
			$adm = '<iframe id=\'ac63b35f\' name=\'ac63b35f\' src=\'http://cas.fr.eu.criteo.com/delivery/r/afr.php?did=55967552f3fd1983d2fd1e67f3d23860&z=${AUCTION_PRICE}&u=%7CtobaNQh5osYtBHWMWmrNqgyc2ye3d5I5wlNsJI%2FBr0Y%3D%7C&c1=MriEWuZJTjDISPFA0eU2oCb2hD0ICgkYds8alBDiggnQdpchtSVtCcD5LZRB_hY9wjGLbbis4L5J5Yw313MZME_K2h3JSki-RuDYVNJRLVv3RAv3Ih0EjbjBlZu7jwn9XvtjDIbpkIUVilFdCL239W3wDX9Ve65O2Qp2hWO5KH6BE9_dZos53nF6LlQlVabr7lK56c9fA-wqNwASvaGLktu32V7d4vaNdqdD1tHdP70&ct0=${CLICK_URL}\' framespacing=\'0\' frameborder=\'no\' scrolling=\'no\' width=\'300\' height=\'250\'></iframe>';
		else
			$adm = "<script type=\'text/javascript\' src=\'http://cas.fr.eu.criteo.com/delivery/r/ajs.php?did=559bde378d2fc1f55b9d68f718be4410&z=${AUCTION_PRICE}&u=%7CKxl30IlBT9AoAln2B5ccT6RqtJaBgL3uEN35d2MS12U%3D%7C&c1=jP7idqKkb0nqtqZqftPNuloALPoDC0n5wswCp8kjRpkUdhIV0EcUP_BZ3s8W4buw3pzN2TtWuDcp4_qbH0kWeNrBLCf4VZl22Pw_qU9rdvVC2--xG-j90nq8QMTjJlBxxaaoogHxbbvSMeiYHtCum98Wm7V0Niy6hrJTwJjvwbi85TUFSUIuHyJGfqemSQt8FjRjep1ToB_k0c0Fc8Q2vkNw4lX_8VkGMnciBazrsXY&ct0=${CLICK_URL}\'></script>";

		$response['seatbid'][0]['bid'][0]['adm'] = $adm;
		$response['seatbid'][0]['bid'][0]['impid'] = isset ($request['imp'][0]['id']) ? $request['imp'][0]['id'] : '1';

        return $response;
    }
}

?>