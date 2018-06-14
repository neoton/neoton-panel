<div class="add-item">
	<h2 onclick="toggle('add-item-track');"><?php echo(_('Add a track')); ?></h2>
	<form action="/library/add" method="post" class="add-item-form" id="add-item-track" enctype="multipart/form-data">
		<input type="file" name="track-file" id="track-file" onchange="setTrackData(this)" required>
		<input type="text" name="track-artist" id="track-artist" placeholder="<?php echo(_('artist')); ?>" required>
		<input type="text" name="track-title" id="track-title" placeholder="<?php echo(_('title')); ?>" required>
		<input type="submit" value="<?php echo(_('upload')); ?>">
	</form>
</div>

<div class="add-item" id="tracks-search">
	<h2><?php echo(_('Search for a track')); ?></h2>
	<form action="/library/search" method="get" class="add-item-form" id="search-track">
		<input type="text" name="q" placeholder="<?php echo(_('artist or title')); ?>" value="<?php echo($content['query']); ?>">
		<input type="submit" value="<?php echo(_('search')); ?>">
	</form>
</div>

<div class="items-list">
	<?php foreach ($content['tracks'] as $track): ?>
		<div class="item-track">
			<div class="item-track-name" onclick="toggle('track-tools-<?php echo ($track['track_id']); ?>')"><?php echo ($track['track_artist'].' &ndash; '.$track['track_title']);?></div>
			<div class="item-track-tools" id="track-tools-<?php echo ($track['track_id']); ?>">
				<?php echo(_('Edit the track metadata')); ?>
				<form onsubmit="return editTrack(event, this)" method="post">
					<input type="text" name="track-artist" placeholder="<?php echo(_('artist')); ?>" value="<?php echo ($track['track_artist']) ?>" required>
					<input type="text" name="track-title" placeholder="<?php echo(_('title')); ?>" value="<?php echo ($track['track_title']) ?>" required>
					<input type="hidden" name="track-id" value="<?php echo ($track['track_id']); ?>">
					<input type="submit" value="<?php echo(_('update')); ?>">
				</form>
				<button class="button-remove" onclick="removeTrack(<?php echo ($track['track_id']); ?>); return false"><?php echo(_('remove')); ?></button>
			</div>
		</div>
	<?php endforeach; ?>
</div>