<?php

function getLangJson()
{
	return json_encode(Array(
		'network_error' => _('System or network error'),
		'track_updated' => _('Track metadata has been updated'),
		'endpoint_updated' => _('Endpoint info has been updated'),
		'schedule_set' => _('Schedule has been associated with the endpoint'),
		'error' => _('Error'),
		'track_removal' => _('Removing the track'),
		'track_removal_question' => _('Are you sure that you want to remove selected track?'),
		'endpoint_removal' => _('Removing the endpoint'),
		'endpoint_removal_question' => _('Are you sure that you want to remove selected endpoint?'),
		'playlist_removal' => _('Removing the playlist'),
		'playlist_removal_question' => _('Are you sure that you want to remove selected playlist?'),
		'schedule_removal' => _('Removing the schedule'),
		'schedule_removal_question' => _('Are you sure that you want to remove selected schedule?'),
		'no_websockets' => _('Your browser does not support WebSockets'),
		'websocket_disconnected' => _('Socket disconnected'),
		'websocket_not_connected' => _('Socket is not connected'),
		'registration_error' => _('Control server registration error'),
		'streaming_servers_restarted' => _('Streaming servers have been restarted'),
		'could_not_restart' => _('Could not restart streaming servers'),
		'endpoints_reloaded' => _('Endpoints info reloaded'),
		'endpoint_reload_error' => _('Endpoint info reload error'),
	));
}

clearstatcache();
putenv('LANG='.NEOTON_LANG);
setlocale(LC_ALL, NEOTON_LANG, NEOTON_LANG.'.UTF-8');
bindtextdomain('neoton', dirname(__FILE__).'/lang');
textdomain('neoton');