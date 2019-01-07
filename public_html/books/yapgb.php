<?php
//header('P3P: CP="CAO PSA OUR"');
/* If you're planning on using your YapGB guestbook from an external
 * site (e. g. within a frame), and you have problems with confirmation
 * code (when working as an image) not appearing, uncomment the line above
 * by deleting the two slashes (/) at the beginning of the header() instruction.
 */

/*
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
~= YapGB - Yet Another Php Guest Book                              =~
~= v0.7.2                                                          =~
~= Sep 10th, 2006                                                  =~
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
~= yapgb.php: main YapGB file.                                     =~
~=                                                                 =~
~= CHANGES                                                         =~
~= v0.7.2:                                                         =~
~= - Added a header() instruction at the top of this file, it is a =~
~=   "fix" to the problem in IE 6 that won't show the confirmation =~
~=   code (as image) if the guestbook was included from an external=~
~=   site. It is disabled by default, you'll need to uncomment the =~
~=   instruction in order to use it.                               =~
~= v0.7:                                                           =~
~= - Added the option to require the user to enter a confirmation  =~
~=   code. Since other "anti-spam" options didn't work completely, =~
~=   this would be the best option to prevent automated guestbook  =~
~=   signing.                                                      =~
~= - A small bug that allowed empty name/message entries has been  =~
~=   corrected.                                                    =~
~= - New error notification method, errors are shown on the sign   =~
~=   page, the script reloads it and automatically fills in the    =~
~=   data again (so entered data is not lost as before).           =~
~= v0.6.1:                                                         =~
~= - Added the creation of some session vars in sign page. These   =~
~=   variables are checked before saving a new entry. This will    =~
~=   help assure the user visited our sign page first.             =~
~= v0.6:                                                           =~
~= - Added moderation option.                                      =~
~= - Added log and debug flags (useless).                          =~
~= - Added two spam protection features:                           =~
~=   + Trusted URIs (posts are only saved if coming from a trusted =~
~=     URI).                                                       =~
~=   + Minimum "waiting" time between posts.                       =~
~= v0.5:                                                           =~
~= - Added the use of smilies and BBCode functions.                =~
~= v0.4.2:                                                         =~
~= - Improved email and url addresses checking. Now it shows the   =~
~=   appropiate message when the address is not correct.           =~
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
*/

// Debug?
//define("YAPGB_DEBUG_MODE", true);
// Log?
//define( "YAPGB_LOG_ENABLED", true );

# Disable magic quotes at runtime.
# It is used to avoid the addition of back slashes (\) to user's input.
set_magic_quotes_runtime( 0 );

# Configuration options.
require_once( "gbconfig.php" );
# Functions file.
require_once( "gbfunc.php" );
# Language file
require_once( "lang/$cfgLangFile" );

# Get action to perform and page number, it is done this way so that
# YapGB works with older versions of PHP (the $HTTP_XX_VARS array).
if ( !empty( $_GET ) ) {
	if ( isset( $_GET[ "action" ] ) )  $action = $_GET[ "action" ];
	if ( isset( $_GET[ "page" ] ) )    $page = $_GET[ "page" ];
}
else if ( !empty( $HTTP_GET_VARS ) ) {
	if ( isset( $HTTP_GET_VARS[ "action" ] ) )  $action = $HTTP_GET_VARS[ "action" ];
	if ( isset( $HTTP_GET_VARS[ "page" ] ) )  $page = $HTTP_GET_VARS[ "page" ];
}

if ( isset( $_POST["action"] ) )  $action = $_POST[ "action" ];
else if ( isset( $HTTP_POST_VARS[ "action" ] ) )  $action = $_POST[ "action" ];

# Make sure we have page number.
if ( !isset( $page ) or $page == 0 )  $page = 1;

session_start();

