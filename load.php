<?php
define('ABSPATH', __DIR__);
define('ADMIN_PATH', ABSPATH.'/admin');
define('ADMIN_SCRIPT_PATH', ADMIN_PATH.'/scripts');

# Display Errors, DEBUG ONLY. Turn off in production env.
// ini_set('display_errors', 1);

# Start the session 
session_start();

# Anything that is a script should go here
require_once ABSPATH.'/config/database.php';
require_once ADMIN_SCRIPT_PATH.'/functions.php';
require_once ADMIN_SCRIPT_PATH.'/login.php';
#require_once ADMIN_SCRIPT_PATH.'/user_count.php';
# require_once ADMIN_SCRIPT_PATH. '/login_attempts.php';