<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Exchange class
 */
class WavesExchange
{
    public static $ASSET_ID = 'AxAmJaro7BJ4KasYiZhw7HkjwgYtt2nekPuF2CN9LMym';//WNET

    public static function convert($currency, $amount)
    {

        // making use of cryptonator api

        $url = "https://api.cryptonator.com/api/ticker/". strtolower($currency) ."-waves";

        $result = WavesExchange::getBodyAsJson($url);
        if(!$result) {
            return 'undefined';
        }
        $rate = $result->ticker->price;
        return round($amount * $rate,6);
    }

    private static function getBodyAsJson($url,$retries=1) {
        $response = wp_remote_get( $url );
        $result = json_decode(wp_remote_retrieve_body($response));
        if(!$result && $retries>0) {
            return WavesExchange::getBodyAsJson($url,--$retries);
        }
        return $result?$result:null;
    }

    public static function getAssetPrice() {
        $result = WavesExchange::getBodyAsJson("http://marketdata.wavesplatform.com/api/trades/".WavesExchange::$ASSET_ID."/WAVES/1");
        return $result?$result->price:'undefined';
    }
}
