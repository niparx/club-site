<?php
/*
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
~= YapGB - Yet Another Php Guest Book                              =~
~= v0.7.2                                                          =~
~= Sep 16th, 2006                                                  =~
~= http://yapgb.sourceforge.net/                                   =~
~=-----------------------------------------------------------------=~
~= AUTHOR:                                                         =~
~=    José Jorge Enríquez Rodríguez                                =~
~=    jenriquez@users.sf.net (redirected email)                    =~
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
~= YapGB is a free guestbook system written in PHP that does not   =~
~= require a database, entries are stored in a plain text file.    =~
~= Copyright (C) 2003-2005 José Jorge Enríquez Rodríguez           =~
~=                                                                 =~
~= This program is free software; you can redistribute it and/or   =~
~= modify it under the terms of the GNU General Public License     =~
~= as published by the Free Software Foundation; either version 2  =~
~= of the License, or (at your option) any later version.          =~
~=                                                                 =~
~= This program is distributed in the hope that it will be useful, =~
~= but WITHOUT ANY WARRANTY; without even the implied warranty of  =~
~= MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   =~
~= GNU General Public License for more details.                    =~
~=                                                                 =~
~= You should have received a copy of the GNU General Public       =~
~= License along with this program; if not, write to the Free      =~
~= Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, =~
~= MA  02111-1307, USA                                             =~
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
~= gbfunc.php: YapGB utility functions.                            =~
~=                                                                 =~
~= CHANGES                                                         =~
~= v0.7.2:                                                         =~
~= - Bug fixed: allowed HTML tags were not working at all, because =~
~=   the gbCleanMessage had not been updated to use the correct    =~
~=   $cfgAllowedTags configuration variable (it was using the      =~
~=   previous one).                                                =~
~= - Updated gbSwapEmailUrlLinks to use the new $cfgShowEmailLink  =~
~=   and $cfgShowUrlLink configuration variables instead of the    =~
~=   previously used $cfgShowLinksOnlyToAdmin variable.            =~
~= v0.7.1:                                                         =~
~= - Fixed a bug when showing confirmation code as text (when GD   =~
~=   library is not available), it was not shown by the script.    =~
~= v0.7:                                                           =~
~= - Added the gbSwapConfirmCode( $string ) function. When         =~
~=   $cfgEnableConfirmationCode in gbconfig.php is set to 1, it    =~
~=   replaces the new <!--GB_CONFIRCODE--> tag for a 3 characters  =~
~=   confirmation code that the user will be required to enter in  =~
~=   order to sign the guestbook.                                  =~
~= - Added gbGenerateCode() function.                              =~
~= v0.6:                                                           =~
~= - Renamed this file from func.php to gbfunc.php.                =~
~= - Fixed javascript injection flaw in BBcode implementation.     =~
~=   Fix by Burkhard Masseida                                      =~
~=   <reisswolf_nospam [AT] otzenpunkrock.de>                      =~
~= - All functions are now "gb" prefixed.                          =~
~= - Added some functions: gbSwapEntryAdminLink(),                 =~
~=   gbSwapEmailUrlLinks(), gbEchoPage() (not used yet),           =~
~=   gbDeleteEntry(), gbCheckReferer() and gbLog().                =~
~= - Added a parameter to gbSwapEntryGBTags to indicate whether    =~
~=   it is in moderation mode.                                     =~
~= - Added gbGetLatestEntries(n), it returns an array with the     =~
~=   latest n entries.                                             =~
~= v0.5:                                                           =~
~= - Modified swapSmilies a bit: the smilies array is now located  =~
~=   in config.php.                                                =~
~= - Added a condition to improve the pagination function. Thanks  =~
~=   to Dan Edwards for the correction.                            =~
~= - Function readTemplate now gets only the file name and not the =~
~=   full path as parameter.                                       =~
~= v0.4.2:                                                         =~
~= - Modified swapDateTags() function to work with GMT dates so    =~
~=   that it is possible to customize guestbook's timezone.        =~
~= - Added the include("config.php"); at the beginning of file so  =~
~=   that $gbIndex variable is declared before using it in the     =~
~=   direct access to file checking.                               =~
~= - Added the newPostNotify function for notifying admin of new   =~
~=   entries.                                                      =~
~= - Modified checkEmail() and checkUrl functions, the if-else     =~
~=   instructions were redundant.                                  =~
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
*/
if ( eregi( "gbfunc.php", $_SERVER[ "PHP_SELF" ] ) ) {
  header( "location: gbconfig.php" );
  exit();
}

