<?php

class Apps09_Api_Zillow {
		
	private $_zwsid = "";
		
	public function _init() 
	{
		
	}
	
	public function getZillowChartByZipCode($zipCode)
	{ 	
		$client = new Zend_Http_Client('http://www.zillow.com/webservice/GetRegionChart.htm');
		$client->setMethod(Zend_Http_Client::GET);
		$client->setParameterGet(array(
    		'zws-id'=>$this->_zwsid,
    		'zip'=>$zipCode,
    		'unit-type'=>'dollar',
			'width'=>'550',
			'height'=>'250',
			'chartDuration'=>'10years'
		));
		$response = $client->request()->getBody();
		$chartUrl = $this->parseZillowXMLChart($response);
		return $chartUrl;
	}
	
	private function parseZillowXMLChart($response)
	{
		$xml = simplexml_load_string($response);
		return $xml->response->url;
	}


}