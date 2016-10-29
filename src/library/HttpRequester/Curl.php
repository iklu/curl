<?php

namespace src\library\HttpRequester;


class Curl 
{
    /**
     * @param $link
     * @param $parameters
     * @param string $method
     */
    public static function curl($link, $parameters = NULL, $method = 'GET', $headers = NULL, $file = NULL)
    {   
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        //curl_setopt($curl, CURLOPT_URL, 'https://meineke'. trim($request->get('storeId')) . '.fullslate.com/api/bookings');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if($headers != NULL){
            curl_setopt($curl,CURLOPT_HTTPHEADER,array($headers));
        }
        if(($method == 'POST' or $method == 'PUT') AND $parameters != NULL){
            if($file){
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
            }else{
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
            }
        }


//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);


        $response = curl_exec($curl);


   

        return $response;
    }
}
