<?php
/*
 *      PlayerUI Copyright (C) 2013 Andrea Coiutti & Simone De Gregori
 *		 Tsunamp Team
 *      http://www.tsunamp.com
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
 *	UI-design/JS code by: 	Andrea Coiutti (aka ACX)
 * PHP/JS code by:			Simone De Gregori (aka Orion)
 *
 * file:							db/index.php
 * version:						1.0
 *
 */

// common include
include('../inc/connection.php');
error_reporting(ERRORLEVEL);

header('Content-Type: application/json');

if (isset($_GET['cmd']) && $_GET['cmd'] != '') {

		if ( !$mpd ) {
			echo json_encode(['error' => 'Error Connecting to MPD daemon']);
		}  else {
				switch ($_GET['cmd']) {

				case 'filepath':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						if ($spop && strcmp(substr($_POST['path'],0,7),"SPOTIFY") == 0) {
							$arraySpopSearchResults = querySpopDB($spop, 'filepath', $_POST['path']);
							echo json_encode($arraySpopSearchResults);

						} else {
							$arrayMpdSearchResults = searchDB($mpd,'filepath',$_POST['path']);
							echo json_encode($arrayMpdSearchResults);

						}

					} else {
						$arraySearchResults = searchDB($mpd,'filepath');

						if ($spop) {
							$arraySpopSearchResults = querySpopDB($spop, 'filepath', '');
							$arraySearchResults = array_merge($arraySearchResults, $arraySpopSearchResults);

						}

						echo json_encode($arraySearchResults);

					}

					break;

				case 'playlist':
					echo json_encode(getPlayQueue($mpd));
					break;

				case 'add':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						echo json_encode(addQueue($mpd,$_POST['path']));
					}
					break;

				case 'addplay':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						$status = _parseStatusResponse(MpdStatus($mpd));
						$pos = $status['playlistlength'] ;
						addQueue($mpd,$_POST['path']);
						sendMpdCommand($mpd,'play '.$pos);
						echo json_encode(readMpdResponse($mpd));
					}
					break;

				case 'addreplaceplay':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						sendMpdCommand($mpd,'clear');
						addQueue($mpd,$_POST['path']);
						sendMpdCommand($mpd,'play');
						echo json_encode(readMpdResponse($mpd));
					}
					break;

				case 'update':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						sendMpdCommand($mpd,"update \"".html_entity_decode($_POST['path'])."\"");
						echo json_encode(readMpdResponse($mpd));
					}
					break;

				case 'trackremove':
					if (isset($_GET['songid']) && $_GET['songid'] != '') {
						echo json_encode(remTrackQueue($mpd,$_GET['songid']));
					}
					break;

				case 'savepl':
					if (isset($_GET['plname']) && $_GET['plname'] != '') {
						sendMpdCommand($mpd,"rm \"".html_entity_decode($_GET['plname'])."\"");
						sendMpdCommand($mpd,"save \"".html_entity_decode($_GET['plname'])."\"");
						echo json_encode(readMpdResponse($mpd));
					}
					break;

				case 'search':
					if (isset($_POST['query']) && $_POST['query'] != '' && isset($_GET['querytype']) && $_GET['querytype'] != '') {
						$arraySearchResults = searchDB($mpd,$_GET['querytype'],$_POST['query']);

						if ($spop) {
							$arraySpopSearchResults = querySpopDB($spop, 'file', $_POST['query']);
							$arraySearchResults = array_merge($arraySearchResults, $arraySpopSearchResults);

						}

						echo json_encode($arraySearchResults);

					}

					break;

				case 'loadlib':
					echo loadAllLib($mpd);
					break;

				case 'playall':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						echo json_encode(playAll($mpd,$_POST['path']));
					}
					break;

				case 'addall':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						echo json_encode(enqueueAll($mpd,$_POST['path']));
					}
					break;

				case 'spop-playtrackuri':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						sendMpdCommand($mpd,'stop');
						echo sendSpopCommand($spop, "uplay " . $_POST['path']);
					}
					break;

				case 'spop-addtrackuri':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						echo sendSpopCommand($spop, "uadd " . $_POST['path']);
					}
					break;

				case 'spop-playplaylistindex':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						$sSpopPlaylistIndex = end(explode("@", $_POST['path']));
						sendMpdCommand($mpd,'stop');
						echo sendSpopCommand($spop, "play " . $sSpopPlaylistIndex);
					}
					break;

				case 'spop-addplaylistindex':
					if (isset($_POST['path']) && $_POST['path'] != '') {
						$sSpopPlaylistIndex = end(explode("@", $_POST['path']));
						echo sendSpopCommand($spop, "add " . $sSpopPlaylistIndex);
					}
					break;

                case 'getspotifyqueue':
                    $spotifyqueue=array();

                    if ($spop) {
                        $spotifyqueue = getSpopQueue($spop);
                    } else {
                        $spotifyqueue[]='No spotify connection open.';
                    }

               echo json_encode($spotifyqueue);
                    break;

			}

		}

} else {
	echo json_encode(
		[
		'service'       => 'MPD DB INTERFACE',
		'disclaimer'    => 'INTERNAL USE ONLY!',
		'hosted_on' 	=> gethostname() . ":" . $_SERVER['SERVER_PORT']
		]);

}

if ($mpd) {
	closeMpdSocket($mpd);

}

if ($spop) {
	closeSpopSocket($spop);

}

?>

