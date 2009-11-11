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
  function genHeader( $titles, $links )
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
        . $links[ $i ] . '" target="_top" class="fbFont">' . $titles[ $i ] . '</a>';
        echo ' | ';
      }
      echo '<a href="http://apps.facebook.com/notesharesep/'
           . $links[ $i ] . '" target="_top" class="fbFont">' . $titles[ $i ] . '</a>';
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