// include configuration options
include( "gbconfig.php" );

/* function gbLoadTemplate($tempFile)
 * Loads (and returns) the content of a template file.
 */
function gbLoadTemplate($tempFile) {
  global $cfgTheme;

  # If specified $tempFile exists in current theme, use it,
  if ( file_exists("themes/$cfgTheme/$tempFile") )
    $templateFile = "themes/$cfgTheme/$tempFile";
  # if not, use the one in melody1 theme (melody1 is the "default" theme).
  else
    $templateFile = "themes/melody1/$tempFile";

  $fp = fopen($templateFile, "r");
  fseek($fp, 0);
  $templateData = fread($fp, filesize($templateFile));
  fclose($fp);

  return $templateData;
}

# function gbCheckEmail($email)
# Returns whether $email is a valid email address.
function gbCheckEmail($email) {
  return preg_match("/([\w\.\-]+)(\@[\w\.\-]+)(\.[a-z]{2,4})+/i", $email);
}

# function gbCheckUrl($url)
# Returns whether $url is a valid url address.
function gbCheckUrl($url) {
  return preg_match("#^http://[_a-z0-9-]+\\.[_a-z0-9-]+#i", $url);
}

# function gbCutLongWords($string)
# Searches for every word in $string that is longer than the
# allowed $gbMaxWordLenght.
function gbCutLongWords($string) {
  global $cfgMaxWordLenght;

  if ($cfgMaxWordLenght <= 0) return $string;

  // Make sure all break lines are <br /> tags.
  // <br /> -> XHTML standard.
  // <br> -> HTML
  // All browers recognize both, but users could enter whatever they
  // want :).
  $string = str_replace("<br>", "<br />", $string);

  $lines = explode("<br />", $string);

  // For every line
  for ($i = 0; $i < sizeof($lines); $i++) {
    $words = explode(" ", $lines[$i]);
    // for every word
    for ($j = 0; $j < sizeof($words); $j++) {
      if ( strlen($words[$j]) > $cfgMaxWordLenght ) {

        if ( defined("YAPGB_DEBUG_MODE") ) echo "<p><b>Original:</b> <font color=\"red\">$words[$j]</font><br />";

        if ( preg_match("/(\[url=(.+?)\])(.+)/i", $words[$j], $matches) ) {
          //echo "<p>$words[$j]</p>";
          foreach($matches as $matchWord) {
            //echo "<p>$matchWord</p>";
          }
          //$words[$j] = "[url=".gbChopLongWords($matches[1])."]".gbChopLongWords($matches[2]);
        }

//		Some testing for choping url links within posts.
//        if ( preg_match("/(\[url=(.+?)\])(.+?)/i", $words[$j]) ) {
//          $words[$j] = preg_replace("/(\[url=(.+?)\])(.+?)(\[\/url\])/i", gbChopLongWords("\\2"), $words[$j]);
//          $words[$j] = preg_replace("/(\[url=(.+?)\])(.+?)/i", gbChopLongWords("\\2"), $words[$j]);
//        }
//        else if ( preg_match("/(.+?)(\[\/url\])/i", $words[$j]) ) {
//          $words[$j] = preg_replace("/(.+?)(\[\/url\])/i", gbChopLongWords("\\1"), $words[$j]);
//        }
        else {
          $words[$j] = substr($words[$j], 0, $cfgMaxWordLenght) . "...";
        }
        if ( defined("YAPGB_DEBUG_MODE") ) echo "<b>Result:</b> <font color=\"red\">$words[$j]</font></p>";
      }
    }
    $lines[$i] = implode(" ", $words);
  }
  $result = implode("<br />", $lines);

  return $result;
}

# function cleanField($string)
# This function cleans not desired stuff from $string.
# It is used to adequate name, email and url fields when
# adding a new entry to YapGB.
function gbCleanField($string) {
  $string = trim($string);
  # strip_tags replaces() htmlspecialchars() so that
  # the name field allows special HTML characters of the form &xchar;
  $string = strip_tags($string, "");
  // Entry field separator
  $string = str_replace("|", "&brvbar;", $string);

  if ( get_magic_quotes_gpc() )  $string = stripslashes($string);

  return $string;
}

