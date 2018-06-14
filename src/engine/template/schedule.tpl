
<div class="add-item">
	<h2 onclick="toggle('add-item-track');"><?php echo(_('Add schedule')); ?></h2>
	<form action="/schedule/add" method="post" class="add-item-form" id="add-item-track">
		<input type="text" name="schedule-name" placeholder="<?php echo(_('name')); ?>">
		<input type="submit" value="<?php echo(_('add')); ?>">
	</form>
</div>

<div class="schedule-server-time"><?php echo(_('Server time')); ?>: <span id="server-time"></span></div>

<div class="items-list">
	<?php foreach ($content['schedule'] as $sch): ?>
		<div class="item-track">
			<div class="item-track-name" onclick="toggle('track-tools-<?php echo ($sch['schedule_id']); ?>')"><?php echo ($sch['schedule_name']);?></div>
			<div class="item-track-tools" id="track-tools-<?php echo ($sch['schedule_id']); ?>">
				<table class="schedule-timetable" id="stt-for-<?php echo ($sch['schedule_id']); ?>">
					<tr>
						<th><?php echo(_('Start time')); ?></th>
						<th><?php echo(_('Stop time')); ?></th>
						<th><?php echo(_('Playlist')); ?></th>
						<th></th>
					</tr>
					<?php foreach ($sch['timetable'] as $stt) : ?>
					<tr id="stt-<?php echo ($stt['stt_id']); ?>">
						<td><?php echo ($stt['stt_time_start']); ?></td>
						<td><?php echo ($stt['stt_time_end']); ?></td>
						<td><?php echo ($stt['stt_playlist_name']); ?></td>
						<td><button class="button-remove" onclick="removeStt(<?php echo ($stt['stt_id']); ?>); return false" title="<?php echo(_('Remove this time frame')); ?>">x</button></td>
					</tr>
					<?php endforeach; ?>

				</table>

				<?php echo(_('Add time frame')); ?>
				<form onsubmit="return addTimetable(event, this)" method="post">
				<table class="schedule-timetable">
					<tr>
						<td><input type="time" step="1" name="stt-time-start"></td>
						<td><input type="time" step="1" name="stt-time-end"></td>
						<td><select name="stt-playlist-id" id="stt-playlist-name-<?php echo ($sch['schedule_id']); ?>">
						<?php foreach ($content['playlists'] as $pls) : ?>
							<option value="<?php echo($pls['playlist_id']); ?>"><?php echo ($pls['playlist_name']); ?></option>
						<?php endforeach; ?>
					</select></td>
						<td><input type="hidden" name="stt-schedule-id" value="<?php echo ($sch['schedule_id']); ?>"><input type="submit" value="+" title="Add a time frame"></td>
						
					</tr>
				</table>
				</form>
				<button class="button-remove" onclick="removeSchedule(<?php echo ($sch['schedule_id']); ?>); return false"><?php echo(_('remove schedule')); ?></button>
			</div>
		</div>
	<?php endforeach; ?>
</div>