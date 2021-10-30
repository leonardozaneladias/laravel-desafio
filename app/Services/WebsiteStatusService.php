<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;

class WebsiteStatusService {

    public static function getStatus($url){

   
        try{

            $client = new Client();
            $response = $client->get($url);

            $ret = [
                'httpCode' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents()
            ];

        }catch(\GuzzleHttp\Exception\ClientException $e){

            $ret = [
                'httpCode' => $e->getResponse()->getStatusCode(),
                'body' => $e->getResponse()->getBody()->getContents()
            ];


        }catch(\GuzzleHttp\Exception\BadResponseException $e){

            $ret = [
                'httpCode' => $e->getResponse()->getStatusCode(),
                'body' => $e->getResponse()->getBody()->getContents()
            ];


        }catch(\GuzzleHttp\Exception\RequestException $e){

            $ret = [
                'httpCode' => $e->getResponse()->getStatusCode(),
                'body' => $e->getResponse()->getBody()->getContents()
            ];


        }catch(GuzzleException $e){

            $ret = [
                'httpCode' => 0,
                'body' => "Erro geral, decrição do erro: {$e->getMessage()}"
            ];

        }

        return $ret;

    }

}