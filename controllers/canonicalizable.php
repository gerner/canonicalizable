<?php
if(isset($_GET["homepage_input"]))
{
	$homepage = $_GET["homepage_input"];
	if(strpos($homepage, "http://") === 0)
		$homepage = substr($homepage, 7);
	else if(strpos($homepage, "https://") === 0)
		$homepage = substr($homepage, 8);
		
	$c_homepage = new CanonicalizingHomepage($homepage);
	$c_homepage->load();
	
	//we'll get the url as returned by LSAPI so we're consistent if we need to do comparisons
	$homepage = $c_homepage->homepage->metrics->uu;
	
	//first let's check out the homepage the user gave us
	
	//200 OK is great!
	if($c_homepage->homepage->metrics->us == 200)
	{	
		$homepage_isgood = true;
		$homepage_message = "Everything looks good so far!";
	}
	//everything else isn't
	else
	{
		$homepage_isgood = false;
		//3xx redirect to another page is not so good.
		if($c_homepage->homepage->metrics->us >= 300 && $c_homepage->homepage->metrics->us < 400)
		{
			$homepage_message = "It seems as if your homepage redirects to <a href=\"http://".htmlspecialchars($c_homepage->homepage->metrics->ur)."\">".htmlspecialchars($c_homepage->homepage->metrics->ur)."</a>.  You should fix this so that your homepage url serves your homepage content.";
		}
		else if($_homepage->homepage->metrics->us < 100)
		{
			$homepage_message = "Your homepage wasn't crawled by Linkscape.  It may be fresh enough that Linkscape hasn't reached it, or it might be blocking bots in robots.txt.";
		}
		else if($_homepage->homepage->metrics->us == 404)
		{
			$homepage_message = "Your homepage url returned a 404 not found.  It seems as if there is no page at that url.";
		}
		else
		{
			$homepage_message = "Your homepage is not returning content and instead returns ".$_homepage->homepage->metrics->us.".  This isn't a useful response to web browsers or search engines.";
		}
	}
	
	//take a look at the candidate pages
	$candidates = array();
	$candidate_errors = 0;
	$candidate_warnings = 0;
	foreach($c_homepage->candidates as $candidate)
	{
		if($candidate->metrics->us > 0) //skip pages Linkscape hasn't crawled
		{
			if($candidate->metrics->us == 301 && $candidate->metrics->ur == $homepage)
			{
				$isgood = 0;
				$message = "This page redirects with a 301 to your homepage.";
			}
			else
			{
				$isgood = 2;
				if($candidate->metrics->us == 200)
					$message = "This page resolves with content.  But it should redirect to your homepage instead.";
				else if($candidate->metrics->us >= 300 && $candidate->metrics->us < 400)
				{
					if($candidate->metrics->ur == $homepage)
						$message = "This page redirects to your homepage, but with a ".$candidate->metrics->us." status code, instead of a 301.";
					else
						$message = "This page redirects to <a href=\"http://".htmlspecialchars($candidate->metrics->ur)."\">".htmlspecialchars($candidate->metrics->ur)."</a> with a ".$candidate->metrics->us.".  This should be a 301 to your homepage.";
				}
				else if($candidate->metrics->us == 404)
				{
					$isgood = 1;
					$message = "This page returns a 404 Not Found.  This isn't technically an error, but this common page should probably 301 redirect to your homepage.";
				}
				else if($candidate->metrics->us >= 200)
					$message = "This page returned a ".$candidate->metrics->us.".  It should probably be 301 redirecting to your homepage.";
				else
					$message = "This page had some error which prevented it from being crawled.  It may be blocked by robots.txt, but you should probably redirect it to your homepage with a 301";
			}
		}
		else
		{
			$isgood = 1;
			$message = "Linkscape didn't crawl this page.  That could mean there are no links to it, or that it's already doing the right thing.  But you might want to check yourself.";
		}
		if($isgood == 2) $candidate_errors++;
		else if($isgood == 1) $candidate_warnings++;
		$candidates[] = array($isgood, $candidate, $message);
	}	
	rsort($candidates);
}
else
	$homepage = "";


?>