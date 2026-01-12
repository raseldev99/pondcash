<?php

namespace App\Http\Controllers\Postback;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostbackController extends Postback
{
    public function __construct()
    {
        $this->log(request());
    }

    // "/postback/adgem?user_id={player_id}&campaign_id={campaign_id}&campaign_name={campaign_name}&amount={amount}&ip={ip}&payout={payout}"
    public function adgem(Request $request)
    {
        return $this->handlePostback($request, 'Adgem');
    }

    // "/postback/ayetstudios?user_id={external_identifier}&campaign_id={offer_id}&campaign_name={offer_name}&amount={currency_amount}&ip={ip}&payout={payout_usd}"
    public function ayetstudios(Request $request)
    {
        return $this->handlePostback($request, 'Ayet Studios', [
            '51.79.101.241',
            '158.69.185.134',
            '158.69.185.154',
            '35.165.166.40',
            '35.166.159.131',
            '52.40.3.140',
        ]);
    }

    // "/postback/lootably?user_id={userID}&campaign_id={offerID}&campaign_name={offerName}&amount={currencyReward}&ip={ip}&payout={revenue}"
    public function lootably(Request $request)
    {
        return $this->handlePostback($request, 'Lootably');
    }

    // "/postback/monlix?user_id={{userId}}&campaign_id={{campaignId}}&campaign_name={{taskName}}&amount={{rewardValue}}&ip={{userIp}}&payout={{payout}}&country_code={{countryCode}}&status={{status}}"
    public function monlix(Request $request)
    {
        return $this->handlePostback($request, 'Monlix');
    }

