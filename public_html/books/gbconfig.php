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
~= gbconfig.php: YapGB configuration.                              =~
~=                                                                 =~
~= CHANGES                                                         =~
~= v0.7.2:
~= - Replaced $cfgShowLinksOnlyToAdmin configuration variable for  =~
~=   $cfgShowEmailLink and $cfgShowUrlLink to allow show/hide both =~
~=   or just one of them as prefered.
~= v0.7:                                                           =~
~= - Added $cfgEnableConfirmationCode configuration variable. If   =~
~=   set to 1, the new <!--GB_CONFIRMCODE--> in temp_sign.html     =~
~=   be replaced by a 3 characters confirmation code that the user =~
~=   will be required to enter in order to sign the guestbook.     =~
~= - Added $cfgShowLinksOnlyToAdmin configuration variable. If set =~
~=   to 1, entries' email and url links are shown only when the    =~
~=   administrator is logged in.                                   =~
~= v0.6.1:                                                         =~
~= - Added $cfgShowSuccesfulPage configuration variable. If set to =~
~=   1, a sign succesful page will be showed after signing the     =~
~=   guestbook.                                                    =~
~= v0.6:                                                           =~
~= - Renamed this file from config.php to gbconfig.php.            =~
~= - Changed variables prefix from $gb to $cfg.                    =~
~= - Added some configuration variables:                           =~
~=   $cfgGBURI, $cfgEnableTrustedURIs, $cfgTrustedURIs,            =~
~=   $cfgLangFile, $cfgSecondsToWait, $cfgEnableModeration,        =~
~=   $cfgModerationFile and $cfgYapGBVersion instead of $gbInfo.   =~
~= v0.5:                                                           =~
~= - Added configuration variables for enabling/disabling BBCode   =~
~=   and smilies.                                                  =~
~= - Changed $gbAllowedTags to $gbAllowedHTMLTags, but GB_TAG      =~
=    keeps the same: GB_ALLOWEDTAGS for compatibility.             =~
~= v0.4.2:                                                         =~
~= - Added configuration variables for customizing guestbook's     =~
~=   timezone and for notifying administrator of new entries.      =~
~=   There are five new configuration variables: $gbNewPostNotify, =~
~=   $gbNotifyTo, $gbNotifyFrom, $gbNotifyText and $gbGMTOffset.   =~
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
*/

