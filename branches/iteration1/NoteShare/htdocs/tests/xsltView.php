<?php
function XSLTransform($xmlString,$xslFileName)
{
	$xml = new DOMDocument('1.0');
	$xml->loadXML($xmlString);

	$xslt = new XSLTProcessor();
	$xsl = new DOMDocument('1.0');
	$xsl->load( $_SERVER['DOCUMENT_ROOT'] . $xslFileName);

	$xslt->importStylesheet( $xsl );
	return str_replace( "<?xml version=\"1.0\"?>", "", $xslt->transformToXML( $xml ));

}

  echo XSLTransform( '<' . '?' . 'xml-stylesheet type="text/xsl" href="test.xsl"' . '?' . '><UserSessionList><SessionUserItem Id="11" University_Name="University of Illinois">University of Illinois - Intro to Philosophy - Fall 2009</SessionUserItem><SessionUserItem Id="4" University_Name="University of Iowa">University of Iowa - Communication Networks - Fall 2009</SessionUserItem></UserSessionList>', '../view/userHomePageView.xsl' );

?>
