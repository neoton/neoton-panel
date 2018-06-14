<div class="add-item">
	<h2 onclick="toggle('add-item-track');"><?php echo(_('Add a playlist')); ?></h2>
	<form action="/playlists/add" method="post" class="add-item-form" id="add-item-track">
		<input type="text" name="playlist-name" placeholder="<?php echo(_('name')); ?>">
		<input type="submit" value="<?php echo(_('add')); ?>">
	</form>
</div>

<div class="items-list">
	<?php foreach ($content['playlists'] as $pls): ?>
		<div class="item-track">
			<div class="item-track-name" onclick="toggle('track-tools-<?php echo ($pls['playlist_id']); ?>')"><?php echo ($pls['playlist_name']);?></div>
			<div class="item-track-tools" id="track-tools-<?php echo ($pls['playlist_id']); ?>">
				<table class="schedule-timetable leftalign" id="playlist-for-<?php echo ($pls['playlist_id']); ?>">
					<tr>
						<th><?php echo(_('Track')); ?></th>
						<th></th>
					</tr>
					<?php foreach ($pls['tracks'] as $trk) : ?>
					<tr id="playlist-<?php echo ($trk['playlist_id']); ?>-track-<?php echo ($trk['track_id']); ?>">
						<td><?php echo ($trk['track_artist']); ?> &ndash; <?php echo ($trk['track_title']); ?></td>
						<td class="toolkey"><button class="button-remove" onclick="removePlaylistTrack(<?php echo ($trk['track_id']); ?>, <?php echo ($trk['playlist_id']); ?>); return false" title="<?php echo(_('Remove this track')); ?>">x</button></td>
					</tr>
					<?php endforeach; ?>

				</table>

				<div class="item-action-title"><?php echo(_('Add a track')); ?></div>
				<form onsubmit="return addPlaylistTrack(event, this)" method="post">
				<table class="schedule-timetable leftalign">
					<tr>
						<td>
							<select name="track-id" id="tracks-for-<?php echo ($pls['playlist_id']); ?>">
							<?php foreach ($content['tracks'] as $trk) : ?>
								<option value="<?php echo($trk['track_id']); ?>"><?php echo ($trk['track_artist']); ?> &ndash; <?php echo ($trk['track_title']); ?></option>
							<?php endforeach; ?>
							</select>
						</td>
						<td class="toolkey"><input class="button-remove" type="submit" value="+" title="<?php echo(_('Add this track')); ?>"></td>
					</tr>
				</table>
				<input type="hidden" name="playlist-id" value="<?php echo ($pls['playlist_id']); ?>">
				</form>
				<button class="button-remove" onclick="removePlaylist(<?php echo ($pls['playlist_id']); ?>); return false"><?php echo(_('Remove playlist')); ?></button>
			</div>
		</div>
	<?php endforeach; ?>
</div>