# This is used so that nobody can see the contents of this file.
# You should change yapgb.php to whatever page you want to
# redirect the user when trying to access this file.
if ( eregi( "gbconfig.php", $_SERVER[ "PHP_SELF" ] ) ) {
	header( "location: yapgb.php" );
	exit();
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~#
# YapGB configuration variables #
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~#

##### Administrator data #####

# Admin's login nick/name
$cfgAdminName = "admin";

# Admin's login password
$cfgAdminPass = "adminpass";

# Admin's email (NOT USED YET)
$cfgAdminEmail = "gera@irzhi.com";

#### Guestbook configuration #####

# Language file (located in lang/ directory)
# Remember it includes only system messages (mainly errors), if you
# want the full interface to be translated, edit your theme files.
$cfgLangFile = "gblang_en.php";

# Guestbook's title
$cfgTitle = "Sveciu knyga FK &quot;ROTALIS&quot;";

# Text file for storing entries
# Remember it needs read/write permissions
$cfgEntriesFile = "entries.txt";

# Main YapGB file
# You can rename the file to whatever you want (e. g. guestbook.php),
# just remember to edit this variable too.
$cfgGBIndex = "yapgb.php";

# NEW #
# Full URL to YapGB index file
# IMPORTANT: Make sure this URL is correct!
# For example:
# - If YapGB is located in guestbook/ directory of your web space, you
#   can set $cfgGBURI using $cfgGBIndex and $cfgSiteIndex variables:
#   $cfgGBURI = "$cfgSiteIndex/guestbook/yapgb.php";
#   or just type in the URL:
#   $cfgGBURI = "http://yoursite.com/guestbook/yapgb.php";
$cfgGBURI = "$cfgSiteIndex/$cfgGBIndex";

# Your site's URL
# Used by the "back to site" link
$cfgSiteIndex = "http://vvkure.com/rotalis/";

# Theme defining the appearance of YapGB
$cfgTheme = "melody1";

# NEW #
# If set to 1 (default), just URIs in $cfgTrustedURIs will be allowed
# to post to guestbook.
# I'm planning on making this the default behaviour, but this variable
# lets you disable it, just in case it doesn't work for you ;).
# NEW in 0.6.2
# If you enable it but still get spam, disable it and enable
# $cfgEnableConfirmationCode (at the bottom of this file).
$cfgEnableTrustedURIs = 0;

# NEW #
# A list of trusted URLs.
# New posts will only be saved if coming from one of these when
# $cfgEnableTrustedURIs is set to 1.
# Note that the first trusted URL is $cfgGBURI, the full URL to
# your YapGB index file. This is why it's so important that $cfgGBURI
# is correct, if not, the guestbook won't work.
# This configuration variable will help prevent remote spam scripts attacks.
# Normally, you don't need to edit this configuration variable.
$cfgTrustedURIs = array(
	$cfgGBURI
);

# NEW #
# Seconds to wait before user can add a new post again, e.g.:
# don't wait = 0
# 1 minute = 60
# 1 hour = 3600
# 1 day = 86400
# Added to prevent "manual" spam attacks.
$cfgSecondsToWait = 20;

# How many entries will be showed per page
$cfgEntriesPerPage = 10;

# If set to 1, latest entry is at the top
# If set to 0, the first entry appears at the top and the latest at the end
$cfgNewestPostFirst = 1;

# Allowed HTML tags in posts
# The list of HTML tags that are allowed in the guestbook.
# No closing tags are needed, since they are included automatically.
# I'd recommend you not to add anything else, but you could delete
# whatever you want :). If you want to disable HTML tags, just leave
# it blank, like this:
# $cfgAllowedHTML = "";
# In general, HTML tags are not allowed in guestbooks and message
# boards, it's a matter of security, since someone could use them
# to harm your site (specially using script languages such as
# Javascript).
$cfgAllowedHTML = "<i><b><u>";

# A value of 1 means that the email field is required (if the user
# doesn't provide it, he/she will be prompted to do so)
# 0 means it is not required.
$cfgRequireEmail = 0;

# 1 = an URL address is required, 0 = it is not required.
$cfgRequireUrl = 0;

##### Email spam protection #####
# This option helps you protect your visitors against spam bots
# searching the Internet for email addresses.
# When it is 0, email addresses will show normally (example@domain.com),
# but when this configuration variable is turned on (that is,
# it has a value of 1), the email addresses will be seen as
# example [at] domain [dot] com
$cfgHideEmail = 1;

# The text that replaces the '@' in email addresses when $cfgHideEmail is turned on.
$cfgHideEmailAt = " [at] ";

# This will replace the dots '.' in email addresses when $cfgHideEmail is turned on.
$cfgHideEmailDot = " [dot] ";

##### Moderation queue #####
# NOTE: this feature is experimental, use it only for testing purposes (I have tried it
# a bit and it seems to work, but not sure about it yet).
# If set to 1, posts won't appear on guestbook until approved by the administrator
# If set to 0, users cant post to the guestbook without need of approval
$cfgEnableModeration = 0;
# Text file where entries will be saved when $cfgModerateEntries is on.
# This file will requires read and write permissions.
$cfgModerateFile = "gbmod.txt";


# Array containing censored words.
# NOTE: at this moment these words are stored in the entries file
# without being censored, they are censored just when showing guest
# book entries. This is to prevent data loss if the censoring
# function does not work correctly.
$cfgBadWords = array(
	" ass ",
	" bitch ",
	" clit",
	" cock ",
	" c0ck ",
	" cum ",
	" cunt ",
	" fucking ",
	" fuck ",
	" fuking ",
	" fuk ",
	" penis ",
	" shit ",
	" viagra "
);

# Maximum characters per word, if any word is longer than this, then
# that word is "cut" and "..." is added at the end of it to let know
# that the word has been "chopped".
$cfgMaxWordLenght = 50;

# Maximum characters per message (NOT USED YET)
# $cfgMaxMessageLenght = 300;

# If you want to be notified of new entries added to your guestbook,
# set $cfgNewPostNotify to 1.
# Note: the mail() function should be available in your server
# (if you are using a free PHP hosting service, the mail() function
# may be disabled in order to avoid spamming, so don't enable this
# option)
$cfgNewPostNotify = 1;

# The address that will appear as the sender of notification emails.
# It is not required right now and you can leave it unchanged or blank,
# but in a future version it could be of more use.
$cfgNotifyFrom = "gera@irzhi.com";

# Email address which notifications will be sent to.
$cfgNotifyTo = "gera@irzhi.com";

# Notification emails title.
$cfgNotifySubject = "YapGB: A new post has been added to your guestbook!";

# If you enable the notify option, the message you receive will be the one
# stored in this variable. You can use GB_TAGS within to help you customize
# the message you receive :). The GB_TAGS you can use are:
# - All global GB_TAGS (used in temp_body.html file).
# - Entry GB_TAGS <!--GB_ENTRYNAME-->, <!--GB_ENTRYEMAIL-->,
#   <!--GB_ENTRYURL--> and <!--GB_ENTRYMESSAGE-->.
# Note: if you want to use double quotes (") within this text, you should write
#       them this way: \" (using the back-slash). This is a PHP requirement.
$cfgNotifyContent = "<!--GB_ENTRYNAME--> has signed your guestbook at"
                    ."http://vvkure.com/rotalis/books/yapgb.php\n"
                    ."Email: <!--GB_ENTRYEMAIL-->\n"
                    ."Url: <!--GB_ENTRYURL-->\n";

# System timezone, you can use it to customize YapGB timezone.
# Time is got from server's time, but now it is formatted
# in GMT format, so you can customize it by adding or substracting
# your GMT time offset, e.g.:
# if you live in Spain, you'd set it to 1
# if you live in Mexico City, you'd set it to -6
# if you live in London, you leave it as 0
$cfgGMTOffset = +2;

# Enable BBCode.
# As with other variables, a 1 enables BBCode and a 0 disables it.
# Note that this function is still experimental and may not work
# properly in some special cases.
$cfgEnableBBCode = 1;

# Enable smilies. If set to 1, smilies in the message field will be
# swapped by a corresponding image indicated in the $cfgSmilies array.
$cfgEnableSmilies = 1;

# The directory (relative to YapGB main file) where smilies images are located.
$cfgSmiliesPath = "smilies/";

# The smilies array. Please do not edit it unless you know what you are
# doing ;). This variable makes this file even bigger, sorry :). I'll try
# to implement a better administration interface.
# The format for every smilie is:
# "smilie" => array("image.ext", "Emotion"),
# If you want to disable a smilie, delete the lines where that smilie
# appears below. Also, you'll want to edit your temp_sign.html file and
# delete the smilie from the signing form.
# Note: the images included with YapGB were "borrowed" from the phpBB forum.
$cfgSmilies =array(
	//"smilie" => array("smilie_image.xxx", "Emotion" ),
	":!:"    => array( "icon_exclaim.gif", "Exclamation" ),
	":?:"    => array( "icon_question.gif", "Question" ),
	":?"     => array( "icon_confused.gif", "Confused" ),
	":-?"    => array( "icon_confused.gif", "Confused" ),
	":D"     => array( "icon_biggrin.gif", "Very happy" ),
	":-D"    => array( "icon_biggrin.gif", "Very happy" ),
	":grin:" => array( "icon_biggrin.gif", "Very happy" ),
	":)"     => array( "icon_smile.gif", "Smile" ),
	":-)"    => array( "icon_smile.gif", "Smile" ),
	":smile:" => array( "icon_smile.gif", "Smile" ),
	":("     => array( "icon_sad.gif", "Sad" ),
	":-("    => array( "icon_sad.gif", "Sad" ),
	":sad:"  => array( "icon_sad.gif", "Sad" ),
	":oops:" => array( "icon_redface.gif", "Embarrased" ),
	":o"     => array( "icon_surprised.gif", "Surprised" ),
	":-o"    => array( "icon_surprised.gif", "Surprised" ),
	":shock:" => array( "icon_eek.gif", "Shocked" ),
	"8)"     => array( "icon_cool.gif", "Cool" ),
	"8-)"    => array( "icon_cool.gif", "Cool" ),
	":lol:"  => array( "icon_lol.gif", "Laughing" ),
	":x"     => array( "icon_mad.gif", "Mad" ),
	":-x"    => array( "icon_mad.gif", "Mad" ),
	":P"     => array( "icon_razz.gif", "Razz" ),
	":-P"    => array( "icon_razz.gif", "Razz" ),
	":cry:"  => array( "icon_cry.gif", "Crying or very sad" ),
	";)"     => array( "icon_wink.gif", "Winking" ),
	";-)"    => array( "icon_wink.gif", "Winking" ),
	":wink:" => array( "icon_wink.gif", "Winking" ),
	":idea:" => array( "icon_idea.gif", "Idea" )
);

# Current YapGB version
$cfgYapGBVersion = "0.7.2";

# Show a sign succesful page.
# If set to 1, after signing the guesbook, it will show the temp_signsuccesful.html file
# (located within current theme's directory) to the user.
# If set to 0, the main guestbook page will be showed after signing the guestbook.
$cfgShowSuccesfulPage = 1;
# Confirmation code
# If set to 1, the user is required to enter a confirmation code in order
# to sign the guestbook.
$cfgEnableConfirmationCode = 1;
$cfgConfirmationCodeAsImage = 1;

# Show links only to admin (deprecated)
# If set to 1, entries' email and url links won't be showed unless
# administrator has logged in.
#$cfgShowLinksOnlyToAdmin = 1;

# Show Email and URL links to visitors.
# Email and Url links are always shown to administrator (when logged in).
# To show visitors' email and url links set these variables as needed.
# 1 = show link. 0 = do not show.
$cfgShowEmailLink = 0;
$cfgShowUrlLink = 1;

$cfgLogFile = "yapgb.log";
?>