# function cleanMessage($string)
# Similar to cleanField($string), but this one is used
# to adequate the message field since it needs some different
# 'treatment' ;).
function gbCleanMessage($string) {
  global $cfgAllowedHTML;
  $string = trim($string);
  $string = strip_tags($string, $cfgAllowedHTML);
  $string = str_replace("|", "&brvbar;", $string);
  $string = str_replace("\n", "<br />", $string);
  $string = str_replace("\r", "", $string);

  if ( get_magic_quotes_gpc() )  $string = stripslashes($string);

  return $string;
}

// Note: in future versions it'd be more secure
// to write email to an image.
function gbHideEmail($email) {
  global $cfgHideEmailAt, $cfgHideEmailDot;

  $email = str_replace( "@", $cfgHideEmailAt, $email );
  $email = str_replace( ".", $cfgHideEmailDot, $email );

  return $email;
}

function gbSwapBadWords($string) {
  global $cfgBadWords;

  $totalBadWords = sizeof($cfgBadWords);

  for ($i = 0; $i < $totalBadWords; $i++) {
    $banned = substr($cfgBadWords[$i], 0, 1);
    for ($j = 1; $j < strlen($cfgBadWords[$i]); $j++) {
      $banned .= "*";
    }
    $string = str_replace($cfgBadWords[$i], $banned, $string);
  }
  return $string;
}

function gbSwapGlobalGBTags($string) {
  global $cfgTitle, $cfgGBIndex, $cfgSiteIndex, $cfgAllowedHTML;

  $allowedHTML = $cfgAllowedHTML == "" ? " none. " : $cfgAllowedHTML;

  $string = str_replace("<!--GB_TITLE-->", $cfgTitle, $string);
  $string = str_replace("<!--GB_INDEX-->", $cfgGBIndex, $string);
  $string = str_replace("<!--GB_SITEINDEX-->", $cfgSiteIndex, $string);
  $string = str_replace("<!--GB_ALLOWEDTAGS-->", "<!--GB_ALLOWEDHTML-->", $string);
  $string = str_replace("<!--GB_ALLOWEDHTML-->", htmlspecialchars($allowedHTML), $string);

  $string = str_replace("<!--GB_INFO-->", gbYapGBInfo(), $string);

  return $string;
}

function gbSwapEntryAdminLink($string, $id, $moderationFile) {
  global $cfgTheme;
  global $strAdminModifyDelete;

  session_start();
  if ( session_is_registered("SESSION_ADMINLOGGED") ) {
  	if ( file_exists("themes/$cfgTheme/gbadminlink.gif") )
  		$image = "themes/$cfgTheme/gbadminlink.gif";
  	else $image = "themes/$cfgTheme/gbadminlink.png";

    if ( file_exists($image) ) {
      $link = "<img src=\"$image\" align=\"middle\" border=\"0\" alt=\"Edit entry\" title=\"Edit entry\" title=\"$strAdminModifyDelete\" />";
    }
    else {
      $link = $strAdminModifyDelete;
    }

    if ( !$moderationFile )
      $adminLink = "<a href=\"gbadmin.php?id=$id\">$link</a>";

    $string = str_replace("<!--GB_ENTRYADMINLINK-->", $adminLink, $string);
  }

  return $string;
}

/* Updated in 0.7.2
 * Email and URL links are enabled separately.
 */
