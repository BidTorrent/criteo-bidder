<?php

//Deprecated Should Use the CriteoBidTorrentDecoder
class CriteoTestDecoder
{
    var $helper;
    var $currency;
    var $bidtorrentId;
    var $bidfloor;

    function __construct($helper) {
        $this->helper = $helper;
    }

    function tryDecode($stream, $userId, &$request, &$errorMessage) {
        $content = $stream;

        if ($content == null)
        {
            $errorMessage = 'Not able to read the json';
            return false;
        }

        $criteoRequest                                      = array();
        $criteoRequest['Analysis']                          = 1;
        $criteoRequest['PublisherID']                       = isset($content['site']) ? $content['site']['id'] : $content['app']['id'];
        $criteoRequest['Timeout']                           = 120;

        $criteoRequest['AppInfo']                           = array();
        $criteoRequest['AppInfo']['AppId']                  = $this->helper->Get($content, array('app', 'publisher', 'id'));
        $criteoRequest['AppInfo']['AppName']                = $this->helper->Get($content, array('app', 'publisher', 'name'));
        $criteoRequest['RequestID']                         = $content['id'];
        $criteoRequest['Device']                            = array();
        $criteoRequest['Device']['IdCategory']              = strtolower($this->helper->Get($content, array('device', 'os'))) == 'ios' ? 'IDFA' : 
                                                                strtolower($this->helper->Get($content, array('device', 'os'))) == 'android' ? 'ANDROID_ID' :
                                                                null;
        $criteoRequest['Device']['EnvironmentType']         = isset($content['site']) ? 0 : 1; // 0 => Web, 1 => In_app
        $criteoRequest['Device']['Id']                      = $this->helper->Get($content, array('device', 'id'));
        $criteoRequest['Device']['OperatingSystemType']     = strtolower($this->helper->Get($content, array('device', 'os'))) == 'ios' ? 1 :
                                                                strtolower($this->helper->Get($content, array('device', 'os'))) == 'android' ? 2 :
                                                                0;
        $criteoRequest['User']                              = array();
        $criteoRequest['User']['CriteoUser']                = array();
        $criteoRequest['User']['CriteoUser']['Id']          = $userId;
        $criteoRequest['User']['CriteoUser']['Version']     = 1;
        $criteoRequest['User']['IpAddress']                 = $this->helper->Get($content, array('device', 'ip'));
        $slot                                               = array();
        $slot['SlotId']                                     = 1;
        $slot['Intention']                                  = 0; //Accept
        $slot['RenderContainer']                            = isset($content['site']) ? 0 : 1; // 0 => IFrame, 1 => Javascript
        $slot['Sizes']                                      = array(array('Item1' => $content['imp'][0]['banner']['w'], 'Item2' => $content['imp'][0]['banner']['h']));
        $slot['MinCpm']                                     = $content['imp'][0]['bidfloor'];
        $criteoRequest['Slots']                             = array($slot);
        $criteoRequest['Currency']                          = "EUR"; //$content['cur'];
        $criteoRequest['ext']['btid']                       = $this->helper->Get($content, array('ext', 'btid'));

        $request = array('bidrequest' => $criteoRequest);

        $this->bidtorrentId = isset($content['site']['id']) ? $content['site']['publisher']['id'] : $content['app']['publisher']['id'] ;
        $this->currency = $content['cur'];
        $this->bidfloor = $content['imp'][0]['bidfloor'];
        return true;
    }

    function tryEncode($rawResponse, &$response, &$errorMessage) {
        $criteoResponse = $rawResponse;

        if ($criteoResponse == null) {
            $errorMessage = "No response from CRITEO";
            return false;
        }

        if (!isset($criteoResponse['seatbid']) || count($criteoResponse['seatbid']) == 0) {
            $errorMessage = "Criteo answered with no bid";
            return false;
        }

        $response = array();
        $response['id'] = $criteoResponse['id'];
        $response['cur'] = $this->currency;
        $seatbidObject = array(
            'id' => $criteoResponse['seatbid'][0]['bid'][0]['id'],
            'impid' => $criteoResponse['seatbid'][0]['bid'][0]['impid'],
            'price' => $criteoResponse['seatbid'][0]['bid'][0]['price'],
            'signature' => $this->helper->Sign($criteoResponse['seatbid'][0]['bid'][0]['price'], $criteoResponse['id'], $this->bidtorrentId, $this->bidfloor),
            'nurl' => '',
            'adomain' => $criteoResponse['seatbid'][0]['bid'][0]['adomain'][0],
            'creative' => $criteoResponse['seatbid'][0]['bid'][0]['creative']['adm']
        );
        $seatbid = array();
        $seatbid['bid'] = array($seatbidObject);
        $response['seatbid'] = array($seatbid);

        return true;
    }
}
?>