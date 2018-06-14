<?php

function processAddition()
{

	$schedule_name = trim($_POST['schedule-name']);

	if (!empty($schedule_name))
		addSchedule($schedule_name);
	else
		addFurtherMessage(_('Schedule name can not be empty'));

	
	header ('HTTP/1.1 302 Added');
	header ('Location: /schedule');
	die();
}


function processRemoval()
{

	$schedule_id = (int)$_GET['schedule-id'];

	if (scheduleExists($schedule_id))
		removeSchedule($schedule_id);
	else
		addFurtherMessage(_('This schedule does not exist'));

	header ('HTTP/1.1 302 Removed');
	header ('Location: /schedule');
	die();
}

switch ($route[1])
{
	case 'add' :
		processAddition();
		break;

	case 'remove' :
		processRemoval();
		break;
}

$content['title'] = _('Schedule');
$content['description'] = _('Schedule management: add, remove, edit timetables of the schedule');
$content['schedule'] = getSchedules();
$content['playlists'] = getPlaylists();

foreach ($content['schedule'] as $key => $sched)
{
	$content['schedule'][$key]['timetable'] = getScheduleTimetable($sched['schedule_id']);
}

display('schedule', $content);