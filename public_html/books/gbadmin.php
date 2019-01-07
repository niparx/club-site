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
~= gbadmin.php: YapGB administration.                              =~
~=                                                                 =~
~= CHANGES                                                         =~
~= v0.6:                                                           =~
~= - Renamed this file from admin.php to gbadmin.php               =~
~= - Added admin login interface.                                  =~
~= - Added moderation queue administration                         =~
~=   (not finished but usable right now).                          =~
~= v0.5.1:                                                         =~
~= - Small correction: date was not acquired with register_globals =~
~=   disabled.                                                     =~
~= v0.5:                                                           =~
~= - Improved security when deleting entries. Now the date field   =~
~=   is checked so that it corresponds to the one being deleted.   =~
~=   This way we avoid undesired behavior when two or more people  =~
~=   have access to the admin page.                                =~
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
*/
set_magic_quotes_runtime(0);

require("gbconfig.php");
require_once("gbfunc.php");
include_once("lang/$cfgLangFile");

if ( !empty($_POST) ) {
	if ( isset($_POST["action"]) )  $action = $_POST["action"];
}
else if ( !empty($HTTP_POST_VARS) ) {
	if ( isset($HTTP_POST_VARS["action"]) )  $action = $HTTP_POST_VARS["action"];
}

if ( !empty($_GET) ) {
	if ( isset($_GET["action"]) )  $action = $_GET["action"];
}
else if ( !empty($HTTP_GET_VARS) ) {
	if ( isset($HTTP_GET_VARS["action"]) )  $action = $HTTP_GET_VARS["action"];
}

// Enable access to session vars
session_start();

