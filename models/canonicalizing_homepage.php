<?php
class CanonicalizingHomepage
{
	public $domain;
	public $homepage_url;
	public $candidates;
	public $suggested_candidate;
	
	function __construct($h)
	{
		$h = trim($h);
		if(strpos($h, "/") === false)
			$h .= "/";
		$this->homepage_url = $h;
		
		$d = substr($this->homepage_url, 0, strpos($this->homepage_url, "/"));
		
		$this->domain = $d;
		$this->candidates = array();
		$this->suggested_candidate = NULL;
	}
	
	function get_candidate_urls()
	{
		$candidate_urls = array();
		
		//add the opposite of the www vs non-www version as a candidate to check
		$alternate_domain;
		if(strpos($this->homepage_url, "www.") === 0)
		{
			$candidate_urls[] = substr($this->homepage_url, 4);
			$alternate_domain = substr($this->homepage_url, 4, strlen($this->homepage_url)-5);
		}
		else
		{
			$candidate_urls[] = "www.".$this->homepage_url;
			$alternate_domain = substr($this->homepage_url, 0, strlen($this->homepage_url)-1);
		}
			
		$possible_files = array(
			"index.htm", 
			"index.html", 
			"homepage.htm", 
			"homepage.html", 
			"default.htm", 
			"default.html", 
			"homeapge.asp", 
			"homepage.aspx", 
			"default.asp", 
			"default.aspx", 
			"index.php");
		
		foreach($possible_files as $file)
		{
			$candidate_url = $this->domain."/".$file;
			if($candidate_url != $this->homepage_url)
				$candidate_urls[] = $candidate_url;
			$candidate_url = $alternate_domain."/".$file;
			$candidate_urls[] = $candidate_url;
		}
		
		return $candidate_urls;
	}
	
	function load()
	{
		//first set up our candidates
		$candidate_urls = $this->get_candidate_urls();
		foreach($candidate_urls as $url)
		{
			$this->candidates[] = new CanonicalCandidate($url);
		}
		
		//this will be a bunch of candidates to fetch
		$candidates = $this->candidates;
		
		//add the homepage so we get data for it along with all the candidates
		$this->homepage = new CanonicalCandidate($this->homepage_url);
		$candidates[] = $this->homepage;
		
		//get all the candidates at once which will be faster than individually
		CanonicalCandidate::load_many($candidates);
	}
}
?>