<?php

// When user roles will be implemented in Neoton,
// this function will generate menu dynamically
// depending on user's role
function getMenu()
{
	return Array
		(
			'playlists' => Array (
				'active' => false,
				'title' => _('Playlists')
			),

			'schedule' => Array (
				'active' => false,
				'title' => _('Schedule')
			),

			'endpoints' => Array (
				'active' => false,
				'title' => _('Endpoints')
			),

			'library' => Array (
				'active' => false,
				'title' => _('Library')
			)
		);
}