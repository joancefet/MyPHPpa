<?php

/*
 * MyPHPpa
 * Copyright (C) 2003, 2007 Jens Beyer
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

include_once "auth_check.php";

header ("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
   <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
   <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache,must-revalidate">
   <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
   <META HTTP-EQUIV="Expires" CONTENT="0">
   <META NAME="Author" CONTENT="khan@web.de (Jens Beyer)">

<?php
require_once "mobile.inc";

echo "  <LINK rel=stylesheet type=\"text/css\" href=\"mpb.css\">";
echo "  <TITLE>$game $version</TITLE>\n";
?>
</head>


