<?php
namespace tests\library\HttpRequester;

use src\library\HttpRequester\Curl;

class CurlTest extends \PHPUnit_Framework_TestCase
{
	public function testGetRequest()
	{
	
		$curl = Curl::curl("http://localhost");
		
		print_r($curl);
	
	}	
	  
}
