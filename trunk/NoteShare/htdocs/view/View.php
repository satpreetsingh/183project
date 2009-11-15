<?php
/**
 * View.php
 *
 * Contains all of the basic view functions.
 *
 * Last Modified:	11/09/09
 * Notes:		Initial Creation
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
    echo '<xml version="1.0" encoding="UTF-8">
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
          <html xmlns="http://www.w3.org/1999/xhtml"
            xmlns:fb="http://www.facebook.com/2008/fbml">
            <head>
              <title>' . $title . '</title>
              <script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"
                type="text/javascript"></script>
              <link rel="stylesheet" type="text/css" href="/view/noteshare.css" />
              <script type="text/javascript">
                function initPage() {
                  FB_RequireFeatures(["XFBML"],
                function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112",
                  "xd_receiver.htm"); });
                }
              </script>
            </head>
            <body onload="initPage(); return false;">';
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
	      echo '<a href="http://apps.facebook.com/notesharesep/'
        . $links[ $i ] . '" target="iframe_canvas" class="fbFont">' . $titles[ $i ] . '</a>';
        echo ' | ';
      }
      echo '<a href="http://apps.facebook.com/notesharesep/'
           . $links[ $i ] . '" target="iframe_canvas" class="fbFont">' . $titles[ $i ] . '</a>';
    }

    echo '</td></tr></table>';
    echo '<br><br>';
  }

  /**
   * Prints the generic header information for a file based off the passed
   * parameters.  It is expected that some form of css will handle all of the
   * necessary styling.  It is also expected that the view has already
   * connected to Facebook via the SessionController.
   *
   * @param string[] navChain array of navigation strings from main page down
   *
  **/
  function genHeadingBar( $title )
  {
    echo '<table><tr><td class="headingBar">' . $title . '</td></tr></table>';
  }
?>
