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

require "options.php";
require "dblogon.php";

$imgpath="true";
require "header.php";
my_header("",0,0);

require "msgbox.php";

require_once "session.inc";
session_init();
$need_navigation=0;
if (!session_check()) 
  $need_navigation=1;

if($need_navigation == 1) {
  require_once "navigation.inc";
  echo "<div id=\"main\">\n";
}

titlebox("General");
?>
<center>
<table class="std_nb" border=0 width="650">
<tr class="a"><th colspan=2>Rules</th></tr>
<tr><td><br></td></tr>
<tr><td valign="top">1.</td><td>This is a private fun game. <br>
This means: I make the rules
how and whenever I want. If You dont have fun leave the game.</td></tr>
<tr><td valign="top">2.</td><td>If I dont have fun anymore the game stops.</td></tr>
<tr><td valign="top">3.</td><td>So called multies arent allowed - account sharing is interpreted as multiing, as is account switching/hopping. Thus dont use fake emails or anonymizers.</td></tr>
<tr><td valign="top">4.</td><td>So called bots, scripting and farming or donating roids, ships or salvage is forbidden.</td></tr>
<tr><td valign="top">5.</td><td>Spamming the forum or via mail isnt allowed.</td></tr>
<tr><td valign="top">6.</td><td>If you and friends or whatever play over
the same IP (LAN), you should send the Mod (1:1:2) or Admin (1:1:1) an ingame 
message  - multiple accounts from one IP as are those coming through 
web-anonymiser may be banned otherwise.</td></tr>
<tr><td valign="top">7.</td><td>Using offensive or insulting 
names/pictures/posts/mails/whatever may lead to direct deletion.</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>As far as possible standard language is english.</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2><b>By logging into this game You accept these terms.</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2 align=left>Khan, 1. Nov 2002</td></tr>
<tr><td colspan=2 align=left><em>Last update: December 2016</em></td></tr>
</table>

</center>

<?php
if($need_navigation == 1) 
  echo "</div>\n";

require "footer.php";
?>
