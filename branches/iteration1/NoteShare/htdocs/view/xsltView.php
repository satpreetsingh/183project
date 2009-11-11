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
?>
