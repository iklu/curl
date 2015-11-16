<?php
/**
Example values
url - 'http://example.com'
fields - array('var' => 'value'), or can be empty
auth - 'user:password', or can be empty
*/
function curl($url, $fields = array(), $auth = false){
   
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
   
    if($auth){
        curl_setopt($curl, CURLOPT_USERPWD, "$auth");
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    }

    if($fields){       
        $fields_string = http_build_query($fields);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
    }
   
    $response = curl_exec($curl);
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header_string = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
   
    $header_rows = explode(PHP_EOL, $header_string);
    $header_rows = array_filter($header_rows, 'trim');
    $i=0;

    foreach((array)$header_rows as $hr){
        $colonpos = strpos($hr, ':');
        $key = $colonpos !== false ? substr($hr, 0, $colonpos) : (int)$i++;
        $headers[$key] = $colonpos !== false ? trim(substr($hr, $colonpos+1)) : $hr;
    }
    foreach((array)$headers as $key => $val){
        $vals = explode(';', $val);
        if(count($vals) >= 2){
            unset($headers[$key]);
            $j=0;
            foreach($vals as $vk => $vv){
                $equalpos = strpos($vv, '=');
                $vkey = $equalpos !== false ? trim(substr($vv, 0, $equalpos)) : (int)$j++;
                $headers[$key][$vkey] = $equalpos !== false ? trim(substr($vv, $equalpos+1)) : $vv;
            }
        }
    }
    //print_rr($headers);
    curl_close($curl);
    return array($headers);
}

if(isset($argv[1])) {
    list($d['headers']) = curl($argv[1], array('request' => 'hola'));
//POST to example.com with POST var "parameter" as "value"
} else {
    list( $d['headers']) = curl($_POST['url'], array('request' => 'hola'));
}
   
echo '<pre>';

print_r($d);
