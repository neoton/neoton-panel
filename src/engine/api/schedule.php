<?php

function processSttAddition()
{
	$schedule_id = (int)$_POST['stt-schedule-id'];
	if (!scheduleExists($schedule_id))
		json_respond(1, 'No such schedule');

	$playlist_id = (int)$_POST['stt-playlist-id'];
	if (!playlistExists($playlist_id))
		json_respond(1, 'No such playlist');

	$time_start = $_POST['stt-time-start'];
	if (preg_match('/^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{1,2}$/', $time_start) !== 1)
		json_respond(2, 'Bad time_start format');

	$time_end = $_POST['stt-time-end'];
	if (preg_match('/^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{1,2}$/', $time_end) !== 1)
		json_respond(3, 'Bad time_end format');

	$res_id = addScheduleTime($schedule_id, $_POST['stt-time-start'], $_POST['stt-time-end'], $playlist_id);

	saveLiquidsoapScripts();

	json_respond(0, $res_id);
}

function processSttRemoval()
{
	$stt_id = (int)$_POST['stt-id'];
	if (!sttExists($stt_id))
		json_respond(1, 'No such schedule timetable item');


	removeScheduleTime($stt_id);

	saveLiquidsoapScripts();
	
	json_respond(0, 'OK');
}


array_shift($route); // remove /library/
switch ($route[0])
{
	case 'addTimetable' :
		processSttAddition();
		break;

	case 'removeTimetable' :
		processSttRemoval();
		break;

	default:
		json_respond (-1, 'Bad API request');
}