function gbSwapEmailUrlLinks($string, $name, $email, $url) {
  global $cfgTheme, $cfgHideEmail;
  //Previous:
  //global $cfgShowLinksOnlyToAdmin;
  //Now:
  global $cfgShowEmailLink;
  global $cfgShowUrlLink;

	// If only admin can see email and url links
	/*
	if ( $cfgShowLinksOnlyToAdmin ) {
		session_start();
		// If admin not logged, return without doing anything.
		if ( !session_is_registered( 'SESSION_ADMINLOGGED' ) )
			return $string;
	}
	*/
	
  if ( gbCheckEmail($email) && $cfgShowEmailLink != 0 ) {
  		
	if ( $cfgHideEmail ) $email = gbHideEmail( $email );

	if ( file_exists("themes/$cfgTheme/gbemail.gif") )
		$emailImage = "themes/$cfgTheme/gbemail.gif";
	else $emailImage = "themes/$cfgTheme/gbemail.png";

    if ( file_exists($emailImage) ) {
      $linkText = "<img src=\"$emailImage\" align=\"middle\" border=\"0\" title=\"Send an email to $name\" />";
    }
    else
      $linkText = $email;

    $emailLink = "<a href=\"mailto:$email\">$linkText</a>";
  }

  if ( gbCheckUrl($url) && $cfgShowUrlLink != 0 ) {
  	if ( file_exists( "themes/$cfgTheme/gburl.gif" ) )
  		$urlImage = "themes/$cfgTheme/gburl.gif";
  	else
  		$urlImage = "themes/$cfgTheme/gburl.png";

    if ( file_exists($urlImage) ) {
      $linkText = "<img src=\"$urlImage\" align=\"middle\" border=\"0\" title=\"Visit $name's homepage\" />";
    }
    else
      $linkText = $url;

    $urlLink = "<a href=\"$url\" target=\"_blank\">$linkText</a>";
  }
	  
  $string = str_replace("<!--GB_ENTRYEMAILLINK-->", $emailLink, $string);
  $string = str_replace("<!--GB_ENTRYURLLINK-->", $urlLink, $string);

  return $string;
}

function gbSwapEntryGBTags($string, $id, $name, $date, $email, $url, $message, $moderationMode) {
  # Not used anymore, use <!--GB_ENTRYADMINLINK--> instead.
  $string = str_replace("<!--GB_ENTRYID-->", $id, $string);
  $string = gbSwapEntryAdminLink($string, $id, $moderationMode);

  $string = gbSwapEmailUrlLinks($string, $name, $email, $url);

  $string = str_replace("<!--GB_ENTRYEMAILLINK-->", $emailLink, $string);
  $string = str_replace("<!--GB_ENTRYURLLINK-->", $uriLink, $string);

  $string = str_replace("<!--GB_ENTRYNAME-->", $name, $string);
  $string = str_replace("<!--GB_ENTRYEMAIL-->", $email, $string);
  $string = str_replace("<!--GB_ENTRYURL-->", $url, $string);
  $string = str_replace("<!--GB_ENTRYMESSAGE-->", $message, $string);

  $string = gbSwapDateGBTags($string, $date);

  return $string;
}

function gbSwapDateGBTags($string, $date) {
  global $cfgGMTOffset;

  // Changed it before setting the time offset since it needs to
  // keep unchanged.
  $string = str_replace("<!--GB_ENTRYDATEINT-->", $date, $string);

  $timeOffset = 3600 * $cfgGMTOffset;
  $date += $timeOffset;

  $string = str_replace("<!--GB_ENTRYDAY-->", gmstrftime("%a", $date), $string);
  $string = str_replace("<!--GB_ENTRYDAYN-->", gmstrftime("%d", $date), $string);
  $string = str_replace("<!--GB_ENTRYMONTH-->", gmstrftime("%b", $date), $string);
  $string = str_replace("<!--GB_ENTRYMONTHN-->", gmstrftime("%m", $date), $string);
  $string = str_replace("<!--GB_ENTRYYEAR-->", gmstrftime("%y", $date), $string);
  $string = str_replace("<!--GB_ENTRYTIME-->", gmstrftime("%X", $date), $string);
  $string = str_replace("<!--GB_ENTRYDATEFULL-->", gmstrftime("%c", $date), $string);

  # New date GB_TAGS
  $string = str_replace("<!--GB_ENTRYDAYFULL-->", gmstrftime("%A", $date), $string);
  $string = str_replace("<!--GB_ENTRYMONTHFULL-->", gmstrftime("%B", $date), $string);
  $string = str_replace("<!--GB_ENTRYYEARFULL-->", gmstrftime("%Y", $date), $string);

  return $string;
}

