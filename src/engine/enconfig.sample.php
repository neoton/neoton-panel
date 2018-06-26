<?php

// System language. Use gettext-compatible format like en_US, ru_RU
// en_US is used by default if no translation found for the specified lang
define ('NEOTON_LANG', 'en_US');

// Database settings. Constants are self-descriptive
define ('DB_HOST', '127.0.0.1');
define ('DB_NAME', 'neoton');
define ('DB_USER', 'neoton');
define ('DB_PASSWORD', 'passwd');

// Salt to be used for password storage
// Change it to something random
define ('AUTH_SALT', '_change_me_plz');

// Path to the directory where Neoton will store its music library
// You can use external storage and mount it via NFS
define ('AUDIO_STORAGE', '/srv/audio/neoton');

// These are Liquidsoap settings. Neoton uses Liquidsoap to produce
// the broadcasting stream and deliver it to the endpoints

// Path to Liquidsoap's binary. Used in shebang in *.liq files
define ('LS_INTERPRETER_PATH', '/usr/bin/liquidsoap');

// Liquidsoap's log level. Logs are written to stdout and caught by 
// control process (neoton-daemon)
define ('LS_LOG_LEVEL', 4);

// Where to save liquidsoap interpreter's log
define ('LS_LOG_DIR', '/var/log/neoton');

// Where to save generated playlist files
define ('LS_PLAYLIST_DIR', '/var/run/neoton/playlists');

// Where to save *.liq files
define ('LS_SCRIPT_DIR', '/var/run/neoton/liquidsoap');

// Control server settings. They are used to tell admin's 
// browser where to connect to access the control system (neoton-daemon)
define ('CS_SERVER', $_SERVER['SERVER_ADDR']);
define ('CS_PORT', 1337);
define ('CS_SECURE', false); // Use WS or WSS?