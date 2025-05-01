<?php
declare(strict_types=1);
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

function rval (string $val): void {
  if ($val < 200000) // 200 thousand
    return pval($val) . "g";
  else if ($val < 10000000) // 10 Millionen
    return pval($val/1000) . "k";
  else 
    return pval($val/1000000). "t";
}

function pval (string $val): void {
  return number_format($val, 0, ",", ".");
}

function get_online(): void {
  global $db;

  $result = mysqli_query($db, "SELECT COUNT(*) from planet");
  $row = mysqli_fetch_row($result);
  $total = $row[0];

  $result = mysqli_query($db, "SELECT COUNT(*) ".
                        "FROM planet WHERE mode=2 or mode=0xF2");
  $row = mysqli_fetch_row($result);
  $numonline = $row[0];

  if ($numonline == 1) {
    return "You are the only player of $total online";
  } else {
    return "$numonline of $total Players online";
  }
}

function top_header(string $myrow): void {
  global $mytick, $player_ip, $imgpath;

  $news = "<td  width=\"20%\" align=center>News";
  if ($myrow["has_news"]) {
    $news = "<td  width=\"20%\" align=center bgcolor=\"lightblue\">".
	"<a class=\"x\" href=\"news.php\" accesskey=\"n\">News</a>";
  }
  $mail = "<td  width=\"20%\" align=center>Mail";
  if ($myrow["has_mail"]) {
    $mail = "<td  width=\"20%\" align=center bgcolor=\"lightblue\">".
	"<a class=\"x\" href=\"messages.php\" accesskey=\"m\">Mail</a>";
  }
  $hostile = "<td  width=\"20%\" align=center>Hostile";
  if ($myrow["has_hostile"]) {
    $hostile = "<td  width=\"20%\" align=center bgcolor=\"#FFB0B0\">".
	"<a class=\"x\" href=\"galstatus.php\">Hostile</a>";
  }
  $friendly = "<td  width=\"20%\" align=center>Friendly";
  if ($myrow["has_friendly"]) {
    $friendly = "<td  width=\"20%\" align=center bgcolor=\"lightgreen\">".
	"<a class=\"x\" href=\"galstatus.php\">Friendly</a>";
  }

  $galaxy = "<td width=\"20%\">&nbsp;";
  if ($myrow["gal_hostile"]) {
    $galaxy = "<td  width=\"20%\" align=center bgcolor=\"#FFE0A0\">".
	"<a class=\"x\" href=\"galstatus.php\">Galaxy</a>";
  } else {
    if ($myrow["has_politics"] & 1) {
      $galaxy = "<td  width=\"20%\" align=center bgcolor=\"lightblue\">".
	"<a class=\"x\" href=\"politics.php\" accesskey=\"p\">Politics</a>";
    } else if ($myrow["has_politics"] & 2) {
      $galaxy = "<td  width=\"20%\" align=center bgcolor=\"lightblue\">".
        "<a class=\"x\" href=\"allforum.php\">Alliance</a>";
    }
  }

  global $date_format;
  $gdate = date($date_format);
  $online = get_online();

  global $tickfile;

  if (file_exists($tickfile . '.run')) {
    $diff = time() - filemtime($tickfile . '.run');
    if ($diff < 60) {
      if ($diff < 30) {
        $tdate = "Last: <span class=\"norm\" id=\"myt\">$diff</span> sec ago";
      } else {
        $tdate = "Last: <span class=\"red\" id=\"myt\">$diff</span> sec ago";
      }
    } else {
      $tdate = sprintf(
        "Last: %02d:%02d:%02d ago",
        floor($diff / 3600),
        floor(($diff % 3600) / 60),
        $diff % 60
      );
    }
  } else {
    $tdate = "<span class=\"red\">Ticks stopped</span>";
  }

  echo "<center>\n";
  if ($imgpath && $imgpath != "") {
    echo "<TABLE class=\"top\">\n";
  } else {
    echo "<TABLE class=\"top\">\n";
  }
  echo "<tr><td colspan=3 align=left width=\"60%\"><span class=\"medium\">".
    "<b>Score</b>: ".
    pval($myrow["score"])."</span></td>\n";
  echo "<td colspan=2 align=right width=\"40%\"><span class=\"medium\">".
    $myrow["leader"]." of ".
    $myrow["planetname"]."</span></td></tr>\n";
  
  echo "<tr><td align=left><span class=\"tiny\">$gdate</span></td>\n".
    "<td align=left><span class=\"tiny\">$tdate</span></td>\n".
    "<td align=center><span class=\"tiny\"><b>MyT</b>: ".
    "<span id=\"mtt\">$mytick</span></span></td>\n".
    "<td align=center>$player_ip</td><td align=right>".
    "<b>$myrow[x]:$myrow[y]:$myrow[z]</b></td></tr>\n";

  echo "<tr>$hostile</td>$friendly</td>\n";
  echo "$galaxy</td>$mail</td>$news</td></tr>\n";
  
  echo "<tr><td align=left width=\"20%\"><b>Metal</b>: ".
    rval($myrow["metal"])."</td>\n";
  echo "<td align=left width=\"20%\"><b>Crystal</b>: ".
    rval($myrow["crystal"])."</td>\n";
  echo "<td align=left width=\"20%\"><b>Eonium</b>: ".
    rval($myrow["eonium"]).
    "</td><td colspan=2 width=\"40%\" align=\"right\">$online</td></tr>\n";

  echo "</TABLE></center>\n";
}
?>
