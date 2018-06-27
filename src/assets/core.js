function rnd(min, max)
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function forEach(data, callback){
  for(var key in data){
    if(data.hasOwnProperty(key)){
      callback(key, data[key]);
    }
  }
}

function randword()
{
    var s = '';
    var ltr = 'qwertyuiopasdfghjklzxcvbnm';
    while (s.length < 20)
    {
        s += ltr[rnd(0, 20)];
    }
    return s;
}

function form2JSON(form)
{
    var unindexed_array = form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function toggle (e)
{
	$('#'+e).slideToggle(100);
}

function prompt (title, text, onOkPress)
{
	$('#message-header').html(title);
	$('#message-content').html(text);
	$('#message-prompt-ok').click(onOkPress);
	$('#message').fadeIn(100);
}

function showToast (message, type)
{
    type = type || 'info';

    var id = randword();

    $('#toast-messages-wrapper').append('<div class="toast-message '+type+'" id="'+id+'">'
        +message+'</div>');

    var block = $('#'+id);

    block.click(function() {block.fadeOut(100, function() { block.remove(); });});

    setTimeout(function() {block.fadeOut(100, function() { block.remove(); });},
        5000);

    block.fadeIn(100);
}

function ajax (address, data, onSuccess)
{
	$.ajax({
		'data': data,
		'dataType': 'json',
		'success': onSuccess,
		error: function()
		{
			showToast(LANG.network_error, 'error');
		},
		'type': 'POST',
		'url': address
	});
}

function setTrackData(fileform)
{
	var filename = $(fileform).val();
	filename = filename.replace(/.*[\/\\]/, '');
	filename = filename.replace(/\.[^/.]+$/, '');

	var trackParts = filename.split(/\s\-\s/);

	$('#track-artist').val(trackParts[0]);
	$('#track-title').val(trackParts[1]);
}

function editTrack(event, form)
{
	event.preventDefault();

	ajax('/api/library/edit', $(form).serialize(), function(json) {
		switch (+json['status'])
		{
			case 0 :
				showToast(LANG.track_updated);
				break;

			default :
				showToast(LANG.error+': '+json['payload'], 'error');
				break;
		}
	});

	return false;
}

function removeTrack(id)
{
	prompt (LANG.track_removal,
			LANG.track_removal_question, 
			function() { window.location.href = '/library/remove?track-id='+id; });
	
}

function removeEndpoint(id)
{
	prompt (LANG.endpoint_removal, LANG.endpoint_removal_question, 
			function() { window.location.href = '/endpoints/remove?endpoint-id='+id; });
}

function removePlaylist(id)
{
	prompt (LANG.playlist_removal, LANG.playlist_removal_question, 
		function() { window.location.href = '/playlists/remove?playlist-id='+id; });
}

function removeSchedule(id)
{
	prompt (LANG.schedule_removal, LANG.schedule_removal_question,
		function() { window.location.href = '/schedule/remove?schedule-id='+id; });
	
}

function addTimetable (event, form)
{
	event.preventDefault();

	var formData = form2JSON($(form));

	ajax('/api/schedule/addTimetable', formData, function(json) {
		switch (+json['status'])
		{
			case 0 :
				var sttId = +json['payload'],
					data = {
						'_STT_TIME_START_' : formData['stt-time-start'],
						'_STT_TIME_END_' : formData['stt-time-end'],
						'_STT_PLAYLIST_NAME_' : $("#stt-playlist-name-"+formData['stt-schedule-id']+" option:selected").text(),
						'_STT_ID_' : sttId
					},

					tpl = templatize(data, 'stt');

					$('#stt-for-'+formData['stt-schedule-id']).append(tpl);

					cs_restartEndpoints();
				break;

			default :
				showToast(LANG.error+': '+json['payload'], 'error');
				break;
		}
	});

	return false;
}

function removeStt(id) 
{
	ajax('/api/schedule/removeTimetable', {'stt-id':id}, function(json) {
		switch (+json['status'])
		{
			case 0 :
				$('#stt-'+id).remove();
				cs_restartEndpoints();
				break;

			default :
				showToast(LANG.error+': '+json['payload'], 'error');
				break;
		}
	});
}

function addPlaylistTrack (event, form)
{
	event.preventDefault();

	var formData = form2JSON($(form));

	ajax('/api/playlist/addTrack', formData, function(json) {
		switch (+json['status'])
		{
			case 0 :
				var sttId = +json['payload'],
					trackSelected = $("#tracks-for-"+formData['playlist-id']+" option:selected");
					data = {
						'_PLAYLIST_ID_' : formData['playlist-id'],
						'_TRACK_ID_' : formData['track-id'],
						'_TRACK_TITLE_' : trackSelected.text()
					},

					tpl = templatize(data, 'pls_track');

					$('#playlist-for-'+formData['playlist-id']).append(tpl);

					cs_restartEndpoints();
				
				break;

			default :
				showToast(LANG.error+': '+json['payload'], 'error');
				break;
		}
	});
}

function removePlaylistTrack (track, playlist)
{
	ajax('/api/playlist/removeTrack', {'track-id':track, 'playlist-id':playlist}, function(json) {
		switch (+json['status'])
		{
			case 0 :
				$('#playlist-'+playlist+'-track-'+track).remove();

				cs_restartEndpoints();
				break;

			default :
				showToast(LANG.error+': '+json['payload'], 'error');
				break;
		}
	});
}

function editEndpoint (event, form)
{
	event.preventDefault();

	var formData = form2JSON($(form));

	ajax('/api/endpoint/edit', formData, function(json) {
		switch (+json['status'])
		{
			case 0 :
				showToast(LANG.endpoint_updated);
				cs_restartEndpoints(formData['endpoint-id'], true);
				break;

			default :
				showToast(LANG.error+': '+json['payload'], 'error');
				break;
		}
	});
}

function setEndpointSchedule (event, form)
{
	event.preventDefault();

	var formData = form2JSON($(form));

	ajax('/api/endpoint/setSchedule', formData, function(json) {
		switch (+json['status'])
		{
			case 0 :
				showToast(LANG.schedule_set);
				cs_restartEndpoints(formData['endpoint-id']);
				break;

			default :
				showToast(LANG.error+': '+json['payload'], 'error');
				break;
		}
	});
}

function serverTimeInit()
{
	if ($('#server-time').length)
	{
		serverTimeUpdate();
	}
}

function serverTimeUpdate()
{
	var cDate = new Date(currentServerTime * 1000);

	time  = ((cDate.getHours() < 10) ? '0' : '')+cDate.getHours()+':'+
						((cDate.getMinutes() < 10) ? '0' : '')+cDate.getMinutes()+':'+
						((cDate.getSeconds() < 10) ? '0' : '')+cDate.getSeconds(),

	$('#server-time').text(time);

	currentServerTime++;

	setTimeout(serverTimeUpdate, 1000);
}