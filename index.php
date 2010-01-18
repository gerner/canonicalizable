<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<?php 
require_once("includes.php"); 
require_once("controllers/canonicalizable.php");
?>
<html>
<head>
<link rel="stylesheet" href="css/aal.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="css/canonicalizable.css" type="text/css" media="screen" charset="utf-8" /> 
</head>
<body>
<div id="wrapper"> 
<a href="http://www.seomoz.org/linkscape/intel/basic?uri=<?php echo urlencode($homepage);?>"><img class="right" src="powered_by_linkscape.png" alt="Powered By Linkscape" /></a>
<h1>Canonicalizable</h1>

<form id="homepage_form" class="horizontal" action="" method="get"> 
	<fieldset> 
		<div class="field"> 
			<span class="form_prefix">http://</span><input type="text" class="text" id="homepage_input" name="homepage_input" value="<?php echo $homepage; ?>" />
			<button type="submit">Submit</button>
		</div>
	</fieldset>
</form>
<?php if(!$c_homepage) { ?>
<p>Put what you think of as your homepage in and take a look at some common problems that might confuse users and Search Engines.</p>
<?php } else {?>

<h2>Homepage</h2>

<p>This is the page you want users to end up first when they visit your site.</p>

<div class="messagebox <?php echo $homepage_isgood?"goodbox":"badbox";?>">
<ul>
<li>URL: <a href="http://<?php echo htmlspecialchars($homepage); ?>"><?php echo htmlspecialchars($homepage); ?></a></li>
<li>Title: <?php echo htmlspecialchars($c_homepage->homepage->metrics->ut, ENT_NOQUOTES, "UTF-8"); ?></li>
<li>HTTP Status: <?php echo $c_homepage->homepage->metrics->us; ?></li>
<li>Links: <?php echo $c_homepage->homepage->metrics->uid;?></li>
<?php if($c_homepage->metrics->ur) {?><li>Redirects To: <?php echo htmlspecialchars($c_homepage->homepage->metrics->ur); ?></li><?php } ?>
</ul>
<p><?php echo $homepage_message; ?></p>
</div>

<h2>Candidate Homepages</h2>

<?php if(count($candidates) == 0) {?>

<p>We couldn't find any references to alternative candidate homepages in the Linkscape index.  This doesn't mean that you shouldn't check these pages out yourself:</p>

<ul>
<?php foreach($c_homepage->get_candidate_urls() as $candidate_url) {?>
<li><a href="http://<?php echo $candidate_url;?>">http://<?php echo $candidate_url; ?></a></li>
<?php } // foreach($c_homepage->get_candidate_urls() as $candidate_url)?>
</ul>

<?php } else {?>
<p>These are pages you want to automatically redirect users away from, to <?php echo $homepage; ?></p>

<?php if($candidate_errors + $candidate_warnings > 0) {?>
<ul>
<li><?php echo $candidate_errors; ?> Errors</li>
<li><?php echo $candidate_warnings;?> Warnings</li>
<li><?php echo count($candidates) - $candidate_errors - $candidate_warnings;?> Pages checked doing the right thing</li>
</ul>
<?php } else {?>
Everything looks good:
<?php } // if($candidate_errors + $candidate_warnings > 0)?>

<?php foreach($candidates as $candidate_info) {
list($isgood, $candidate, $message) = $candidate_info;?>

<div class="messagebox <?php if($isgood == 0) echo "goodbox"; elseif($isgood == 1) echo "warnbox"; else echo "badbox";?>">
<ul>
<li>URL: <a href="http://<?php echo htmlspecialchars($candidate->url); ?>"><?php echo htmlspecialchars($candidate->url); ?></a></li>
<li>Title:</dt><dd><?php echo htmlspecialchars($candidate->metrics->ut, ENT_NOQUOTES, "UTF-8"); ?></li>
<li>HTTP Status: <?php echo $candidate->metrics->us; ?></li>
<li>Links: <?php echo $candidate->metrics->uid;?></li>
<?php if($candidate->metrics->ur) {?><li>Redirects To: <?php echo htmlspecialchars($candidate->metrics->ur); ?></li><?php } ?>
</ul>
<p><?php echo $message; ?></p>
</div>
<?php } // foreach($candidates as $candidate_info)?>

<?php } // count($canddiates) else?>

<?php }?>

<div class="attrib_footer">
<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/us/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/us/88x31.png" /></a><br /><span xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dc:title" rel="dc:type">Canonicalizable</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.nickgerner.com/2010/01/canonicalizable/" property="cc:attributionName" rel="cc:attributionURL">Nick Gerner</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/us/">Creative Commons Attribution-Share Alike 3.0 United States License</a>.<br />
<a href="http://github.com/gerner/canonicalizable">Check out the source code for yourself!</a>
</div>

</div>
</body>
</html>