<?php 
class CanonicalCandidate
{
	public $url;
	public $metrics;
	
	function __construct($url)
	{
		$this->url = $url;
	}
	
	protected function lsapi_metrics_url()
	{
		//taken from: http://apiwiki.seomoz.org/Sample-Code
		$objectURL = $this->url;
		$accessID = LSAPI_ACCESS_ID;
		$secretKey = LSAPI_SECRET_KEY;
		$expires = mktime() + 300;  // The request is good for the next 5 minutes, or 300 seconds from now.
		$stringToSign = $accessID."\n".$expires;
		
		//only get the columns you need as described in http://apiwiki.seomoz.org/Request-Response+Format
		$cols = "805308421";
		 
		// Get the "raw" or binary output of the hmac hash.
		$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
		 
		// We need to base64-encode it and then url-encode that.
		$urlSafeSignature = urlencode(base64_encode($binarySignature));
		$urlToFetch = "http://lsapi.seomoz.com/linkscape/url-metrics/".urlencode($objectURL)."?Cols=$cols&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;
		return $urlToFetch;
	}
	
	function load($metrics = NULL)
	{
		//TODO: get data for just this candidate
		$url = $this->lsapi_metrics_url();
		
		echo "<!-- $url -->";
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,            $url);
		curl_setopt($curl, CURLOPT_HEADER,         0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    
		$ret = curl_exec($curl);
		$httpstatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		if($ret === false)
		{
			$cerror = curl_error($curl);
			throw new Exception("error getting data from '$url' : '$cerror'");
		}
		else if($httpstatus != 200)
		{
			//get rid of any pesky newlines
			$error = preg_replace("\n", " ", $ret);
			throw new Exception("error '$httpstatus' getting data from '$url' : '$error'");
		}
		
		$metrics = json_decode($ret);
		if($metrics === NULL)
		{
			throw new Exception("error decoding data from '$url'");
		}
		
		$this->metrics = $metrics;
	}
	
	static function load_many($candidates)
	{
		//get the LSAPI calls for each candidate
		$urls = array();
		foreach($candidates as $id => $candidate)
		{
			$urls[] = $candidate->lsapi_metrics_url();	
		}
		
		//make the requests in parallel to reduce response time
		$results = multiRequest($urls);
		
		//get the results and set up the candidates
		foreach($results as $id => $result)
		{
			$metrics = json_decode($result);
			if($metrics === NULL)
			{
				throw new Exception("error decoding data from '$urls[$id]'");
			}
			$candidates[$id]->metrics = $metrics;
			//keep track of the urls that we fetched successfully
			$urls[$id] = NULL; 
		}
		
		foreach($urls as $id => $url)
		{
			if($url !== NULL)
				throw new Exception("error fetching '$url'");
		}
	} 
}
?>