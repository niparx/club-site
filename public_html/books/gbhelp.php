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
~= gbhelp.php: YapGB help information.                             =~
~=                                                                 =~
~= CHANGES                                                         =~
~= v0.6:                                                           =~
~= - BBCode help information is now loaded from temp_bbcode.html   =~
~= v0.5:                                                           =~
~= - Added this file to the YapGB structure.                       =~
~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~
*/

include_once("gbconfig.php");
include_once("gbfunc.php");

if ( !empty($_GET) ) {
	if ( isset($_GET["op"]) ) $op = $_GET["op"];
}
else if ( !empty($HTTP_GET_VARS) ) {
	if ( isset($HTTP_GET_VARS["op"]) ) $op = $HTTP_GET_VARS["op"];
}

switch($op) {
	case "BBCode":
		$helpPage = gbLoadTemplate( "temp_bbcode.html" );
		echo $helpPage;
		break;
	default:
		die("Nothing to show");
}