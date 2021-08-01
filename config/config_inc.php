<?php

$g_hostname = 'mysql';
$g_db_type = 'mysqli';
$g_database_name = 'bugtracker';
$g_db_username = 'root';
$g_db_password = 'root';

$g_default_timezone = 'Europe/Warsaw';

$g_crypto_master_salt = 'UKsMbw3R22LSgiKYUqAk95GHo/jfHQ34qTTonPT+i4Q=';

$g_display_errors = [
    E_WARNING => DISPLAY_ERROR_HALT,
    E_ALL => DISPLAY_ERROR_INLINE,
];

$g_stop_on_errors = ON;
$g_show_detailed_errors = ON;
$g_log_level = LOG_ALL;