# Show sign form
if ( $action == "sign") {
	/* New in version 0.6.1
	 * Now we set a session var if user has visited our sign page.
	 */
	//session_start();
	$userIP = $_SERVER[ "REMOTE_ADDR" ];
	$pathTranslated = $_SERVER[ "PATH_TRANSLATED" ];
	$_SESSION[ "SIGN_PAGE_VISITED" ] = true;
	$_SESSION[ "USER_IP" ] = $userIP;
	$_SESSION[ "KEY" ] = md5( $pathTranslated );

	# Get the signing form template
	$signTemplate = gbLoadTemplate( "temp_sign.html" );
	# New in v0.7
	# Needed since temp_sign.html now uses GB_TAGS as values for
	# input fields.
	$signTemplate = gbSwapEntryGBTags( $signTemplate, -1, "", -1, "", "http://", "", false );
	# Swap global GB_TAGS
	$signTemplate = gbSwapGlobalGBTags( $signTemplate );
	
	// Confirmation code
	if ( $cfgEnableConfirmationCode ) {
		$signTemplate = gbSwapConfirmCode( $signTemplate );
	}

	# Show sign form
	echo $signTemplate;
}
# Add a new entry
else if ( $action == "add") {
	# Security and anti-spam:
	# who's calling the script?
	$referer = $_SERVER[ "HTTP_REFERER" ];
	
	# New since v0.7
	# The script does not stop on errors, rather it writes them
	# to string $error and shows them at the end.
	$error = "";

	// if trusted URIs is on
	if ( $cfgEnableTrustedURIs ) {
		// Check wheteher referer is a trusted one
		/* New in 0.6.1: referer must be non-empty, it (almost) disables the check
		 * referer feature, but I've found that some browsers do not create the
		 * HTTP_REFERER var correctly (if it does not exist, users won't be able
		 * to sign guestbook at all!)
		 */
		if ( !gbCheckReferer( $referer ) && $referer != '' ) {
			if ( defined( "YAPGB_LOG_ENABLED" ) ) gbLog( "Not trusted referer." );
			$error .= "<b>$strError:</b> $strErrorNoTrustedReferer<br />\n";
			//die( "<b>$strError:</b> $strErrorNoTrustedReferer" );
		}
		/* New in version 0.6.1
		 * HTTP_REFERER depends on user agent (it means spammers can bypass referer checks),
		 * so we can not rely on it (and also because of the "fix" above).
		 * Now we check for our session variables set when showing the signing page.
		 */
		//session_start();
		$userIP = $_SERVER[ "REMOTE_ADDR" ];
		$pathTranslated = $_SERVER[ "PATH_TRANSLATED" ];

		if ( !isset( $_SESSION[ "SIGN_PAGE_VISITED" ] )
				|| $userIP != $_SESSION[ "USER_IP" ]
				|| md5( $pathTranslated ) != $_SESSION[ "KEY" ] )
		{
			if ( defined( "YAPGB_LOG_ENABLED" ) ) gbLog( "Not coming from signing form." );
			$error .= "<b>$strError:</b> $strErrorNoTrustedReferer<br />\n";
		}
	}

	# Get entry variables
	if ( !empty( $_POST ) ) {
		if ( isset($_POST[ "name" ]) )    $name  = $_POST[ "name" ];
		if ( isset($_POST[ "email" ]) )   $email = $_POST[ "email" ];
		if ( isset($_POST[ "url" ]) )     $url   = $_POST[ "url"];
		if ( isset($_POST[ "message" ]) ) $message = $_POST[ "message" ];
		if ( isset($_POST[ "confirmCode" ]) ) $confirmCode = $_POST[ "confirmCode" ];
	}
	else if ( !empty( $HTTP_POST_VARS ) ) {
		if ( isset( $HTTP_POST_VARS[ "name" ] ) )    $name  = $HTTP_POST_VARS[ "name" ];
		if ( isset( $HTTP_POST_VARS[ "email" ] ) )   $email = $HTTP_POST_VARS[ "email" ];
		if ( isset( $HTTP_POST_VARS[ "url" ] ) )     $url   = $HTTP_POST_VARS[ "url" ];
		if ( isset( $HTTP_POST_VARS[ "message" ] ) ) $message = $HTTP_POST_VARS[ "message" ];
		if ( isset( $HTTP_POST_VARS[ "confirmCode" ] ) ) $confirmCode = $HTTP_POST_VARS[ "confirmCode" ];
	}

	//session_start();
	if ( !isset( $_SESSION[ "CONFIRM_CODE" ] ) ) {
		if ( defined( 'YAPGB_LOG_ENABLED' ) ) gbLog( "Confirmation code didn't exist." );
		$error .= "<b>$strError:</b> " . $strErrorWrongConfirmationCode .'<br />'. $strErrorNoTrustedReferer ."<br />\n";
	}
	else {
		if ( strtoupper( $confirmCode ) != $_SESSION[ 'CONFIRM_CODE' ] ) {
			//echo "Code entered: $confirmCode<br />Code required: {$_SESSION[ 'CONFIRM_CODE' ]}<br />";
			if ( defined( 'YAPGB_LOG_ENABLED' ) ) gbLog( "Wrong confirmation code" );
			$error .= "<b>$strError:</b> ". $strErrorWrongConfirmationCode ."<br />\n";
		}
	}

	// current time
	$date = time();

	// Enable access to session vars
	// If user has already posted, check whether he/she can post again
	if ( isset( $_SESSION["LASTPOST_TIME"] ) ) {
		if ( $date - $_SESSION[ "LASTPOST_TIME" ] <= $cfgSecondsToWait ) {
			if ( defined( "YAPGB_LOG_ENABLED" ) ) gbLog( "User tried to post again. Current time: $date, last post: ".$_SESSION["LASTPOST_TIME"] );
			$error .= "<b>$strError:</b> $strErrorAlreadyPosted<br />\n";
		}
	}

	# [ Bug corrected]: 'bad users' were able to post entries
	# with empty name/message fields.
	# Name and message are required, check whether they are empty.
	if ( !isset( $name ) or empty( $name ) ) {
		$error .= "<b>$strError:</b> $strErrorNameRequired<br />\n";
	}
	if ( !isset( $message ) or empty( $message ) ) {
		$error .= "<b>$strError:</b> $strErrorMessageRequired<br />\n";
	}

	# Email checking. First we check whether the email field is
	# not empty, if so, it does not matter if email is not
	# required, we check for a correct email address and if it is
	# not, we show an error message.
	# Changes since v0.7: check first if email is required
	if ( $cfgRequireEmail ) {
		if ( empty( $email ) ) {
			$error .= "<b>$strError:</b> $strErrorEmailRequired<br />\n";
		}
		else if ( !gbCheckEmail( $email ) ) {
			$error .= "<b>$strError:</b> $strErrorEmailIncorrect<br />\n";
		}
	}
	
	# Url checking.
	# Valid url addresses could be:
	# http://www.hewop.com/~yapgb
	# www.hewop.com/~yapgb (the script will add "http://" at the start)
	# If url field is not empty and it is not equal to just "http://"
	# Since v0.7: check first whether url is required
	if ( $cfgRequireUrl ) {
		if ( empty( $url ) ) {
			$error .= "<b>$strError:</b> $strErrorUrlRequired<br />\n";
		}
		else if ( !gbCheckUrl( $url ) ) {
			if ( gbCheckUrl( "http://$url" ) ) {
				$url = "http://$url";
			}
			else {
				$error .= "<b>$strError:</b> $strErrorUrlIncorrect<br />\n";
			}
		}
	}
	
	# If there were errors
	if ( !empty( $error ) ) {
		$signTemplate = gbLoadTemplate( "temp_sign.html" );
		if ( get_magic_quotes_gpc() ) {
			$name = stripslashes( $name );
			$email = stripslashes( $email );
			$url = stripslashes( $url );
			$message = stripslashes( $message );
		}
		// Put back info entered before by the user
		$signTemplate = gbSwapEntryGBTags( $signTemplate, -1, $name, -1, $email, $url, $message, false );
		$signTemplate = gbSwapGlobalGBTags( $signTemplate );
		
		if ( $cfgEnableConfirmationCode ) {
			$signTemplate = gbSwapConfirmCode( $signTemplate );
		}

		// Let the user know about the errors
		$signTemplate = str_replace( "<!--GB_SIGNERRORS-->", $error, $signTemplate );		
		// Show sign form and exit.
		die( $signTemplate );
	}

	# Format fields. These functions need revision.
	$name = gbCleanField( $name );
	$email = gbCleanField( $email );
	$url = gbCleanField( $url );
	$message = gbCleanMessage( $message );

	# Format new entry. I'm planning on changing the date format.
	$newEntry = $name."|".$date."|".$email."|".$url."|".$message."|[end]";

	# If moderate entries is enabled, save new entry to moderation file.
	$file = 	$cfgEnableModeration ? $cfgModerateFile : $cfgEntriesFile;

	$fp = fopen( $file, "a" ) or die( $strErrorOpeningFile );
	fwrite( $fp, $newEntry."\n" );
	fclose( $fp );

	// Remember when user posted
	$_SESSION[ "LASTPOST_TIME" ] = $date;

	// and unset our sign page session vars
	unset( $_SESSION[ "SIGN_PAGE_VISITED" ] );
	unset( $_SESSION[ "USER_IP" ] );
	unset( $_SESSION[ "KEY" ] );
	unset( $_SESSION[ "CONFIRM_CODE" ] );

	# If notify of new post enabled.
	if ( $cfgNewPostNotify ) {
		gbNewPostNotify( $name, $email, $url, $message );
	}

	/* New in version 0.6.1
	 * If $cfgShowSignSuccesful is set to 0, go to main page.
	 * If it is set to 1, load a temp_succesful.html page and
	 * show it to the user.
	 */
	if ( !$cfgShowSuccesfulPage ) {
		header( "Location: $cfgGBIndex" );
		exit();
	}
	else {
		$succesfulPage = gbLoadTemplate( "temp_signsuccesful.html" );
		$succesfulPage = gbSwapEntryGBTags( $succesfulPage, -1, $name, $date, $email, $url, $message, false );
		$succesfulPage = gbSwapGlobalGBTags( $succesfulPage );
		die( $succesfulPage );
	}
}
# Generate confirmation code image
else if ( $action == 'genimg' ) {
	// Code based on Edward Eliot's article at:
	// http://www.sitepoint.com/article/toughen-forms-security-image
	// A 'captcha' class of his creation may be used in a future version.

	//session_start();

	$width = 100;
	$height = 20;
	$lines = 30;	// Number of lines to draw as "noise"
	$chars = 4;	// Number of chars in confirmation code
	$spacing = $width / $chars;
	
	// Create image
	$image = imagecreate( $width, $height );
	$white = imagecolorallocate( $image, 255, 255, 255 );
	$black = imagecolorallocate( $image, 0, 0, 0 );
	
	// Draw some noise
	for ( $i = 0; $i < $lines; $i++ ) {
		$randColor = rand( 190, 250 );
		$lineColor = imagecolorallocate( $image, $randColor, $randColor, $randColor );
		imageline( $image, rand( 0, $width ), rand( 0, $height ), rand( 0, $width ), rand( 0, $height ), $lineColor );
	}
	
	// Draw a black rectangle around image
	imagerectangle( $image, 0, 0, $width - 1, $height - 1, $black );
	// Draw the code
	$code = $_SESSION[ 'CONFIRM_CODE' ];
	for( $i = 0; $i < strlen( $code ); $i++ ) {
		// Random font (3, 4 or 5. Internal GD fonts)
		$font = rand( 3, 5 );
		$randColor = rand( 0, 128 );
		$textColor = imagecolorallocate( $image, $randColor, $randColor, $randColor );
		$x = $spacing / 3 + $i * $spacing;
		$y = ( $height - imagefontheight( $font ) ) / 2;
		imagestring( $image, $font, $x, $y, $code[ $i ], $textColor );
	}

	// Output correct headers
	header( 'Content-type: image/png' );

	// These headers avoid saving the image to cache	
	header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
	header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" ); 
	
	// Finally, output the result
	imagepng( $image );
	// and free our resources
	imagedestroy( $image );
}
# View entries
else {
	# Read entries file into an array.
	$text = file( $cfgEntriesFile );
	# How many entries we have.
	$lines = count( $text );

	$entryTemplate = gbLoadTemplate( "temp_message.html" );

	# Most recent post first.
	if ( $cfgNewestPostFirst ) {
		$limitValue = $lines - $page * $cfgEntriesPerPage;

		for ( $i = $limitValue + $cfgEntriesPerPage - 1; ( $i >= $limitValue ) && ( $i >= 0 ); $i-- ) {
			list( $name, $date, $email, $url, $message ) = explode( "|", $text[ $i ] );

			# Censoring functions.
			$name = gbSwapBadWords( $name );
			$name = gbCutLongWords( $name ); // New call in 0.6.2
			//$email = gbSwapBadWords($email);
			//$url = gbSwapBadWords($url);
			$message = gbSwapBadWords( $message );
			$message = gbCutLongWords( $message );

			# If hide email option is enabled.
			# Not executed here anymore, it is done in the gbSwapEmailUrlLinks() function
			//if ( $cfgHideEmail ) $email = gbHideEmail($email);

			# If BBCode is enabled.
			if ( $cfgEnableBBCode ) $message = gbSwapBBCode( $message );
			# If smilies are enabled.
			if ( $cfgEnableSmilies ) $message = gbSwapSmilies( $message );
			$entries .= $entryTemplate;
			# Swap entry GB_TAGS
			$entries = gbSwapEntryGBTags( $entries, $i, $name, $date, $email, $url, $message, false );
		}
	}
	# Oldest post first.
	else {
		$limitValue = $page * $cfgEntriesPerPage;
		for ( $i = $limitValue - $cfgEntriesPerPage; $i < $limitValue; $i++ ) {
			list( $name, $date, $email, $url, $message ) = explode( "|", $text[ $i ] );
			$name = gbSwapBadWords( $name );
			$message = gbSwapBadWords( $message );
			if ( $cfgHideEmail ) $email = gbHideEmail( $email );

			$entries .= $entryTemplate;
			$entries = gbSwapEntryGBTags( $entries, $i, $name, $date, $email, $url, $message, false );
		}
	}

	# Load main page template.
	$bodyContent = gbLoadTemplate( "temp_body.html" );
	# Swap global GB_TAGS
	$bodyContent = gbSwapGlobalGBTags( $bodyContent );
	# Put entries into main page.
	$bodyContent = str_replace( '<!--GB_ENTRIES-->', $entries, $bodyContent );
	#Pagination
	$bodyContent = gbSwapPaginationGBTags( $bodyContent, $lines, $page );
	
	# Show main page
	echo $bodyContent;
}

session_write_close();
?>