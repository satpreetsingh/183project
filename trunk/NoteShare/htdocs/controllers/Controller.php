<?php
/*-----------------------------------------------------------------------------
 File:         Controller.php
 Description:  A generic controller which all other controllers must include.
               This provides the session instance for access to the facebook
                 Api and XFBML.
 UseCases:     <Later>
 Requirements: <Later>
 Components:   <Later>
-------------------------------------------------------------------------------
 Last Modified: N.Fritze
 Modified On:   12/01/09
 Notes:         Adding in parameters to ensure that only enrolled users can
                view content associated with that session or study group. 

 Modified:      N.Fritze
 Modified On:   11/28/09
 Notes:         Combined Session Controller code into a generic controller that
                  includes methods to alter XML if needed. 

 Modified:      N.Fritze
 Modified On:   10/20/09
 Notes:         Quick initial creation of session controller.
-----------------------------------------------------------------------------*/

  require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';

  // API key for our application, needed for facebook session
  $appapikey = '20f5b69813b87ffd25e42744b326a112';

  // Secret key that's also needed for a facebook session
  $appsecret = '9c30a702413dccd1856b58d2fab4c992';

  // Create the facebook session
  $facebook = new Facebook($appapikey, $appsecret, true);

  // Require that the user be logged in to use the page
  $user_id = $facebook->require_login();

  /**
   * Appends tags with specified values to the parent tag of an existing XML
   * document.
   * @param array strings $tags   Contains the tags to insert as XML
   * @param array strings $values Contains the values to insert as XML
   * @param XML $xml              the XML to insert into
   * @param string $parentTag     Name of the parent tag to insert under
   *
   * @return XML on success
   *         -1 on null parameter
   *         -2 on tag/values size mismatch
   *         -3 on missing or multiple parent tags
  **/
  function insertXMLTags( $tags, $values, $xml, $parentTag )
  {
    // check for null parameters
    if( $tags == NULL || $values == NULL || $xml == NULL || $parentTag == NULL )
    {
      return -1;
    }
  
    // check for dimension mismatch
    $upperBound = sizeof( $tags );
    if( $upperBound < 1 || sizeof( $values ) != $upperBound )
    {
      return -2;
    }

    // create temporary DOM document
    $tempDOM = new DOMDocument('1.0');
    $tempDOM->loadXML( $xml );

    // check for parent tag existance or multiple tags
    $parentTag  = $tempDOM->getElementsByTagName( $parentTag );
    if( $parentTag == null || $parentTag->length != 1 )
    {
      return -3;
    }
    //NOTE: Originally, the tag is returned as a list.  Here we grab the actual
    //      tag object itself.
    $parentTag = $parentTag->item(0);

    // load tags into XML
    for( $i = 0; $i < $upperBound; $i++ )
    {
      $tempElement = $tempDOM->createElement( $tags[ $i ] );
      $tempValue = $tempDOM->createTextNode( $values[ $i ] );
      $tempElement->appendChild( $tempValue );
      $parentTag->insertBefore( $tempElement, $parentTag->firstChild );
    }

    return $tempDOM->saveXML();
  }
?>
