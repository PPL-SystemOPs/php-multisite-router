<!DOCTYPE html>
<html><head><title><?=$title?></title></head>

<body>

<h3>CompanyPages PHP Script</h3>
<hr><br><br>
<p>
This is the template for the companysite's index page.
</p>

<p>
All these templates are reused for every host.  The only thing that differs is the data that is
fed into them.
</p>

<p>The company's name is <?=$company_name?></p>

<p>The company owner's name is <?=$company_owner_name?></p>

<p>
	The page request info to get to here is:<br/><br/>
	URL: <?=$page_request_url?><br/>
	PATH: <?=$page_request_path?><br/>
	QUERY: <?=$page_request_query?>
</p>
