<!--
/*
 *  PlayerUI Copyright (C) 2013 Andrea Coiutti & Simone De Gregori
 *  Tsunamp Team
 *  http://www.tsunamp.com
 *
 *  This Program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3, or (at your option)
 *  any later version.
 *
 *  This Program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with RaspyFi; see the file COPYING.  If not, see
 *  <http://www.gnu.org/licenses/>.
 *
 *
 *  version:	1.1-dev
 * 
 *  Andrea Coiutti (aka ACX)
 *  PlayerUI frontend design - frontend HTML/JS/CSS coding
 * 
 *  Simone De Gregori (aka Orion) 
 *  PlayerUI PHP backend coding - frontend JS coding - OS optimization
 * 
 *
 *  RaspyFi Project (C) 2013 Michelangelo Guarise (OS image build/customization)
 *
 *  RaspyFi Logo design (C) 2013 Daniele Busciantella Ricci
 *
 */ 
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>V.A.M.P. </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/flat-ui.css" rel="stylesheet">
    <link href="css/bootstrap-select.css" rel="stylesheet">
	<link href="css/bootstrap-fileupload.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
	<!--[if lte IE 7]>
		<link href="css/font-awesome-ie7.min.css" rel="stylesheet">
	<![endif]-->
	<?php if ($sezione == 'index') { ?>
	<link href="css/jquery.countdown.css" rel="stylesheet">
	<?php } ?>
	<!--<link rel="stylesheet" href="css/jquery.mobile.custom.structure.min.css">-->
	<link href="css/jquery.pnotify.default.css" rel="stylesheet">
	<link rel="stylesheet" href="css/panels.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <!-- allows removal of the mobile safari window dressing when 
         added to the home screen of an iOS app. Also requires 
         link.js which is included below -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">

</head>

<body class="<?php echo $sezione ?>">
	
<?php
    if (isset($_POST['stop-all']))
    {
         shell_exec('mpc stop && service mpd restart');
    }
?>	
	
	

<div id="menu-top" class="ui-header ui-bar-f ui-header-fixed slidedown" data-position="fixed" data-role="header" role="banner">
	<div class="dropdown">
		<a class="dropdown-toggle" id="menu-settings" role="button" data-toggle="dropdown" data-target="#" href="<?php echo $sezione ?>.php">MENU <i class="fa fa-th-list dx"></i></a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="menu-settings">
			<li class="<?php ami('index'); ?>"><a href="index.php"><i class="fa fa-play sx"></i> Main</a></li>
			<li class="<?php ami('sources'); ?>"><a href="sources.php"><i class="fa fa-folder-open sx"></i> Library</a></li>
			<li class="<?php ami('mpd-config'); ?>"><a href="mpd-config.php"><i class="fa fa-cogs sx"></i> Playback </a></li>
			<li class="<?php ami('mpd-config'); ?>"><a href="credits.php" ><i class="fa fa-cogs sx"></i> DAC Info </a></li>
			<li><a href="#service-menu-modal" data-toggle="modal" ><i class="fa fa-cogs sx"></i> Service Menu</a></li>
			<li class="<?php ami('net-config'); ?>"><a href="net-config.php"><i class="fa fa-sitemap sx"></i> Network</a></li>
			<li class="<?php ami('settings'); ?>"><a href="settings.php"><i class="fa fa-wrench sx"></i> System</a></li>
			<li><a href="#poweroff-modal" data-toggle="modal"><i class="fa fa-power-off sx"></i> Turn off</a></li>
		</ul>
	</div>
	<div class="playback-controls">	
		<button id="previous" class="btn btn-cmd" title="Previous"><i class="fa fa-step-backward"></i></button>
		<button id="stop" class="btn btn-cmd" title="Stop"><i class="fa fa-stop"></i></button>
		<button id="play" class="btn btn-cmd" title="Play/Pause"><i class="fa fa-play"></i></button>
		<button id="next" class="btn btn-cmd" title="Next"><i class="fa fa-step-forward"></i></button>
		<form method="post"><button id="stop-all" name="stop-all" class="btn btn-cmd" ><i class="fa">Restart MPD</i></button></form>
	</div>
	<a class="home" href="index.php"><img src="images/logo.png" class="logo" alt="VAMP"></a>
</div>
<div id="menu-bottom" class="ui-footer ui-bar-f ui-footer-fixed slidedown" data-position="fixed" data-role="footer"  role="banner">
	<ul>
		<?php if ($sezione == 'index') { ?>
		<li id="open-panel-sx"><a href="#panel-sx" class="open-panel-sx" data-toggle="tab"><i class="fa fa-music sx"></i> Browse</a></li>
		<li id="open-panel-lib"><a href="#panel-lib" class="open-panel-lib" data-toggle="tab"><i class="fa fa-columns sx"></i> Library</a></li>
		<li id="open-playback" class="active"><a href="#playback" class="close-panels" data-toggle="tab"><i class="fa fa-play sx"></i> Playback</a></li>
		<li id="open-panel-dx"><a href="#panel-dx" class="open-panel-dx" data-toggle="tab"><i class="fa fa-list sx"></i> Playlist</a></li>
		<?php } else { ?>
		<li id="open-panel-sx"><a href="index.php#panel-sx" class="open-panel-sx"><i class="fa fa-music sx"></i> Browse</a></li>
		<li id="open-panel-lib"><a href="index.php#panel-lib" class="open-panel-lib"><i class="fa fa-columns sx"></i> Library</a></li>
		<li id="open-playback"><a href="index.php#playback" class="close-panels"><i class="fa fa-play sx"></i> Playback</a></li>
		<li id="open-panel-dx"><a href="index.php#panel-dx" class="open-panel-dx"><i class="fa fa-list sx"></i> Playlist</a></li>
		<?php } ?>
	</ul>
</div>
