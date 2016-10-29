<?php
namespace tests\library\Resources;

use src\library\HttpRequester\Curl;

class ResourcesTest extends \PHPUnit_Framework_TestCase
{
	public function testGetRequest()
	{
	
		$curl = Curl::curl("http://localhost/RO-WEB/web/search/");
		
		print_r($curl);
	
	}	
	  
}
