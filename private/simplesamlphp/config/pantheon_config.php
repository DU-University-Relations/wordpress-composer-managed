<?php

/**
 * @file
 * SimpleSamlPhp Pantheon Configuration.
 *
 * All custom changes below. Modify as needed.
 */

if (!ini_get('session.save_handler')) {
  ini_set('session.save_handler', 'file');
}

// $ps = json_decode($_SERVER['PRESSFLOW_SETTINGS'], TRUE);
// $db = $ps['databases']['default']['default'];

$host = $_SERVER['HTTP_HOST'];
if(isset($_ENV['PANTHEON_ENVIRONMENT'])){
  $db = array(
      'host'      => $_ENV['DB_HOST'],
      'database'  => $_ENV['DB_NAME'],
      'username'  => $_ENV['DB_USER'],
      'password'  => $_ENV['DB_PASSWORD'],
      'port'      => $_ENV['DB_PORT'],
  );
} else {
  $db = array(
      'host'      => 'db',
      'database'  => 'db',
      'username'  => 'db',
      'password'  => 'db',
      'port'      => '3306',
  );
}

/**
 * Cookies No Cache.
 *
 * Allow users to be automatically logged in if they signed in via the same
 * SAML provider on another site.
 *
 * Warning: This has performance implications for anonymous users.
 *
 * @link https://docs.acquia.com/articles/using-simplesamlphp-acquia-cloud-site
 */
setcookie('NO_CACHE', '1');

// CONFIGURATION OPTIONS
/**
 * Multi-site installs.
 *
 * Support multi-site installations at different base URLs.
 * SAML should always connect via 443
 */
//$config['baseurlpath'] = 'https://'. $host .':443/simplesaml/';
$config['baseurlpath'] = 'https://'. $host .'/simplesaml/';
/*
 * The 'application' configuration array groups a set configuration options
 * relative to an application protected by SimpleSAMLphp.
 */
$config['application']['baseURL'] = 'https://'. $host . ':443';
//$config['application']['baseURL'] = 'https://'. $host;
$config['certdir'] = 'cert/';
$config['loggingdir'] = $_ENV['HOME'] . '/code/docroot/sites/default/files/private/';
$config['logging.handler'] = 'file';

$config['datadir'] = 'data/';
$config['tempdir'] = $_ENV['HOME'] . '/tmp/';

// DB config. Set SQL database session storage.
$config['store.type'] = 'sql';
$config['store.sql.dsn'] = 'mysql:host='. $db['host'] .';port='. $db['port'] .';dbname='. $db['database'];
$config['store.sql.username'] = $db['username'];
$config['store.sql.password'] = $db['password'];
$config['store.sql.prefix'] = 'simplesaml';

$config['trusted.url.domains'] = [$host . ':443', $host];

// Set some security and other configs that are set above, however we
// overwrite them here to keep all changes in one area.
$config['technicalcontact_name'] = "Paul Huckins";
$config['technicalcontact_email'] = "paul.huckins@du.edu";

// Change these for your installation.
$config['secretsalt'] = 'y0h9d13pki9qdhfm3l5nws4jjn55j6hj';
$config['auth.adminpassword'] = '66aBH$ATv*k@Zm^';

// SSL terminated at the ELB/balancer so we correctly set the SERVER_PORT
// and HTTPS for SimpleSAMLphp baseurl configuration.
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $_SERVER['SERVER_PORT'] = 443;
  $_SERVER['HTTPS'] = 'true';
}