// Ouputs confirmation code to sign page.
function gbSwapConfirmCode( $string ) {
	global $cfgGBIndex;
	global $cfgConfirmationCodeAsImage;
	session_start();
	
	// If code has not been created yet
	if ( !session_is_registered( 'CONFIRM_CODE' ) ) {
		$code = gbGenerateCode();
		// Store it to current sesion
		$_SESSION[ 'CONFIRM_CODE' ] = $code;
	}
	// Small correction in v0.7.1: else clause added.
	// $code variable was not properly set when
	// GD library is not available.
   else {
      $code = $_SESSION[ 'CONFIRM_CODE' ];
   }
	
	// Show it
	if ( function_exists( gd_info ) && $cfgConfirmationCodeAsImage ) {
		// If GD library is available
		$imageLink = "<img src=\"$cfgGBIndex?action=genimg\" />";
		$string = str_replace( '<!--GB_CONFIRMCODE-->', $imageLink, $string );
	}
	else {
		gbLog( "GD library not available, confirmation code will be shown as text" );
		// If GD library is not installed, show it as text (unsecure).
		$newCode = '';
		for( $i = 0; $i < strlen( $code ); $i++ ) {
			switch( rand( 0, 2 ) ) {
				case 0:
					$newCode .= "<em>$code[$i]</em>";
					break;
				case 1:
					$newCode .= "<font size=\"+1\">$code[$i]</font>";
					break;
				case 2:
					$newCode .= "<font size=\"-1\">$code[$i]</font>";
					break;
			}
		}
		$string = str_replace( '<!--GB_CONFIRMCODE-->', $newCode, $string );		
	}

	return $string;
}

// Generate a length characters random code.
// It uses only uppercase letters (not numbers).
function gbGenerateCode( $length = 4 ) {
	$code = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$code .= chr( rand( 65, 90 ) );
	}
	return $code;
}

function gbSwapPaginationGBTags($string, $totalEntries, $pageCurrent) {
  global $cfgGBIndex, $cfgEntriesPerPage;
  global $strNext, $strPrev;

  # I've changed pagination algorithm a bit,
  # it is not commented, make sure to understand
  # it if you're planning to edit it.
  # Please let me know if you know of a better way for
  # managing pagination links.

  if ($pageCurrent != 1) {
    $pagePrev = $pageCurrent - 1;
    $pagination = "<a href=\"$cfgGBIndex?page=$pagePrev\" class=\"pagin\">$strPrev</a>&nbsp;";
  }
  else {
    $pagination = "$strPrev&nbsp;";
  }

  /*
  $numOfPages = $totalEntries / $cfgEntriesPerPage;
  $lastPage = ceil($numOfPages);
  $pageLimit = 10;
  */
  /* CORRECTION: the final number in $numOfPages was being omitted,
   * the if condition below makes it work correctly.
   * Thanks to Dan Edwards for the correction :).
   */
  if ($totalEntries > $cfgEntriesPerPage and ($totalEntries % $cfgEntriesPerPage) != 0) {
    $numOfPages = ($totalEntries+$cfgEntriesPerPage) / $cfgEntriesPerPage;
  }
  else {
    $numOfPages = ($totalEntries) / $cfgEntriesPerPage;
  }
  $lastPage = ceil($numOfPages);
  $pageLimit = 10;


  if ($pageCurrent > 10) {
    $startPage = $pageCurrent - 10;
    $endPage = $startPage + $pageLimit;
    if ($startPage != 1)
      $pagination .= "<a href=\"$cfgGBIndex?page=1\" class=\"pagin\">1</a>&nbsp;..&nbsp;";
  }
  else {
    $startPage = 1;
    $endPage = $pageLimit;
  }

  for ($i = $startPage; $i <= $endPage and $i <= $numOfPages; $i++) {
    if ($i == $pageCurrent)
        $pagination .= "<b>$i</b>&nbsp;";
    else {
        $pagination .= "<a href=\"$cfgGBIndex?page=$i\" class=\"pagin\">$i</a>&nbsp;";
    }
  }

  if ($pageCurrent < $lastPage and $lastPage > 10)
    $pagination .= "..&nbsp;<a href=\"$cfgGBIndex?page=$lastPage\" class=\"pagin\">$lastPage</a>&nbsp;";
  else {
    if ($lastPage > 10)
      $pagination .= "<b>$lastPage</b>&nbsp;";
  }

  if ( ($totalEntries - ($cfgEntriesPerPage * $pageCurrent)) > 0 ) {
    $pageNext = $pageCurrent + 1;
    $pagination .= "<a href=\"$cfgGBIndex?page=$pageNext\" class=\"pagin\">$strNext</a>&nbsp;";
  }
  else
    $pagination .= "$strNext ";

  $paginationInfo = "Showing ".$limitValue." to ".$cfgEntriesPerPage*$page." from a total of ".$totalEntries;

  $string = str_replace("<!--GB_PAGINATION-->", $pagination, $string);

  return $string;
}

