var
	templates = {
	// ======== TRACK =========
	// _TRACK_ID_ => Track ID
	// _TRACK_TITLE_ => Track title + artist
	// _PLAYLIST_ID_ => Playlist ID
		pls_track : `
			<tr id="playlist-_PLAYLIST_ID_-track-_TRACK_ID_">
				<td>_TRACK_TITLE_</td>
				<td class="toolkey"><button class="button-remove" onclick="removePlaylistTrack(_TRACK_ID_, _PLAYLIST_ID_); return false">x</button></td>
			</tr>`,


	// ======== TRACK =========

	// ======== STT =========

		stt : `
		<tr id="stt-_STT_ID_">
			<td>_STT_TIME_START_</td>
			<td>_STT_TIME_END_</td>
			<td>_STT_PLAYLIST_NAME_</td>
			<td><button class="button-remove" onclick="removeStt(_STT_ID_); return false">x</button></td>
		</tr>`

	// ======== STT =========

};

function templatize(map, template)
{
	var src = templates[template];

	forEach(map, function (key, value) {
		src = src.replace(new RegExp(key, 'g'), value);
	});

	return src;
}