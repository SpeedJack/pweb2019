<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */


/*
 * To make the application work, just set up the following Db config.
 * Use the rest of this file only for advanced features config.
 */
// Db config
$config['db']['host'] = 'localhost';
$config['db']['port'] = 3306;
$config['db']['username'] = 'root';
$config['db']['password'] = '';
$config['db']['dbname'] = 'pweb';
// Uncomment if PDO does not work
// $config['db']['prefer_mysqli_over_pdo'] = true;


// Show all social buttons in footer.
// Social config (to show links)
$config['social_names'] = [
	'facebook' => '#',
	'instagram' => '#',
	'twitter' => '#',
	'youtube' => 'user/#'
];

// For Windows (comment out for Unix):
$config['locales'] = [
	'/^en/i' => 'english',
	'/^it/i' => 'italian'
];

// Show all errors/warnings:
// $config['debug'] = true;




/*******************
 * DATABASE CONFIG *
 *******************/

/* Database hostname. Default: localhost */
// $config['db']['host'] = 'localhost';

/* Database port. Default: 3306 */
// $config['db']['port'] = 3306;

/* Database username. Default: root */
// $config['db']['username'] = 'root';

/* Database password. Default: empty/no password */
// $config['db']['password'] = '';

/* Database name. Default: pweb */
// $config['db']['dbname'] = 'pweb';

/* Database character set. Default: utf8 */
// $config['db']['charset'] => 'utf8';

/* If TRUE, mysqli will be used even when PDO is available. Default: false */
// $config['db']['prefer_mysqli_over_pdo'] = false;


/*******************
 * BASE APP CONFIG *
 *******************/

/* Application name. Default: '> CTF' */
// $config['app_name'] = '> CTF';

/*
 * Message of the day, shown in every page under the app name. Default:
 * 'A platform for Jeopardy style CTFs'
 */
// $config['header_motd'] = 'A platform for Jeopardy style CTFs.';

/*
 * If TRUE, all URLs are rewritten in the form: site.tld/page/action/?param=val
 * Requires a rewrite rule in your .htaccess (not provided). Default: false
 */
// $config['use_url_rewrite'] = false;

/*
 * Fallback server name and port, used when the application is not able to get
 * the server name and port number.
 * Default:
 * 	fallback_server_name: 'localhost'
 * 	fallback_server_port: 80
 */
// $config['fallback_server_name'] = 'localhost';
// $config['fallback_server_port'] = 80;

/* Default number of users to show in the ranking page. */
// $config['default_per_page'] = 20;


/***********************
 * USER SESSION CONFIG *
 ***********************/

/*
 * Specifies how often the PHP session id should be regenerated.
 * Default: 300 (5 minutes)
 */
// $config['session_canary_lifetime'] = 60*5;

/* Length of generated auth tokens. Default: 20 */
// $config['auth_token_length'] = 20;

/* Duration of new auth tokens. Default 31536000 (~1 year) */
// $config['auth_token_duration'] = 60*60*24*365;


/**************************
 * FORM VALIDATION CONFIG *
 **************************/

/* Minimum number of characters required for users' password. Default: 8 */
// $config['min_password_length'] = 8;

/*
 * Regular expression used to check username validity.
 * Default: '/^[a-zA-Z0-9._-]{5,32}$/'
// $config['username_regex'] = '/^[a-zA-Z0-9._-]{5,32}$/';

/*
 * Regular expression used to check username validity (client-side: this regex
 * is used in the pattern attribute of HTML input field).
 * Usually, the same as above (or similar), without first and last /.
 * Default: '^[a-zA-Z0-9._-]{5,32}$'
 */
// $config['form_validation']['username_regex'] = '^[a-zA-Z0-9._-]{5,32}$';

/* Username max length. Default: 32 */
// $config['form_validation']['username_maxlength'] = 32;

/*
 * Regular expression used to check challenge's flag validity.
 * Default: '/^(?:f|F)(?:l|L)(?:a|A)(?:g|G)\{[ -z|~]{1,249}\}$/'
// $config['flag_regex'] = '/^(?:f|F)(?:l|L)(?:a|A)(?:g|G)\{[ -z|~]{1,249}\}$/';

/*
 * Regular expression used to check challenge's flag validity (client-side: this
 * regex is used in the pattern attribute of HTML input field).
 * Usually, the same as above (or similar), without first and last /.
 * Default: '^(?:f|F)(?:l|L)(?:a|A)(?:g|G)\{[ -z|~]+\}$'
 */
// $config['form_validation']['flag_regex'] = '^(?:f|F)(?:l|L)(?:a|A)(?:g|G)\{[ -z|~]+\}$';

/* Challenge's flag max length. Default: 255 */
// $config['form_validation']['flag_maxlength'] = 255;


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

/*
 * Default locale (used when the user's locale is not supported).
 * Default: 'en'
 */
// $config['default_locale'] = 'en';

/*
 * List of available languages on the language selector (footer).
 * Default: [ 'en', 'it' ]
 */
// $config['selector_languages'] = [ 'en', 'it' ];


/*****************
 * SOCIAL CONFIG *
 *****************/

/*
 * Social usernames. Add only the part after website.tld/. Default: all field
 * empty (disabled)
 */
// $config['social_names'] = [
//	'facebook' => '',
//	'instagram' => '',
//	'twitter' => '',
//	'youtube' => ''
// ];


/****************
 * DEBUG CONFIG *
 ****************/

/* Debug mode. When enabled, all errors are shown to the user. Default: false */
// $config['debug'] = true;

/*
 * When enabled, even caught errors/exceptions are shown to the user. Requires
 * debug=true and Xdebug extension. Default: false
 */
// $config['show_all_exceptions'] = true;

/* If TRUE always use specified fallback server name and port. Default: false */
// $config['use_fallback_server_infos'] = false;

/* Specifies the file where to save error logs. Default: null (disabled) */
// $config['error_log'] = null;
