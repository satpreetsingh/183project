<?php

	// the host where the script lives
//	$proxy = "noteshare.homelinux.net";
	$proxy = "localhost";
	
	// extract oringinal url from get query
	$url = urldecode($_GET['IPS_Orig_Host']);
	$url = str_replace(" ","+",$url);

	// get the original header and body
	$headers = apache_request_headers();
	
	$body = @file_get_contents('php://input');
	
	// request and store the requested page using header and body
	$cu = curl_init();
	curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cu, CURLOPT_HEADER, true);
	curl_setopt($cu, CURLOPT_URL, $url);
	
	//curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($cu, CURLOPT_COOKIE, $headers['Cookie']);
	curl_setopt ($cu, CURLOPT_USERAGENT, $headers['User-Agent']);
	curl_setopt ($cu, CURLOPT_REFERER, $headers['Referer']);
	
	// Add any post arguments
	if(count($_POST) > 0)
	{
		curl_setopt($cu, CURLOPT_POST, true);
		curl_setopt($cu, CURLOPT_POSTFIELDS, $body);
	}
	
	// Execute curl
	$response = curl_exec($cu);
	
	
	// Split response to header and body
	$headerBody = explode("\r\n\r\n",$response,2);
	$headerLines = explode("\r\n",$headerBody[0]);
	
	
	if(curl_getinfo($cu,CURLINFO_HTTP_CODE) == 200)
	{
		// Set the cookies to the user
		foreach( $headerLines as $line )
		{
			if(substr($line,0,10)=="Set-Cookie")
			{
				header($line,false);
				continue;
			}
		}
	}
	else
	{
		// Set the cookies to the user
		foreach( $headerLines as $line )
		{
			header($line,false);
		}
		
		echo $headerBody[1];
		exit();
	}
	curl_close($cu);
	
	
//	header('Content-type: application/xhtml+xml');

	// Create the DOM document from the page
	$document = new DOMDocument();
	$patterns[0] = '/<script.*?>/';
	$patterns[1] = '/<\/script>/';
	$patterns[2] = '/<style.*?>/';
	$patterns[3] = '/<\/style>/';
	$replaces[0] = '\\0<!--';
	$replaces[1] = '-->\\0';
	$replaces[2] = '\\0<!--';
	$replaces[3] = '-->\\0';
	$body = preg_replace($patterns, $replaces, $headerBody[1]);
	@$document->loadHTML( $body );

	// XPath for searching the DOM
	$xpath = new DOMXpath($document);

	// get the head
	$head = $xpath->query('/html/head');
	
	// only process documents with a head
	if($head->length > 0)
	{
		$head = $head->item(0);
		
		// prepare a script node of type javascript and src to the script
		$script = $document->createElement('script');
		$type = $document->createAttribute('type');
		$type->appendChild($document->createTextNode('text/javascript'));
		$src = $document->createAttribute('src');
		$src->appendChild($document->createTextNode("http://$proxy/infoproxy/infoProxyScript.js"));
		$script->appendChild($type);
		$script->appendChild($src);
		
		// add script to head
		$head->appendChild($script);
		
		// where theres a head theres a body...
		$body = $xpath->query('/html/body');
		
		if($body->length != 0)
		{
			$body = $body->item(0);
			
			// prepare a new div for the body
			$div = $document->createElement('div');
			$id = $document->createAttribute('id');
			$id->appendChild($document->createTextNode('IPS_div'));
			$div->appendChild($id);
			$style = $document->createAttribute('style');
			$style->appendChild(
				$document->createTextNode(
					'position:fixed; bottom:0%; right:0%; width:200px; height:62px; z-index:100;'));
			$div->appendChild($style);
			
			// add the div to the body
			$body->appendChild($div);
			
			// embed the svg from http://en.wikipedia.org/wiki/File:Information_icon.svg
			$embed = $document->createElement('embed');
			$id = $document->createAttribute('id');
			$id->appendChild($document->createTextNode("IPS_svg"));
			$embed->appendChild($id);
			$src = $document->createAttribute('src');
			$src->appendChild($document->createTextNode("IPS_SVG_ICON.svg"));
			$embed->appendChild($src);
			$type = $document->createAttribute('type');
			$type->appendChild($document->createTextNode('image/svg+xml'));
			$embed->appendChild($type);
			$plugin =  $document->createAttribute('pluginspage');
			$plugin->appendChild($document->createTextNode('http://www.adobe.com/svg/viewer/install/'));
			$embed->appendChild($plugin);
			$style = $document->createAttribute('style');
			$style->appendChild($document->createTextNode('position:fixed; bottom:0%; right:0%; z-index:99;'));
			$embed->appendChild($style);
			
			// add the embed to the body
			$body->appendChild($embed);

						
			// prepare a script node of type javascript and src to the script
			$script = $document->createElement('script');
			$type = $document->createAttribute('type');
			$type->appendChild($document->createTextNode('text/javascript'));
			$src = $document->createAttribute('src');
			$src->appendChild($document->createTextNode("http://$proxy/infoproxy/infoProxyScriptReg.js"));
			$script->appendChild($type);
			$script->appendChild($src);
		
			// add script to body
			$body->appendChild($script);
		
		}
	}
	
	// echo the modified (or unmodified) doc
	$newDoc = $document->saveHTML();
	echo $newDoc;
	
	exit();
	
?>
	
