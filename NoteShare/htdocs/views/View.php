<?php
/**
 * View.php
 *
 * Contains all of the basic view functions.
 *
 * Last Modified: 11/14/09
 * Notes:         Expanded genHeadingBar functionality to include options for
 *                  links within the heading bar.
 * Modified:	    11/09/09
 * Notes:		      Initial Creation
**/

  /**
   * Prints the header information for the view page.  Contained within this
   *  header is the HTML to:
   *  --Declare the document type
   *  --Link to the CSS
   *  --Include the script to use XFBML
   *  --Set the Title of the Page (doesn't seem to do anything though)
   *  --Open the body tag for the page
   *
   * @param string $title Title of the web page
   * @return the page header
  **/
  function genViewHeader( $title )
  {
    echo "<xml version=\"1.0\" encoding=\"UTF-8\">\n" .
         "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n" .
         "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:fb=\"http://www.facebook.com/2008/fbml\">\n" .
         "  <head>\n" .
         "    <title>" . $title . "</title>\n" .
         "    <link rel=\"stylesheet\" type=\"text/css\" href=\"/views/noteshare.css\">\n" .
         "  </head>\n" .
         "  <body>\n" .
         "    <script src=\"http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php\" type=\"text/javascript\"></script>\n" .
         "    <script type=\"text/javascript\">\n" .
         "      FB_RequireFeatures([\"XFBML\", \"CanvasUtil\"], function()\n" .
         "      {\n" .
         "        FB.Facebook.init( \"20f5b69813b87ffd25e42744b326a112\", \"/xd_receiver.html\");\n" .
         "        FB.XdComm.Server.init(\"/xd_receiver.htm\");\n" .
         "        FB.CanvasClient.startTimerToSizeToContent();\n" .
         "        FB.CanvasClient.scrollTo( 0, 0 );\n" .
         "      });\n" .
         "    </script>\n";
  }

  /**
   * Prints the generic header information for a file based off the passed
   * parameters.  It is expected that some form of css will handle all of the
   * necessary styling.  It is also expected that the view has already
   * connected to Facebook via the SessionController.
   *
   * @param string[] $titles titles of the nav links,
   *                         in order from main page to current page
   * @param string[] $links  web addresses of the nav links
   *                          in order from main page to current page
   *
  **/
  function genPageHeader( $titles, $links )
  {
    echo '<table>' .
         '  <tr><td class="title fbFont">NoteShare (Beta)</td></tr>' .
         '  <tr><td class="headingBar fbFont">';

    if( sizeof( $titles ) == sizeof( $links ))
    {
      $upperBound = sizeof( $titles ) - 1;
      for( $i = 0; $i < $upperBound; $i += 1 )
      {
	      echo '<a href="' . $links[ $i ] . '" target="iframe_canvas" class="fbFont">' . $titles[ $i ] . '</a>';
        echo ' | ';
      }
      echo '<a href="' . $links[ $i ] . '" target="iframe_canvas" class="fbFont">' . $titles[ $i ] . '</a>';
    }

    echo '</td></tr></table>';
    echo '<br><br>';
  }

  /**
   * Prints the generic header information for a file based off the passed
   * parameters.  It is expected that some form of css will handle all of the
   * necessary styling.  Header bar will use the class td.headingBar .
   *
   * @param string $title Title of the heading bar
   * @param string $link_text Text of the link to display (optional)
   * @param string $link_href Address to use for the link (optional)
   * @version 1.0
   * @return A Facebook Style Heading bar
  **/
  function genHeadingBar( $title, $link_text=null, $link_href=null )
  {
    if( $link_text )
    {
      echo '<table cellspacing="0" cellpadding="0">' .
           '  <tr>' .
           '    <td class="headingBar">' . $title . '</td>' .
           '    <td class="headingBar right">[ <a href="' . $link_href . '" target="iframe_canvas" class="fbFont">' . $link_text . '</a> ]</td>' .
           '  </tr>' .
           '</table>';
    }
    else
    {
      echo '<table><tr><td class="headingBar">' . $title . '</td></tr></table>';
    }
  }

  /**
   * Handles the XSLT Transformations between XML to HTML.
   *
   * @param XML $xmlString XML to transform
   * @param FileName $xslFileName XSL file to use ( in /views/xsl/ )
   * @return Converted XML as HTML
  **/
  function XSLTransform( $xmlString, $xslFileName)
  {
    if( !$xmlString )
    {
      return "BAD OR EMPTY XML STRING.";
    }
    else
    {
	    $xml = new DOMDocument('1.0');
	    $xml->loadXML($xmlString);

  	  $xslt = new XSLTProcessor();
  	  $xsl = new DOMDocument('1.0');
  	  $xsl->load( $_SERVER['DOCUMENT_ROOT'] . 'views/xsl/' . $xslFileName);

	    $xslt->importStylesheet( $xsl );
  	  return str_replace( "<?xml version=\"1.0\"?>", "", $xslt->transformToXML( $xml ));
    }
  }
?>