    // "/postback/adgatemedia?user_id={user_id}&campaign_id={campaign_id}&campaign_name={campaign_name}&amount={amount}&ip={ip}&payout={payout}"
    public function adgatemedia(Request $request)
    {
        return $this->handlePostback($request, 'Adgatemedia', ['52.42.57.125']);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/admantum?user_id={uid}&campaign_id={of_id}&campaign_name={of_name}&amount={virtual_currency}&ip={ip}&payout={payout}"
    public function admantum(Request $request)
    {
        return $this->handlePostback($request, 'Admantum');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/adscendmedia?user_id=[SB1]&campaign_id=[OID]&campaign_name=[ONM]&amount=[CUR]&ip=[IP]&payout=[PAY]&status=[STS]"
    public function adscendmedia(Request $request)
    {
        $data = $request->all();
        if (empty($data['amount'])) {
            $multiplier = (float)setting('offers.adscendmedia.payout_rate', 500);
            $data['amount'] = floatval($data['payout'] ?? 0) * $multiplier;
        }

        return $this->handlePostback($request, 'AdscendMedia', [
            '54.204.57.82',
            '3.235.151.36',
            '52.117.122.183',
            '52.117.127.192',
            '52.117.121.196'
        ], modifiedData: $data);
    }

    // "https://lootcasher.com/postback/ZJX44M5PLK/wannads?user_id={user_id}&campaign_id={offer_id}&campaign_name={offer_name}&amount={reward}&ip={ip}&payout={payout}"
    public function wannads(Request $request)
    {
        return $this->handlePostback($request, 'Wannads', ['3.22.177.178']);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/offertoro?user_id={user_id}&campaign_id={id}&campaign_name={o_name}&amount={amount}&ip={ip_address}&payout={payout}"
    public function offertoro(Request $request)
    {
        return $this->handlePostback($request, 'Offertoro');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/timewall?user_id={userID}&campaign_id={transactionID}&amount={currencyAmount}&ip={ip}&payout={revenue}"
    public function timewall(Request $request)
    {
        return $this->handlePostback($request, 'Timewall');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/pollfish?user_id=[[request_uuid]]&campaign_id=[[tx_id]]&amount=[[reward_value]]&payout=[[cpa]]"
    public function pollfish(Request $request)
    {
        return $this->handlePostback($request, 'Pollfish');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/ogads?user_id={aff_sub4}&campaign_id={offer_id}&campaign_name={offer_name}&ip={session_ip}&payout={payout}"
    public function ogads(Request $request)
    {
        $data = $request->all();
        $data['amount'] = $data['payout'] ? $data['payout'] * setting('offers.ogads.rate', 500) : 0;
        return $this->handlePostback($request, 'Ogads', modifiedData: $data);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/bitlabs?user_id=[%UID%]&campaign_id=[%OFFER_ID%]&campaign_name=[%OFFER_NAME%]&amount=[%VAL%]&payout=[%RAW%]&country_code=[%COUNTRY%]"
    public function bitlabs(Request $request)
    {
        return $this->handlePostback($request, 'Bitlabs');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/adbreakmedia?user_id=[YOUR_USER_ID]&campaign_id=[OFFER_ID]&campaign_name=[OFFER_NAME]&amount=[REWARD_VALUE]&ip=[USER_IP]&payout=[PAYOUT]&country_code=[COUNTRY]&status=[STATUS]&percent=500"
    public function adbreakmedia(Request $request)
    {
        $data = $request->all();
        if (isset($data['payout']) && !is_numeric($data['payout'])) {
            $percent = $data['percent'] ?? 500;
            $data['payout'] = $data['amount'] / $percent;
        }

        if (isset($data['status']) && $data['status'] == 'rejected') {
            $data['status'] = 2;
        }

        return $this->handlePostback($request, 'AdbreakMedia', ['139.144.178.218'], modifiedData: $data);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/cpxresearch?user_id={user_id}&campaign_id={offer_ID}&amount={amount_local}&ip={ip_click}&payout={amount_usd}"
    public function cpxresearch(Request $request)
    {
        return $this->handlePostback($request, 'CpxResearch');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/admantium?user_id={user_id}&campaign_id={offer_id}&campaign_name={offer_name}&amount={reward}&payout={payout}&ip={ip}"
    public function admantium(Request $request)
    {
        return $this->handlePostback($request, 'Admantium', ['3.22.177.178']);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/mylead?user_id=[player_id]&campaign_id=[destination_program_id]&campaign_name=[destination_program_name]&amount=[virtual_amount]&payout=[payout_decimal]&ip=[ip]&country_code=[country_code]&status=[status]"
    public function mylead(Request $request)
    {
        $data = $request->all();
        if (isset($data['status'])) {
            if ($data['status'] == 'rejected')
                $data['status'] = 2;
            elseif ($data['status'] == 'pre_approved' || $data['status'] == 'pending')
                $data['status'] = 3;
            else
                unset($data['status']);
        }

        return $this->handlePostback($request, 'Mylead', modifiedData: $data);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/revlum?user_id={subId}&campaign_id={offerId}&campaign_name={offerName}&amount={reward}&ip={userIp}&payout={payout}&country_code={country}"
    public function revlum(Request $request)
    {
        return $this->handlePostback($request, 'Revlum', ['209.159.156.198']);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/revu?user_id=$uid$&campaign_id=$campaign$&campaign_name=$name$&amount=$currency$&ip=$ip$&payout=$rate$&country_code=$country$"
    public function revu(Request $request)
    {
        return $this->handlePostback($request, 'RevU');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/mychips?user_id={user_id}&campaign_id={campaign_id}&campaign_name={campaign_name}&ip={session_ip}&payout={payout}&country_code={conversion_country}&amount={user_payout_in_vc}"
    public function mychips(Request $request)
    {
        $data = $request->all();
        return $this->handlePostback($request, 'MyChips', ['168.63.37.145', '13.70.194.104', '34.54.234.115', '34.64.93.62', '34.84.180.208', '4.207.193.125', '20.54.96.37', '34.146.139.91', '34.54.248.253', '34.47.93.43', '48.209.163.104', '48.209.162.122'], $data);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/theoremreach?user_id={user_id}&campaign_id={offer_id}&campaign_name={offer_name}&amount={reward}&ip={ip}&payout={currency}"
    public function theoremreach(Request $request)
    {
        if ($request->has('debug') && $request->debug) {
            return "0";
        }

        $data = $request->all();
        $data['payout'] = $data['currency'] ?? 0;
        $data['status'] = null;
        return $this->handlePostback($request, 'Theoremreach', modifiedData: $data);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/upwall?user_id={userid}&campaign_id={offer_id}&campaign_name={offer_name}&amount={user_amount}&ip={ip_address}&payout={payout}"
    public function upwall(Request $request)
    {
        return $this->handlePostback($request, 'UpWall');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/taskwall?user_id={userid}&campaign_id={offer_id}&campaign_name={offer_name}&amount={user_amount}&ip={ip_address}&payout={payout}"
    public function taskwall(Request $request)
    {
        return $this->handlePostback($request, 'Taskwall');
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/tplayad?user_id={subId}&campaign_id={campaign_id}&amount={reward}&ip={userIp}&payout={payout}"
    public function tplayad(Request $request)
    {
        $data = $request->all();
        $data['company'] = "Tplayad";
        $data['user_id'] = $data['subId'] ?? null;
        $data['ip'] = $data['userIp'] ?? null;
        $data['amount'] = $data['reward'] ?? 0;
        $data['campaign_name'] = $data['offer_name'] ?? null;
        $data['country_code'] = $data['country'] ?? null;

        $response = $this->handlePostback($request, 'Tplayad', modifiedData: $data);
        return $response->getStatusCode() === 200 ? response('OK', 200) : response('0', 400);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/adtogame?user_id={user_id}&campaign_id={offer_id}&campaign_name={offer_name}&payout={payout_usd}&amount={points}&country_code={geo}"
    public function adtogame(Request $request)
    {
        return $this->handlePostback($request, 'AdToGame', ['64.226.124.135']);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/adparagon"
    public function adparagon(Request $request)
    {
        $data = $request->all();
        $data['company'] = "AdParagon";
        $data['user_id'] = $data['subId'];
        $data['ip'] = $data['userIp'];
        $data['amount'] = $data['reward'];
        $data['country_code'] = $data['country'] ?? null;
        $data['campaign_name'] = $data['offer_name'] ?? null;

        $response = $this->handlePostback($request, 'adParagon', modifiedData: $data);
        return $response->getStatusCode() === 200 ? response('OK', 200) : response('0', 400);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/revtoo"
    public function revtoo(Request $request)
    {
        $data = $request->all();
        $data['company'] = "Revtoo";
        $data['user_id'] = $data['subId'];
        $data['ip'] = $data['userIp'];
        $data['amount'] = $data['reward'];
        $data['country_code'] = $data['country'] ?? null;
        $data['campaign_name'] = $data['offer_name'] ?? null;

        $response = $this->handlePostback($request, 'Revtoo', [
            '195.35.39.220',
            '2a02:4780:b:1270:0:2b97:5732:1',
            '2a02:4780:b:1270:0:2b97:5732:2',
            '2a02:4780:b:1234::19'
        ], modifiedData: $data);

        return $response->getStatusCode() === 200 ? response("ok", 200) : response("0", 400);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/inbrain"
    public function inbrain(Request $request)
    {
        $data = $request->all();
        $data['campaign_id'] = $data['UniqueSurveyId'] ?? null;
        $data['user_id'] = $data['PanelistId'] ?? null;
        $data['amount'] = $data['Reward'] ?? null;
        $data['payout'] = $data['RevenueAmount'] ?? null;
        return $this->handlePostback($request, 'Inbrain', modifiedData: $data);
    }

    // "https://lootcasher.com/ZJX44M5PLK/postback/notik"
    public function notik(Request $request)
    {
        $data = $request->all();
        $data['campaign_id'] = $data['offer_id'] ?? null;
        $data['campaign_name'] = $data['offer_name'] ?? null;
        $data['ip'] = $data['conversion_ip'] ?? null;
        return $this->handlePostback($request, 'Notik', modifiedData: $data);
    }
    
     
    // "https://lootcasher.com/ZJX44M5PLK/postback/radientwall?user_id={subId}&campaign_id=1&campaign_name={offer_name}&amount={reward}&ip={userIp}&payout={reward}"
    
    public function radientwall(Request $request)
    {
        return $this->handlePostback($request, 'Radientwall');
    }
    
    
    
      // "https://lootcasher.com/postback/ZJX44M5PLK/primewall?user_id={subId}&campaign_id={transId}&campaign_name={OfferName}&amount={reward}&ip={userIp}&payout={payout}"
    
    public function primewall(Request $request)
    {
        return $this->handlePostback($request, 'Primewall');
    }



    // // "https://lootcasher.com/ZJX44M5PLK/postback/paidbusky?user_id={subid}&campaign_id={campaign_id}&campaign_name={offer_name}&amount={reward}&ip={userip}&payout={payout}"
    
    public function paidbusky(Request $request)
    {
        return $this->handlePostback($request, 'Paidbusky');
    }
    

    // // "https://lootcasher.com/postback/ZJX44M5PLK/offery?user_id={subId}&campaign_id={offer_id}&campaign_name={offer_name}&amount={reward_value}&ip={userIp}&payout={payout}"
    
    public function offery(Request $request)
    {
        return $this->handlePostback($request, 'Offery');
    }
    

    // // "https://lootcasher.com/postback/ZJX44M5PLK/sushiads?user_id={user_id}&campaign_id={offer_id}&campaign_name={offer_name}&amount={reward}&ip={ip}&payout={payout}"
    
    public function sushiads(Request $request)
    {
        return $this->handlePostback($request, 'Sushiads');
    }
    
    
    // // "https://lootcasher.com/postback/ZJX44M5PLK/adtowall?user_id={user_id}&campaign_id={offer_id}&campaign_name={offer_name}&amount={points}&ip={ip}&payout={payout_usd}"
    
    public function adtowall(Request $request)
    {
        return $this->handlePostback($request, 'Adtowall');
    }
    
    
      // https://cashbunk.co/ZJX44M5PLK/postback/vortexwall.php?identity_id={IDENTITY_ID}&campaign_id={CAMPAIGN_ID}&campaign_name={CAMPAIGN_NAME}&event_id={EVENT_ID}&event_name={EVENT_NAME}&payout={PAYOUT}&points={POINTS}&txid={TXID}&result={RESULT}&ipaddr={IPADDR}

    public function vortexwall(Request $request)
    {
        return $this->handlePostback($request, 'Vortexwall');
    }
    
    
    
    
    
    
    
}
