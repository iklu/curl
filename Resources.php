<?php 
namespace src\library\Resources;

class Resources {

    public $url;

    public $argv = array();

    public function __construct($url, array $argv) {
        $this->url = $url;
        $this->argv = $argv;
    }

    /**
    *1- Write a PHP script to report the total download size of any URL
    *2- No HTML interface is necessary for this exercise ; you can write this as a command-line script that accept the URL ad an argument.
    *3- For a single-file resource such as an image or SWF , the script would simply report the total size or the document.
    *4- For a complex resurce such as an HTML document , the script would need to parse it to find references to embedded , incuded resurces: javascript files, CSS files , iframes, etc.
    *    THe goal o this exercise is to output the following information for a given URL:
    *	-total number of HTTP requsts
    *	-total download size for all requests
    */
    public function processRequest(){
        $data["total_http_requests"] = 0;

        $html = $this->checkIfHtml();
        if($html) {
            $data["total_http_requests"] += 1;
            $data["page_size"] = getRemoteFileSize();
        }

        // Create a DOM object
        $html = new simple_html_dom();

        // Load HTML from a URL
        $html->load_file($this->url);

        foreach($html->find('img') as $element)
        {
            
            $data["resource_size"]  = get_remote_file_size($URL.$element->src);
            $data["total_size"]  =  $data["page_size"] + $data["image_size"];
            $data["total_http_requests"] +=1;
            
            echo "TOTAL SIZE SO FAR:    ".$data['total_size'] ."\n";
            echo "TOTAL RESOURCES :     ".$data['total_http_requests']."\n";
            echo "IMAGE SIZE:           ".$data['image_size']."\n";
            echo "IMAGE LOCATION:         {$element->src}.\n";
            
        }

        //find all javascript:
        foreach($html->find('script') as $element)
        {
            //check to see if it is javascript file:
            if (strpos($element->src,'.js') !== false) {
            $size = get_remote_file_size($URL.$element->src);
            //echo " JS SIZE: $size.\n";
            $totalSize = $totalSize + $size;
            $totalNumResources += 1;

            echo "TOTAL SIZE SO FAR: $totalSize.\n";
            echo "TOTAL SIZE SO FAR: $totalNumResources .\n";
            echo "SCRIPT LOCATION : {$element->src}.\n";

            }
        }

        foreach($html->find('link') as $element)
        {
            if (strpos($element->href,'.css') !== false) 
            {
                $size = get_remote_file_size($URL.$element->href);
                $totalSize = $totalSize + $size;
                $totalNumResources += 1;

                echo "total resources: $totalNumResources .\n";
                echo "Total Size So CSS  Far: $totalSize.\n";
                echo "$element->href.\n";

            }
        //only output the ones with 'css' inside...
        }
    }

    /**
    * check to see if the URL points to an HTML page, if it doesn't then we are dealing with a single file resource:
    */
    public function checkIfHtml(){

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);	
        
        $data = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if(strpos($contentType, "text/html")!==false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getRemoteFileSize(){

        $headers = get_headers($this->url, 1);

        if (isset($headers['Content-Length'])) 
            return $headers['Content-Length'];

        //this one checks for lower case "L" IN CONTENT-length:
        if (isset($headers['Content-length'])) 
            return $headers['Content-length'];

        $c = curl_init();
        curl_setopt_array($c, array(
        CURLOPT_URL => $this->url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:37.0) Gecko/20100101 Firefox/37.0')));
        curl_exec($c);
        $size = curl_getinfo($c, CURLINFO_SIZE_DOWNLOAD);       
        curl_close($c);

        return $size;
    }

}

/**
*1- Write a PHP script to report the total download size of any URL
*2- No HTML interface is necessary for this exercise ; you can write this as a command-line script that accept the URL ad an argument.
*3- For a single-file resource such as an image or SWF , the script would simply report the total size or the document.
*4- For a complex resurce such as an HTML document , the script would need to parse it to find references to embedded , incuded resurces: javascript files, CSS files , iframes, etc.
*    THe goal o this exercise is to output the following information for a given URL:
*	-total number of HTTP requsts
*	-total download size for all requests
*/
include('./simple_html_dom-master/simple_html_dom.php');
$URL = $argv[1];
$totalSize = 0;
$totalNumResources = 0;

/**
* check to see if the URL points to an HTML page, if it doesn't then we are dealing with a single file resource:
*/
if(check_if_html($URL))
{
	$totalSize = get_remote_file_size($URL);
	
	echo "Final Total Download Size: $totalSize Bytes\n";
	$totalNumResources+=1;
	echo "Final Total HTTP requests: $totalNumResources\n";

}
/**
* At this point we know we are dealing with an HTML document which also counts as resource , so increment the $totalNumResources variable by 1
*/

$totalNumResources+=1;


$html = file_get_html($URL);

foreach($html->find('img') as $element)
{
	
	$size = get_remote_file_size($URL.$element->src);
	$totalSize = $totalSize + $size;
	$totalNumResources+=1;
	
	echo "TOTAL SIZE SO FAR: $totalSize.\n";
	echo "TOTAL RESOURCES : $totalNumResources.\n";
	echo "IMAGE SIZE: $size.\n";
	echo "IMAGE LOCATION: {$element->src}.\n";
	
}
//find all javascript:
foreach($html->find('script') as $element)
{
	//check to see if it is javascript file:
	if (strpos($element->src,'.js') !== false) {
	$size = get_remote_file_size($URL.$element->src);
	//echo " JS SIZE: $size.\n";
	$totalSize = $totalSize + $size;
	$totalNumResources += 1;

	echo "TOTAL SIZE SO FAR: $totalSize.\n";
	echo "TOTAL SIZE SO FAR: $totalNumResources .\n";
	echo "SCRIPT LOCATION : {$element->src}.\n";

	}
}

foreach($html->find('link') as $element)
{
	if (strpos($element->href,'.css') !== false) 
	{
		$size = get_remote_file_size($URL.$element->href);
		$totalSize = $totalSize + $size;
		$totalNumResources += 1;

		echo "total resources: $totalNumResources .\n";
		echo "Total Size So CSS  Far: $totalSize.\n";
		echo "$element->href.\n";

	}
//only output the ones with 'css' inside...
}

	echo "\n\nFinal total download size Bytes: $totalSize \n";
	echo "Final total download size Kb: ".$totalSize/(1024)." \n" ;
	echo "Final total download size Mb:".$totalSize/(1024*1024)." \n";
	echo "Final total HTTP requests: $totalNumResources\n";

function check_if_html($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, TRUE);	
	
	$data = curl_exec($ch);
	$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	curl_close($ch);

	if(strpos($contentType, "text/html")!==false)
	{
		return true;
	}
	else
	{
	echo "FALSE+++++++++++++++++++++++";
		return false;
	}
}


function get_remote_file_size($url) {

	$headers = get_headers($url, 1);

	if (isset($headers['Content-Length'])) return $headers['Content-Length'];
	//this one checks for lower case "L" IN CONTENT-length:
	if (isset($headers['Content-length'])) return $headers['Content-length'];
	$c = curl_init();
	curl_setopt_array($c, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => array('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:37.0) Gecko/20100101 Firefox/37.0')));
	curl_exec($c);
	$size = curl_getinfo($c, CURLINFO_SIZE_DOWNLOAD);
	return $size;
	curl_close($c);
}

