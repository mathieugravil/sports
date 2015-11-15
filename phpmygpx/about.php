<?php
/**
* @version $Id: about.php 442 2012-07-29 19:54:34Z eska $
* @package phpmygpx
* @copyright Copyright (C) 2009-2012 Sebastian Klemm.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

define( '_VALID_OSM', TRUE );
define( '_PATH', './' );
$DEBUG = FALSE;
if($DEBUG) error_reporting(E_ALL);

session_start();

include("./check_db.php");
#include("./config.inc.php");
#include("./libraries/functions.inc.php");
#include("./libraries/classes.php");
include("./libraries/html.classes.php");

setlocale (LC_TIME, $cfg['config_locale']);
include("./languages/".get_lang($cfg['config_language']).".php");
include("./head.html.php");

if($cfg['show_exec_time'])
    $startTime = microtime_float();

if($DEBUG) {
    foreach($_POST as $akey => $val)
        out("<b>$akey</b> = $val", "OUT_DEBUG");
}

if(!$cfg['embedded_mode'] || !$cfg['public_host'] || check_password($cfg['admin_password'])) {
	HTML::heading(_APP_NAME, 2);
	HTML::main_menu();
}

HTML::heading(_MENU_ABOUT, 3);
HTML::message(_APP_NAME ." Version ". _APP_VERSION ." (released: ". _APP_RELEASE_DATE .")");

// check for software updates
if($cfg['check_updates'] && function_exists('fsockopen')) {
	$url = "phpmygpx.tuxfamily.org/check_upd.php?v=". _APP_VERSION;
	$answer = fetchUrlWithoutHanging($url, 5, 0);
	#print_r($answer);
	// successfully connected
	if(strpos($answer[0], "200") !== FALSE) {
		$version = strip_tags($answer[sizeof($answer)-1]);
		if($version == _APP_VERSION) {
			HTML::message(_NO_UPDATE_AVAIL);
		}
		else {
			HTML::message_r("%1% ". _UPDATE_AVAIL . $version,
				'<img src="images/b_tipp.png" />');
		}
	}
	// doc not found on update server
	elseif(strpos($answer[0], "404") !== FALSE) {
		HTML::message(_UPDATE_SERVER_ERROR404);
	}
	// no connection to update server
	else {
		HTML::message(_UPDATE_SERVER_CONN_ERROR);
	}
}
else {
	HTML::message(_UPDATE_CHECK_DISABLED);
}

HTML::heading(_ABOUT_LICENSE, 4);
?>
<table><tr><td>
	<div style="font-size:9pt;">
	<p>
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.
	</p><p>
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
	</p><p>
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <a href="http://www.gnu.org/licenses/">
    www.gnu.org/licenses/</a>.
	</p>
	</div>
</td><td width=130>
	<div style="padding:20px; font-size:8pt;">
	<a rel="license" href="http://creativecommons.org/licenses/GPL/2.0/"><img alt="Creative Commons License" style="border-width:0" src="images/CC_GPL_88x62.png" /></a> This software is licensed under a <a rel="license" href="http://creativecommons.org/licenses/GPL/2.0/">Creative Commons</a> license.
	</div>
</td></tr></table>
<?php
HTML::heading(_ABOUT_CREDITS, 4);
HTML::message("Thanks to all the people who contributed in any way!");
?>

<ul>
<li>Arno Renevier (French translation), <a href="http://syp.renevier.net/">website</a></li>
<li>Leon Vrancken (Dutch translation)</li>
<li>Andrés Gómez Casanova (Spanish translation)</li>
<li>Uwe Zerbe (code for elevation charts against distance)</li>
<li>Sarah Hoffmann (hiking layer tiles, <a href="http://hiking.lonvia.de/">website</a>)</li>
<li>Colin Marquardt (Hike & Bike Map and hillshading tiles, <a href="http://hikebikemap.de/">website</a>)</li>
</ul>

<p>Code from the following open source projects is included:</p>
<ul>
<li>OpenLayers 2.10 (<a href="http://www.openlayers.org/">www.openlayers.org</a>)</li>
<li>GeoCalc-PHP 1.2 by Steven Brendtro (<a href="http://geocalc-php.sourceforge.net/">geocalc-php.sourceforge.net</a> / <a href="http://www.imaginerc.com/software/GeoCalc/">www.imaginerc.com/software/GeoCalc/</a>)</li>
<li>PHPlot 5.1.1 (<a href="http://phplot.sourceforge.net/">phplot.sourceforge.net</a>)</li>
<li>Photolayer by Florian Lohoff</li>
<li>ProxySimplePHP by Lizard (<a href="http://wiki.openstreetmap.org/wiki/ProxySimplePHP">wiki.openstreetmap.org/wiki/ProxySimplePHP</a>)</li>
<li>Map icons CC-0 from SJJB Management (<a href="http://www.sjjb.co.uk/mapicons/">www.sjjb.co.uk/mapicons/</a>)</li>
</ul>

<?php
HTML::message("Some icons are taken from the open source applications phpMyAdmin 2.9 (<a href='http://www.phpmyadmin.net'>www.phpmyadmin.net</a>) and Joomla 1.5 (<a href='http://www.joomla.org'>www.joomla.org</a>).");

if($cfg['show_exec_time']) {
    $endTime = microtime_float();
    $exectime = round($endTime - $startTime, 4);
}
include("./foot.html.php");
?>