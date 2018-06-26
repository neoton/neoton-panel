#!<?php echo(LS_INTERPRETER_PATH); ?>


set("log.level", <?php echo(LS_LOG_LEVEL); ?>)

set("log.file", true)
set("log.file.path", "<?php echo(LS_LOG_DIR); ?>/endpoint_<?php echo($liq['endpoint']['endpoint_id']); ?>.log")

set("harbor.bind_addr", "0.0.0.0")
set("harbor.icy", true)
set("log.stdout", false)


<?php foreach ($liq['playlists'] as $pls): ?>

playlist_<?php echo ($pls['playlist_id']); ?> = merge_tracks(playlist(reload=1, reload_mode="rounds", mode="normal", "<?php echo(LS_PLAYLIST_DIR.'/playlist_'.$pls['playlist_id'].'.txt'); ?>"))

<?php endforeach; ?>

scheduled_stream = switch(track_sensitive = false,
			[
			<?php
				$len = count($liq['timetable']);

				// We use for() instead of foreach() because
				// we need to detect the last element and decide
				// to add or not to add the comma
				for ($i = 0; $i < $len; $i++):
					$stt = $liq['timetable'][$i]; ?>
			(
				{ <?php echo($stt['stt_time_start']);?>-<?php echo($stt['stt_time_end']);?> },
				playlist_<?php echo($stt['stt_playlist_id']); ?>
			
			),
<?php
			 	// if ($i < $len - 1) echo(','); 
			 	echo ("\n");
			 endfor; ?>
			 
			({ true }, blank())
		])
				

scheduled_stream = mksafe(scheduled_stream)

output.harbor(%mp3(bitrate=128),
        port = <?php echo($liq['endpoint']['endpoint_port']); ?>,
        encoding = "UTF-8",
        mount = "<?php echo($liq['endpoint']['endpoint_mount']); ?>",
        user = "endpoint_<?php echo($liq['endpoint']['endpoint_id']); ?>",
        password = "<?php echo($liq['endpoint']['endpoint_password']); ?>",
scheduled_stream)

