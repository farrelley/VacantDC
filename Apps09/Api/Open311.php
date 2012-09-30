<?php

class Apps09_Api_Open311 {
	
	protected $_square = null;
	protected $_suffix = null;
	protected $_lot = null;
	
	private $_locationWSDL = "http://citizenatlas.dc.gov/newwebservices/locationverifier.asmx?WSDL";
	private $_open311APIRest = "http://api.dc.gov/open311/";
	
	protected $_open311Version = '/v1/';
	protected $_open311Format = 'json'; // can be json or xml
	protected $_open311Key = '';
	protected $_open311Method = null;
	
	public function _init() 
	{
		
	}
	
	public function getSSLFromAddress($address)
	{
		$houseArray = array();
		
		//$addressSoap = new Zend_Soap_Client($this->_locationWSDL, array('soapVersion' => SOAP_1_1));
		//$params = array('str' => $address);
		//$data = $addressSoap->findLocation($params);
		
		$xmlString = "http://citizenatlas.dc.gov/newwebservices/locationverifier.asmx/findLocation?str=" . $address;
		//$xmlString = $data->findLocationResult->returnDataset->any;
		
		//$w = simplexml_load_string($xmlString);
		$w = simplexml_load_file($xmlString);
		if(count($w->returnDataset) === 0) 
			return false;
		$tmp = $w->returnDataset->children ( 'urn:schemas-microsoft-com:xml-diffgram-v1' );
		
		$item = $tmp->children()->NewDataSet->Table1;
	
		$houseArray['Photo'] = $item->IMAGEURL . '/' . $item->IMAGEDIR . '/' . $item->IMAGENAME;
		$houseArray['SSL'] = $item->SSL;
		$houseArray['Address_Id'] = $item->ADDRESS_ID;
		
		$houseArray['Full_Address'] = 
			$item->ADDRNUM . " " .
			ucwords(strtolower($item->STNAME)) . " " .
			ucwords(strtolower($item->STREET_TYPE)) . " " .
			$item->QUADRANT . " " .
			ucwords(strtolower($item->CITY)) . ", " . 
			$item->STATE . " " . 
			$item->ZIPCODE;
			
		return $houseArray;		
	}
	
	public function submitVacantLot($aid, $description)
	{ 
		$client = new Zend_Http_Client('http://api.dc.gov/open311/v1/submit.json');
		$client->setMethod(Zend_Http_Client::POST);
		$client->setParameterPost(array(
    		'servicecode'=>'S0471',
		'apikey'  => $this->_open311Key,
    		'aid'   => $aid,
    		'description' => $description,
		));
		$response = $client->request()->getBody();
		$data = Zend_Json::decode($response, Zend_Json::TYPE_OBJECT);
		
		return $data->token; 
	}
	
	public function getServiceIdFromToken($token)
	{
		$client = new Zend_Http_Client('http://api.dc.gov/open311/v1/getFromToken.json');
		$client->setMethod(Zend_Http_Client::GET);
		$client->setParameterGet(array(
			'token'=>$token,
    			'apikey'=>$this->_open311Key,
		));
		$response = $client->request()->getBody();
		$data = Zend_Json::decode($response, Zend_Json::TYPE_OBJECT);
		return $data->servicerequestid; 
	}
	
	public function getServiceIdStatus($serviceId)
	{
		$client = new Zend_Http_Client('http://api.dc.gov/open311/v1/get.json');
		$client->setMethod(Zend_Http_Client::GET);
		$client->setParameterGet(array(
			'apikey'=>$this->_open311Key,
			'servicerequestid'=>$serviceId,
		));
		$response = $client->request()->getBody();
		$data = Zend_Json::decode($response, Zend_Json::TYPE_OBJECT);
		
		$statusInfo = $this->parse311Status($data);
		//var_dump($statusInfo);
		return $statusInfo;
		//http://api.dc.gov/open311/v1/get.json?apikey=4&servicerequestid=123456
	}
	
	private function parse311Status($data) 
	{
		$array = array();
		foreach($data->servicerequest as $shaun) {
			$key = key($shaun);
			$array[$key] = strip_tags($shaun->$key);
			

			//echo $shaun->$key;
			
			echo "<pre>";
			//var_dump($shaun);
			echo "</pre>";
//			echo $key . " - - " . $value . "<br />";
		}
		return $array;
	}

}