# If admin is logged in, go to the administration interface.
if ( isset( $_SESSION[ "SESSION_ADMINLOGGED" ] ) || isset( $HTTP_SESSION_VARS[ "SESSION_ADMINLOGGED"] ) ) {
	if ( !empty( $_POST ) ) {
		if ( isset($_POST["id"]) ) $id = $_POST["id"];
		if ( isset($_POST["date"]) ) $date = $_POST["date"];
	}
	else if ( !empty($HTTP_POST_VARS) ) {
		if ( isset($HTTP_POST_VARS["id"]) ) $id = $HTTP_POST_VARS["id"];
		if ( isset($HTTP_POST_VARS["date"]) ) $date = $HTTP_POST_VARS["date"];
	}

	if ( !empty( $_GET ) ) {
		if ( isset( $_GET["id"] ) ) $id = $_GET[ "id" ];
		if ( isset( $_GET["date"] ) ) $date = $_GET[ "date" ];
	}
	else if ( !empty( $HTTP_GET_VARS ) ) {
		if ( isset( $HTTP_GET_VARS[ "id"] ) ) $id = $HTTP_GET_VARS[ "id" ];
		if ( isset( $HTTP_GET_VARS[ "date" ] ) ) $date = $HTTP_GET_VARS[ "date" ];
	}

	switch ( $action ) {
		case "logout":
			// Delete session information
			unset( $_SESSION[ "SESSION_ADMINLOGGED" ] );
			unset( $_SESSION[ "SESSION_ERROR" ] );
			// PHP 4.0.6 and previous compatibility
			if ( !empty( $HTTP_SESSION_VARS ) ) {
				unset( $HTTP_SESSION_VARS[ "SESSION_ADMINLOGGED" ] );
				unset( $HTTP_SESSION_VARS[ "SESSION_ERROR" ] );
			}
			/*
			Not recomended by PHP documentation
			session_unregister( "SESSION_ADMINLOGGED" );
			session_unregister( "SESSION_ERROR" );
			*/
			// Go to admin login page
			header( "Location: gbadmin.php" );
			// You can go directly to YapGB's main page, just comment
			// above instruction and uncomment next one:
			// header( "Location: $cfgGBIndex" );
		break;
    case "delete":
        if (!isset($id) or !isset($date)) {
          die("$strErrorNothingToDelete");
        }
        else
          gbDeleteEntry($cfgEntriesFile, $id, $date);
      break;
    case "modify":
      if ( !isset($id) )
        die("$strError: Nothing to modify!<br>Please <a href=\"javascript:history.go(-1);\">go back.</a> and try again.");

      $fp = fopen($cfgEntriesFile, "r") or die("$strError: $strErrorOpeningFile (03)<br>");
      fseek($fp, 0);
      $content = fread($fp, filesize($cfgEntriesFile));
      fclose($fp);

      $text = explode("\n", $content);

      $contents = gbLoadTemplate("temp_modify.html");

      list($name, $date, $email, $url, $message) = explode("|", $text[$id]);
      $message = str_replace("<br />", "\n", $message);

      $contents = gbSwapEntryGBTags($contents, $id, $name, $date, $email, $url, $message, false);
      $contents = gbSwapGlobalGBTags($contents);

      echo $contents;
      break;
    case "update":
      # Exit if id is not set.
      if ( !isset($id) )
        die("$strError: Nothing to modify!<br>Please <a href=\"javascript:history.go(-1);\">go back.</a> and try again.");

      $fp = fopen($cfgEntriesFile, "r") or die("$strError: $strErrorOpeningFile (04)");
      fseek($fp, 0);
      $content = fread($fp, filesize($cfgEntriesFile));
      fclose($fp);

      $text = explode("\n", $content);
      $lines = count($text);

      if ( !empty($_POST) ) {
        if ( isset($_POST["name"]) )
        $name = $_POST["name"];
        if ( isset($_POST["email"]) )
          $email = $_POST["email"];
        if ( isset($_POST["url"]) )
          $url = $_POST["url"];
        if ( isset($_POST["message"]) )
          $message = $_POST["message"];
      }
      else if ( !empty($HTTP_POST_VARS) ) {
        if ( isset($HTTP_POST_VARS["name"]) )
          $name = $HTTP_POST_VARS["name"];
        if ( isset($HTTP_POST_VARS["email"]) )
          $email = $HTTP_POST_VARS["email"];
        if ( isset($HTTP_POST_VARS["url"]) )
          $url = $HTTP_POST_VARS["url"];
        if ( isset($HTTP_POST_VARS["message"]) )
          $message = $HTTP_POST_VARS["message"];
      }

      $name = gbCleanField($name);
      $email = gbCleanField($email);
      $url = gbCleanField($url);
      $message = gbCleanMessage($message);

      $modEntry = $name."|".$date."|".$email."|".$url."|".$message."|[end]";

      $text[$id] = $modEntry;

      $fp = fopen($cfgEntriesFile, "w") or die("$strError: $strErrorOpeningFile (05)");
      for ($i = 0; $i < $lines - 1; $i++)
        fputs($fp, "$text[$i]\n");

      fclose($fp);

      echo "Message with id = $id was updated succesfully!.<br>\n<a href=\"$cfgGBIndex\">$strGoToBook</a>\n";
      break;
    case "viewPending": {
      echo "Pending messages interface (NOT READY YET!)";
      $text = file($cfgModerateFile);
      $lines = count($text);

      $entryTemplate = gbLoadTemplate("temp_message.html");
          $limitValue = $lines - $page * $cfgEntriesPerPage;

      for ($i = 0; $i < $lines; $i++) {
        list($name, $date, $email, $url, $message) = explode("|", $text[$i]);

        # Censoring functions.
        $name = gbSwapBadWords($name);
        //$email = gbSwapBadWords($email);
        //$url = gbSwapBadWords($url);
        $message = gbSwapBadWords($message);

        $message = gbCutLongWords($message);

        # If hide email option is enabled.
        if ( $cfgHideEmail ) $email = gbHideEmail($email);
        # If BBCode is enabled.
        if ($cfgEnableBBCode) $message = gbSwapBBCode($message);
        # If smilies are enabled.
        if ($cfgEnableSmilies) $message = gbSwapSmilies($message);

        $entries .= $entryTemplate;
        $entries .= "<a href=\"gbadmin.php?action=approvePending&id=<!--GB_ENTRYID-->&date=<!--GB_ENTRYDATEINT-->\">Approve</a> "
          ."<a href=\"gbadmin.php?action=deletePending&id=<!--GB_ENTRYID-->&date=<!--GB_ENTRYDATEINT-->\">Delete</a> "
          ."<hr><br><br>";

        # Swap entry GB_TAGS
        define("MODE_MODERATION", true);
        $entries = gbSwapEntryGBTags($entries, $i, $name, $date, $email, $url, $message, true);
    }
      $bodyContent = "<html>"
        ."<head>"
        ."<title>Messages waiting for approval</title>"
        ."<link rel=\"styleSheet\" href=\"themes/$cfgTheme/style.css\" type=\"text/css\">"
        ."</head>"
        ."<body>"
        ."<p><p><p>"
        ."<hr><center><!--GB_ENTRIES-->"
        ."<br><br><br>"
        ."<!--GB_INFO--></center>"
        ."</body>"
        ."</html>";

      # Swap global GB_TAGS
      $bodyContent = gbSwapGlobalGBTags($bodyContent);
      # Put entries into main page.
      $bodyContent = str_replace("<!--GB_ENTRIES-->", $entries, $bodyContent);
      # Pagination
      # TO BE DONE...
      $bodyContent = gbSwapPaginationGBTags($bodyContent, $lines, $page);

      # Show main page
      echo $bodyContent;
      break;
    }
    case "approvePending":
      if (!isset($id) or !isset($date)) die("ERROR");

      $text = file($cfgModerateFile);
      $entry = $text[$id];

      gbDeleteEntry($cfgModerateFile, $id, $date);

      $fp = fopen($cfgEntriesFile, "a");
      fwrite($fp, $entry);
      fclose($fp);
      echo "Approve message $id";
      break;
    case "deletePending":
      if (!isset($id) or !isset($date)) die("ERROR");

      echo "DELETING...<br><br>";
      gbDeleteEntry($cfgModerateFile, $id, $date);
      break;
    case "modifyPending":
      if (!isset($id)) die("ERROR");
      echo "Modify pending message $id";
      break;
    case "updatePending":
      if ( !empty($_GET) ) {
        if ( isset($_GET["id"]) ) $id = $_GET["id"];
      }
      if (!isset($id)) die("ERROR");
      gbDeleteEntry($cfgModerateFile, $id, $date);
      break;
    case "showAdminPanel":
    	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n"
    		."\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";

    	echo "<html>\n"
			."<head\n"
			."<title>$cfgTitle - Administration</title>\n"
			."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n"
			."<link rel=\"styleSheet\" href=\"themes/".( file_exists("themes/$cfgTheme/style.css") ? "$cfgTheme" : "melody1" )."/style.css\" type=\"text/css\" />\n"
			."<head>\n"
			."<body>\n"
			."<br />\n"
			."<table cellpadding=\"3\" cellspacing=\"3\">\n"
			."<tr><th class=\"title\">Admin menu</th></tr>\n"
			."<tr><td>\n"
			."&middot; <a href=\"$cfgGBIndex\" target=\"adminMain\" title=\"View guestbook entries\">View guestbook</a><br />\n"
			."&middot; <a href=\"gbadmin.php?action=viewPending\" target=\"adminMain\" title=\"View posts waiting for approval\">Pending posts</a><br />\n"
			."&middot; <a href=\"gbadmin.php?action=logout\" target=\"_top\" title=\"Log out\">Log out</a><br />\n"
			."</td></tr>\n"
			."</table>\n"
			."</body>\n"
			."</html>\n";
			break;
    default:
      if ( !empty($_GET) ) {
        if (isset($_GET["id"])) $id = $_GET["id"];
      }
      else if ( !empty($HTTP_GET_VARS) ) {
        if ( isset($HTTP_GET_VARS["id"]) ) $id = $HTTP_GET_VARS["id"];
      }

      if ( isset($id) ) {
        $text = file($cfgEntriesFile);
        list($name, $date, $email, $url, $message) = explode("|", $text[$id]);

        $adminContent = gbLoadTemplate("temp_admin.html");
        $adminContent = gbSwapEntryGBTags($adminContent, $id, $name, $date, $email, $url, $message, false);
        $adminContent = gbSwapGlobalGBTags($adminContent);

        echo $adminContent;
      }
      else { // No action but admin is logged in
        ?>
        <html>
        <head><title>Administration</title></head>
        <frameset cols="150,650">
          <frame src="gbadmin.php?action=showAdminPanel" name="adminMenu">
          <frame src="yapgb.php" name="adminMain">
        </frameset>
        <?php
      }
  }
}
# If admin is not logged in:
else {
  switch ($action) {
    case "login":
        if ( !empty($_POST) ) {
          if ( isset($_POST["adminName"]) ) $adminName = $_POST["adminName"];
        }
        else if ( !empty($_POST) ) {
          if ( isset($HTTP_POST_VARS["adminName"]) ) $adminName = $HTTP_POST_VARS["adminName"];
        }
        if ( !empty($_POST) ) {
          if ( isset($_POST["adminPass"]) ) $adminPass = $_POST["adminPass"];
        }
        else if ( !empty($_POST) ) {
          if ( isset($HTTP_POST_VARS["adminPass"]) ) $adminPass = $HTTP_POST_VARS["adminPass"];
        }
        
        if ($adminName == $cfgAdminName and $adminPass == $cfgAdminPass) {
        // password is correct, register session variables and go to administration panel
        $_SESSION[ "SESSION_ADMINLOGGED" ] = true;
        unset( $_SESSION[ "SESSION_ERROR" ] );
        
        $HTTP_SESSION_VARS[ "SESSION_ADMINLOGGED" ] = true;
        if ( !empty( $HTTP_SESSION_VARS ) ) unset( $HTTP_SESSION_VARS[ "SESSION_ERROR" ] );
        
        /* NOT RECOMMENDED (PHP documentation)
        session_register("SESSION_ADMINLOGGED");
        session_unregister("SESSION_ERROR");
        */
        
          header( "Location: gbadmin.php" );
          
          //echo "ADMIN LOGGED SUCCESSFULLY";
          //echo "<a href=\"gbadmin.php\">Go to admin interface</a>";
        }
        else {
        	$_SESSION[ "SESSION_ERROR" ] = true;
        	$HTTP_SESSION_VARS[ "SESSION_ERROR" ] = true;
          // session_register("SESSION_ERROR");
          header("Location: gbadmin.php");
        }
      break;
    default: // Show admin login form
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
	<title><?php echo "$cfgTitle - Administration"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="styleSheet" href="themes/<?echo file_exists("themes/$cfgTheme/style.css") ? "$cfgTheme" : "melody1"; ?>/style.css" type="text/css" />
</head>

<body>
<br /><br /><br />
<hr width="60%" /><br />
<h2 align="center"><?php echo $cfgTitle ?></h2>
<h3 align="center">Admin login</h3>
<table align="center">
	<form action="gbadmin.php" method="post">
	<input type="hidden" name="action" value="login" />
	<?php
		if ( isset( $_SESSION[ "SESSION_ERROR" ] ) || isset( $HTTP_SESSION_VARS[ "SESSION_ERROR" ] ) ) {
			echo "<tr>\n";
			echo "<td colspan=\"2\" bgcolor=\"red\"><font color=\"white\">$strErrorWrongPassword</font></td>\n";
			echo "</tr>\n";
		}
    ?>
  <tr>
    <td>Admin name:</td>
    <td><input type="text" name="adminName" /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="password" name="adminPass" /></td>
  </tr>
  <tr>
    <td align="center" colspan="2"><br><input type="submit" name="submit" value="Login" /></td>
  </tr>
  </form>
</table>
<br /><hr width="60%" />
<center>
<?php echo gbYapGBInfo(); ?>
</center>
</body>
</html>
<?php
  }
}
?>