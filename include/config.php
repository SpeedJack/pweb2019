<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

/*******************
 * DATABASE CONFIG *
 *******************/

/* Database hostname. Default: localhost */
$config['db']['host'] = 'db';

/* Database port. Default: 3306 */
// $config['db']['port'] = 3306;

/* Database username. Default: root */
$config['db']['username'] = 'dbuser';

/* Database password. Default: empty/no password */
$config['db']['password'] = 'dbpass';

/* Database name. Default: pweb */
$config['db']['dbname'] = 'mydb';

/* Database character set. Default: utf8 */
// $config['db']['charset'] => 'utf8';

/* If TRUE, mysqli will be used even when PDO is available. Default: false */
// $config['db']['prefer_mysqli_over_pdo'] = false;

/*******************
 * BASE APP CONFIG *
 *******************/
/* Application name. Default: Pweb */
// $config['app_name'] = 'Pweb';

/*
 * Message of the day, shown in every page under the app name. Default:
 * 'Message of the day'.
 */
// $config['header_motd'] = 'Message of the day';

/*
 * Fallback server name and port, used when the application is not able to get
 * the server name and port number.
 * Default:
 * 	fallback_server_name: localhost
 * 	fallback_server_port: 80
 */
// $config['fallback_server_name'] = 'xps';
// $config['fallback_server_port'] = 8080;

// $config['use_url_rewrite'] = false;
// $config['default_per_page'] = 20;

/***********************
 * USER SESSION CONFIG *
 ***********************/
// $config['session_canary_lifetime'] = 60*5;
// $config['auth_token_length'] = 20;
// $config['auth_token_duration'] = 60*60*24*30;

/**************************
 * FORM VALIDATION CONFIG *
 **************************/

// $config['min_password_length'] = 8;
// $config['username_regex'] = ...;
// $config['form_validation']= ....;

/*****************
 * LOCALE CONFIG *
 *****************/

/*
 * List of key-value pairs of supported locales. The key must be a regular
 * expression that matches the user submitted language name. The value must be
 * the appropriate locale name to load. This is OS-specific.
 */
// For Unix/Linux (Default):
// $config['locales'] = [
//	'/^en/i' => 'en_US.UTF-8',
//	'/^it/i' => 'it_IT.UTF-8'
// ];
// For Windows:
// $config['locales'] = [
//	'/^en/i' => 'english',
//	'/^it/i' => 'italian'
// ];

/* Default locale (used when the user's locale is not supported). Default: en */
// $config['default_locale'] = 'en';

/*
 * List of available languages on the language selector (footer). Default: en,
 * it
 */
// $config['selector_languages'] = [ 'en', 'it' ];

/*****************
 * SOCIAL CONFIG *
 *****************/

/*
 * Social usernames. Add only the part after website.tld/. Default: all field
 * empty (disabled)
 */
$config['social_names'] = [
	'facebook' => '#',
	'instagram' => '#',
	'twitter' => '#',
	'youtube' => 'user/#'
];

/****************
 * DEBUG CONFIG *
 ****************/

/* Debug mode. When enabled, all errors are shown to the user. Default: false */
$config['debug'] = true;

/*
 * When enabled, even caught errors/exceptions are shown to the user. Requires
 * debug=true and Xdebug extension. Default: false
 */
//$config['show_all_exceptions'] = true;

/* If TRUE always use specified fallback server name and port. Default: false */
$config['use_fallback_server_infos'] = false;
