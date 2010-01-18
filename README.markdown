Canonicalizable
===============

A fairly simple web application that asks the user for the homepage of a site and checks if the site is correctly canonicalizing the homepages using 301 redirects. 

This is a reference application illustrating how to use the Linkscape API in a real-world setting.  See:

 * models/canonical_candidate.php for the code that interacts with the API
 * controllers/canonicalizable.php for code that uses the metrics response
 * index.php for code that outputs data from the API
 
Check out the [API Wiki](http://apiwiki.seomoz.org) for more information, or [sign up](http://www.seomoz.org/api) for a free account.

You can see a live version of this code at [http://www.nickgerner.com/canonicalizable/](http://www.nickgerner.com/canonicalizable/)
 
Installation
------------

Simply check the repository out and configure your php-enabled web server to serve the pages.

You'll need to copy configuration.php.tmpl to configuration.php and update a few settings:

 * LSAPI_ACCESS_ID should be set to your Linkscape Access ID
 * LSAPI_SECRET_KEY should be set to your Linkscape Secret Key
 
Code Organization
-----------------

Canonicalizable is implemented using a simple MVC (although it doesn't depend on any framework):

 * index.php is the view and handles all of the layout
 * controllers/canonicalizable.php handles most of the logic for the application
 * models/canonicalizing_homepage.php abstracts the homepage of a domain and understands alternate candidate homepages
 * models/canonical_candidate.php abstracts a single homepage candidate. *All the LSAPI interface can be found here.*

Other files:

 * configuration.php.tmpl is a template for the configuration file (configuration.php) you'll need to create and modify yourself.  This includes your LSAPI key information.
 * includes.php includes all the models, configuraiton, etc.
 * parallel_request.php wraps curl_multi and lets us hit LSAPI for many requests in parallel, which will make the pages load very quickly.
 * css holds all the stylesheets 

Thanks
------

Thanks to:

 * [Sarah Bird](http://www.seomz.org/team/sarah) for wrangling technical documentation in readable, coherent documentation
 * [Stoyan Stefanov](http://www.phpied.com) for writing a neat function to wrap curl_multi (see parallel_request.php)
 * [Timmy Christensen](http://timmychristensen.com/) for pointing me at Aardvark Legs.
 * [Anatoli Papirovski](http://fecklessmind.com/) for putting together [Aardvark Legs](http://aardvark.fecklessmind.com/) 