# Notify admin when a new entry has been added.
function gbNewPostNotify($name, $email, $url, $message) {
  global $cfgNotifyFrom, $cfgNotifyTo, $cfgNotifySubject, $cfgNotifyContent;
  global $cfgModeratePosts;
  global $strError, $strErrorMailFuncDisabled;

  $notifyFrom = str_replace("<!--GB_ENTRYEMAIL-->", $email, $cfgNotifyFrom);
  $emailContent = $cfgNotifyContent;
  # Replacing entry GB_TAGS (note that date can not be used here).
  $emailContent = str_replace("<!--GB_ENTRYNAME-->", $name, $emailContent);
  $emailContent = str_replace("<!--GB_ENTRYEMAIL-->", $email, $emailContent);
  $emailContent = str_replace("<!--GB_ENTRYURL-->", $url, $emailContent);
  $emailContent = str_replace("<!--GB_ENTRYMESSAGE-->", $message, $emailContent);

  # Swapping global GB_TAGS.
  $emailContent = gbSwapGlobalGBTags($emailContent);

  # Send the notify email, it will work only if the PHP function mail()
  # is available. If it is not, you can disable the notify option by setting
  # $gbNewPostNotify variable to 0 in config.php
  if ( defined( 'YAPGB_LOG_ENABLED' ) )
  	gbLog( "Notifying of new post..." );
	
  if (!mail($cfgNotifyTo, $cfgNotifySubject, $emailContent, "From: " . $notifyFrom)) {
	  if ( defined( 'YAPGB_LOG_ENABLED' ) )
	  	gbLog( "Could not send notification. mail() function not available!" );
	  
	  if ( defined( 'YAPGB_DEBUG_MODE' ) )
	  	echo "$strError: $strErrorMailFuncDisabled<br />";
  }
  else {
      
      if ( defined( 'YAPGB_LOG_ENABLED' ) )
	  	gbLog( "Notification was succesfully sent to $cfgNotifyTo." );
        
      if ( defined( 'YAPGB_DEBUG_MODE' ) )
	  	echo "Notification email was succesfully sent.";
	
  }

}

/* function gbSwapBBCode( $string )
 * Replaces emoticons within $string with a nice image.
 * This is Burkhard Masseida's version (thank you so much!)
 * Burkhard Masseida's changes:
 * - fixed javascript injection flaw in yapgb bbcode implementation
 * - uncommented [img]
 * - added max-width to [img]
 *   max-width is not recognized by many browsers, so you may want
 *   to set { overflow: hidden; } in the appropriate container
 * - restricted [img] to gifs, pngs and jp(e)gs
 *   preventing unconvenient formats like xbm, bmp, tiff, etc. which may not be supported
 *   by all browsers, nor by w3c, and whose display libs may not as hardened as the
 *   standard libs are. As a side effect this will rule out some kind of generated web bug
 *   stuff without proper file extension.
 * - added [strong] and [em] because it was easy ;-)
 * - code cleanup: improve readability by removing unnecessary parens
*/
function gbSwapBBCode( $string ) {
  // Bold, italics and underlined text.
  $string = preg_replace( "/\[b\](.+?)\[\/b\]/i", "<b>\\1</b>", $string );
  $string = preg_replace( "/\[i\](.+?)\[\/i\]/i", "<i>\\1</i>", $string );
  $string = preg_replace( "/\[u\](.+?)\[\/u\]/i", "<u>\\1</u>", $string );
  $string = preg_replace( "/\[strong\](.+?)\[\/strong\]/i", "<strong>\\1</strong>", $string );
  $string = preg_replace( "/\[em\](.+?)\[\/em\]/i", "<em>\\1</em>", $string );

  // Images
  $string = preg_replace( "/\[img\]([^\"]+?\.(png|gif|jpe?g))\[\/img\]/i", '<img src="\\1" alt="\\1" style="max-width: 100%;">', $string );

  // Email links
  $string = preg_replace( "/\[email\]([^\"]+?)\[\/email\]/i", '<a href="mailto:\\1">\\1</a>', $string );
  $string = preg_replace( "/\[email=([^\"]+?)\](.+?)\[\/email\]/i", '<a href="mailto:\\1">\\2</a>', $string );

  $string = preg_replace( "/\[url=?\]([^\"]+?)\[\/url\]/i", '<a href="\\1" target="_blank">\\1</a>', $string );
// merged the following line into the previous
//  $string = preg_replace("/\[url=\]([^\"]+?)\[\/url\]/i", '<a href="\\1" target="_blank">\\1</a>', $string);
  $string = preg_replace( "/\[url=([^\"]+?)\](.+?)\[\/url\]/i", '<a href="\\1" target="_blank">\\2</a>', $string );

  return $string;
}

