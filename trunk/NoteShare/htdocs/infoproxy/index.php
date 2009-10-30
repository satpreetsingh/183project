<?php
	// the host where the script lives
	$proxy = "noteshare.homelinux.net";
	
	// extract oringinal url from get query
	$url = urldecode($_GET['IPS_Orig_Host']);
//	echo $url;

	// Set cookieString to the cookies sent by the user
	$cookieString = "";
	foreach($_COOKIE as $key=>$value)
	{
		$postString .= "$key=$value; ";
	}
	$postString = rtrim($postString,'; ');
	
	$cookieJar = tempnam(".","");
	
	// request and store the requested page
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$url);
	curl_setopt($cu, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_COOKIE,$cookieString);
	curl_setopt($cu,CURLOPT_COOKIEJAR,$cookieJar);
	
	// Handle POST
	if(sizeof($_POST) != 0)
	{
		curl_setopt($cu,CURLOPT_POST,true);
		curl_setopt($cu,CURLOPT_POSTFIELDS,$_POST);
	}
	
	// Execute curl
	$page = curl_exec($cu);
	curl_close($cu);
	
	// Set the cookies received in the cookie jar to user
	$temp = fopen($cookieJar,"r");
	while (!feof($temp)) 
	{
   		$line = fgets($temp);
   		
   		$cook = explode('#',$line);
   		$cook = $cook[0];
   		$cook = rtrim($cook);
   		
   		if(strlen($cook))
   		{
   			$cookie = preg_split('[\s]',$cook,null,PREG_SPLIT_NO_EMPTY);
   			setcookie($cookie[5],$cookie[6],$cookie[4],$cookie[2],$cookie[0]);
   		}
	}
	fclose($temp);
	unlink($cookieJar);

	// Create the DOM document from the page
	$document = new DOMDocument();
	@$document->loadHTML($page);

	// XPath for searching the DOM
	$xpath = new DOMXpath($document);

	// get the head
	$head = $xpath->query('/html/head');
	
	// only process documents with a head
	if($head->length != 0)
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
			
			// add the div to the body
			$body->appendChild($div);
		}
	}
	
	// echo the modified (or unmodified) doc
	$newDoc = $document->saveHTML();
	echo $newDoc;
?>
	
