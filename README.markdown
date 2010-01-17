Canonicalizable
===============

A fairly simple web application that asks the user for the homepage of a site and checks if the site is correctly canonicalizing the homepages using 301 redirects. 

This is a reference application illustrating how to use the Linkscape API in a real-world setting.  See:

 * models/canonical_candidate.php for the code that interacts with the API
 * controllers/canonicalizable.php for code that uses the metrics response
 * index.php for code that outputs data from the API
 
Check out http://apiwiki.seomoz.org for more information, or visit http://www.seomoz.org/api to sign up.
 
Installation
------------

Simply check the repository out and configure your php-enabled web server to serve the pages.

You'll need to copy configuration.php.tmpl to configuration.php and update a few settings:

 * LSAPI_ACCESS_ID should be set to your Linkscape Access ID
 * LSAPI_SECRET_KEY should be set to your Linkscape Secret Key
 
Thanks
------

Thanks to:

 * [Sarah Bird](http://www.seomz.org/team/sarah) for wrangling technical documentation in readable, coherent documentation
 * [Stoyan Stefanov](http://www.phpied.com) for writing a neat function to wrap curl_multi (see parallel_request.php)
 * [Timmy Christensen](http://timmychristensen.com/) for pointing me at Aardvark Legs.
 * [Anatoli Papirovski](http://fecklessmind.com/) for putting together [Aardvark Legs](http://aardvark.fecklessmind.com/) 