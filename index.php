<?php
	$request_begin_time = time();

	/* 
	 * 
	 * Company page router -anthonym [anthony@otzi-app.com] 17/2/2014 
	 * Co-Founder & Lead Software Architect @ OTZI, Australia.
	 * http://www.otzi-app.com
	 * 
	 * NOTE:  This was written to help someone in the ##php IRC support channel on
	 * freenode (irc.freenode.net).  It was in no way intended for production use,
	 * nor is there any warranty, neither expressed nor implied that accompanies
	 * the use of this script.
	 * 
	 * I take absolutely no responsibility for any harm this script may cause to you,
	 * your company, or any other company as a result of using this script or having
	 * this script hosted on their hosting servers.
	 * 
	 * If you wish to contact me feel free to jump on freenode IRC, in ##php
	 * you shall see me there as 'anthonym'.
	 * 
	 * Regards,
	 * Anthony.
	 * 
	 */

	// obtain request parameters
	$url	   = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$url_parts = parse_url($url);
	$post_data = file_get_contents('php://input');	
	
	// set request info
	
	array_key_exists("host", $url_parts) ? $host = $url_parts["host"] : $host = null;
	array_key_exists("path", $url_parts) ? $path = $url_parts["path"] : $path = null;
	array_key_exists("query", $url_parts) ? $query = $url_parts["query"] : $query = null;
	

	$page_source="";
	$page_data=null;
	
	// open memcached
	if(class_exists("Memcached"))
	{
		$memcached = new Memcached();
		$memcached->addServer("localhost", 11211);
		$hostkey_path = md5($url);
		$page_data = $memcached->get($hostkey_path);
	}
	
	
	if(!$page_data)
	{
		/* no page data in memcached, need to generate from a database, then store it in memcached. */
		
		/* note: I am not connecting to a database in this example, you'll need to write some database
		 * code to get that working -- I'm just prepopulating data into an array.  Just fill this array
		 * with the information that your pages require. (whether from database, etc)
		 */

		$page_data = array();
		 
		switch(strtoupper($url))
		{
			case "HTTP://TEST1.OSBLAST.ORG":
				$page_data["title"] = "My Company! (TEST1 Company)";
				$page_data["company_name"] = "OTZI Australia (Test 1 Company)";
				$page_data["company_owner_name"] = "David";
				$page_source = "database";
			break;
			
			default:
				$page_data["title"] = "Default company";
				$page_data["company_name"] = "This is the default company page";
				$page_data["company_owner_name"] = "Anthony";
				$page_source = "database";
			break;
		}
		
		if(class_exists("Memcached"))
			$memcached->add($hostkey_path, $page_data);
	}
	else
	{
		$page_source = "cached";
	}
	
	
	if($page_data == null || count($page_data) < 1)
	{
		//404-page not found
		echo("Page not found. [error: pagedata not found]");
		return;
	}

	$page_data["page_request_url"] = $url;
	$page_data["page_request_path"] = $path;
	$page_data["page_request_query"] = $query;

	// page data found, now see if we can locate the correct template to show.  the template is selected
	// by the path. eg  www.mysite.com/path/goes/here .. it needs to be in a folder called templates.
	
	if($path == "" || $path== "/" || $path == null)
		$path="index";
		
	if(substr($path, 0, 1) == "/")
		$path=substr($path, 1, strlen($path)-1);

	if(substr($path, strlen($path)-1, 1) == "/")
		$path=substr($path, 0, strlen($path)-1);
	
	$template_file = "templates/".$path.".php";
	
	if(!file_exists($template_file))
	{
		//404-page not found
		echo("Page not found. [error: template not found for path: ".$template_file."]");
		return;
	}

	// render the page	
	extract($page_data);
    ob_start();
    include($template_file);
	
	$request_end_time = time();
	$total_elapsed_time = $request_end_time-$request_begin_time;
	
    echo(ob_get_clean());
	
	//footer
	echo("<br/><br/><hr>");
	echo("Page served to you by companypages, by Anthony Musgrove (anthony@otzi-app.com) [2014] - download your copy today from our github repo.");
	echo("<br/>Page served to you in ".$total_elapsed_time." sec. It was sourced from ".$page_source);
	echo("</body></html>");
?>