# function swapSmilies($string)
# This function will swap smilies such as :), :P, :(, etc. for
# a corresponding image indicated by the $gbSmilies array in
# config.php.
# Images are stored into a smilies/ folder.
function gbSwapSmilies($string) {
  global $cfgSmilies, $cfgSmiliesPath;

  while ( list($emoticon, $data) = each($cfgSmilies) ) {
    $imgTag = "<img src=\"" . $cfgSmiliesPath . $data[0] . "\" alt=\"$data[1]\" title=\"$data[1]\" border=\"0\">";
    $string = str_replace($emoticon, $imgTag, $string);
  }
  reset($cfgSmilies);

  return $string;
}

function gbYapGBInfo() {
  global $strPoweredBy;
  global $cfgYapGBVersion;

  $string = "<font style=\"font-family:Arial; font-size:9.5px\"><b>";
  $string .= isset($strPoweredBy) ? $strPoweredBy : "Powered By";
  $string .= "</b> "
    ."<a href=\"http://yapgb.sourceforge.net\" target=\"_blank\" title=\"YapGB - Yet Another PHP Guest Book\">YapGB</a> $cfgYapGBVersion</b>\n"
    ."</font>\n";

  return $string;
}

function gbDeleteEntry($file, $entryID, $entryDate) {
  global $cfgEntriesFile, $cfgLangFile, $cfgGBIndex;
  include ("lang/$cfgLangFile");

  if ( !isset($entryID) )
    die("$strError: $strErrorNothingToDelete");

  if ( !isset($entryDate) )
    die("$strError: $strErrorDateNotReceived");

  $fp = fopen($file, "r") or die("$strError: Data file not found! (01)<br>");
  fseek($fp, 0);
  $content = fread($fp, filesize($file));
  fclose($fp);
  $text = explode("\n", $content);

  $lines = count($text) - 1;

  $entry = explode("|", $text[$entryID]);
  if ( $entryDate != $entry[1]) {
    echo "date: $entryDate<br>date: $entry[1] - $entry[0]<hr>";
    die("$strError: $strDateNotMatch");
  }
  if ( $entryID > $lines - 1 ) {
    die("$strError: Entry does not exist anymore.");
  }

  # Move all entries after $id one place up
  # ($id becomes $id's next)
  for ($i = $entryID; $i < $lines - 1; $i++) {
    $text[$i] = $text[$i + 1];
  }

  # Save the new entries array.
  $fp = fopen($file, "w") or die("$strError: $strErrorOpeningFile");

  for ($i = 0; $i < $lines - 1; $i++)
    fputs($fp, "$text[$i]\n");

  fclose($fp);

  echo "$strEntryDeleted<br><br>\n"
    ."<a href=\"$cfgGBIndex\" target=\"adminMain\">$strGoToBook.</a><br>\n";
/*
  if ( session_is_registered("SESSION_ADMINLOGGED") ) {
    echo "<a href=\"gbadmin.php\">$strGoToAdministration</a><br>";
  }
*/
}

/* function gbCheckReferer( $referer )
 * Returns whether $referer is in a trusted URIs list.
 */
function gbCheckReferer( $referer ) {
	global $cfgTrustedURIs;
	
	foreach ( $cfgTrustedURIs as $trusted ) {
		if ( eregi( $trusted, $referer ) ) return true;
	}

	return false;
}

function gbLog( $message ) {
	global $cfgLogFile;
	$logFile = $cfgLogFile;
	
	$logMessage = "------------------------------\n"
				."Generated on: ". date( "M/d/y H:i:s", time() ) ."\n"
				."User agent: ".$_SERVER[ "HTTP_USER_AGENT" ]."\n"
				."Accept language: ".$_SERVER[ "HTTP_ACCEPT_LANGUAGE" ]."\n"
				."Remote address: ".$_SERVER[ "REMOTE_ADDR" ]."\n"
				."Referer: ".$_SERVER[ "HTTP_REFERER" ]."\n"
				."Log message: $message\n";	
	
	$fp = fopen( $logFile, "a" );
	fwrite( $fp, $logMessage );
	fclose( $fp );
}

?>