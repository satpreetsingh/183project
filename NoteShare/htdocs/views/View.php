<?php
/**
 * View.php
 *
 * Contains all of the basic view functions for generating a page header,
 *  individual section headings, form posts, and the XSL translations.
 *
 * Last Modified: 12/07/09
 * Notes:         Updated genPageHeader to include a help link
 *
 * Modified:      11/23/09
 * Notes:         Moved genForm functions from the add course page into this
 *                  generic view.
 *
 * Modified:      11/14/09
 * Notes:         Expanded genHeadingBar functionality to include options for
 *                  links within the heading bar.
 *
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
   * @version 2.0
   * @return the page header
  **/
  function genViewHeader( $title )
  {
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
         "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n" .
         "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:fb=\"http://www.facebook.com/2008/fbml\">\n" .
         "  <head>\n" .
         "    <title>" . $title . "</title>\n" .
         "    <link rel=\"stylesheet\" type=\"text/css\" href=\"/views/noteshare.css\">\n" .
         "  </head>\n" .
         "  <body>\n";
  }

  /**
   * Prints out a generic view's footer which includes the necessary Facebook
   *  javascript for XFBML.
   *
   * @version 2.0
   * @return HTML, view footer
  **/
  function genViewFooter()
  {
    echo "    <script src=\"http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php\" type=\"text/javascript\"></script>\n" .
         "    </script>\n" .
         "    <script type=\"text/javascript\">\n" .
         "      FB_RequireFeatures([\"XFBML\", \"CanvasUtil\"], function()\n" .
         "      {\n" .
         "        FB.Facebook.init( \"20f5b69813b87ffd25e42744b326a112\", \"/xd_receiver.html\");\n" .
         "        FB.XdComm.Server.init(\"/xd_receiver.htm\");\n" .
         "        FB.CanvasClient.set_timerInterval( \"20\");\n" .
         "        FB.CanvasClient.startTimerToSizeToContent();\n" .
         "        FB.CanvasClient.scrollTo( 0, 0 );\n" .
         "      });\n" .
         "    </script>\n" .
         "  </body>\n" .
         "</html>\n";
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
   *                          (paths relative from document root)
   * @param string $help  anchor to help section. Defaults to none.
   *
  **/
  function genPageHeader( $titles, $links, $help="")
  {
    echo '<table cellspacing="0" cellpadding="0">' .
         '  <tr><td class="title fbFont">NoteShare (Beta)</td></tr>' .
         '  <tr><td class="headingBar fbFont">';

    if( sizeof( $titles ) == sizeof( $links ))
    {
      $upperBound = sizeof( $titles ) - 1;
      for( $i = 0; $i < $upperBound; $i += 1 )
      {
	      echo '<a href="http://apps.facebook.com/notesharesep' . $links[ $i ] . '" target="_top" class="fbFont">' . $titles[ $i ] . '</a>';
        echo ' | ';
      }
      echo '<a href="http://apps.facebook.com/notesharesep' . $links[ $i ] . '" target="_top" class="fbFont">' . $titles[ $i ] . '</a>';
    }

    echo '</td><td class="headingBar right fbFont"><a href="http://noteshare.homelinux.net/views/UserManual.php" target="_top">Help</a></td></tr></table>';
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
           '    <td class="headingBar right">[ <a href="http://apps.facebook.com/notesharesep' . $link_href . '" target="_top" class="fbFont">' . $link_text . '</a> ]</td>' .
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

  function formatXmlString($xml) {
    // add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
    $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);   /**/

    // now indent the tags
    $token      = strtok($xml, "\n");
    $result     = ''; // holds formatted version as it is built
    $pad        = 0; // initial indent
    $matches    = array(); // returns from preg_matches()

    // scan each line and adjust indent based on opening/closing tags
    while ($token !== false) :
      // test for the various tag states
      // 1. open and closing tags on same line - no change
      if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
        $indent=0;
      // 2. closing tag - outdent now
      elseif (preg_match('/^<\/\w/', $token, $matches)) :
        $pad--;
      // 3. opening tag - don't pad this one, only subsequent tags
      elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
        $indent=1;
      // 4. no indentation needed
      else :
        $indent = 0;
      endif;

      // pad the line with the required number of leading spaces
      $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
      $result .= $line . "\n"; // add to the cumulative result, with linefeed
      $token   = strtok("\n"); // get the next token
      $pad    += $indent; // update the pad size for subsequent lines
    endwhile;

    return $result;
  }
?>
