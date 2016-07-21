<?php

// Set the site locale
i18n::set_locale('en_AU');

//-------------------------------------- Environment credentials

//--------------------- Local dev

$localEnv = [
    'dbserver'   => 'localhost',
    'dbusername' => 'chejjc',
    'dbpassword' => 'johan.christian',
    'dbname'     => 'che1277',
    'domains'    => [
        'ssready.local'
    ]
];

//--------------------- Staging

$stagingEnv = [
    'dbserver'   => 'localhost',
    'dbusername' => '',
    'dbpassword' => '',
    'dbname'     => '',
    'domains'    => [
        '{JOBCODE}.chdigital.com.au'
    ]
];

//---------------------  Production

$productionEnv = [
    'dbserver'   => 'localhost',
    'dbusername' => '',
    'dbpassword' => '',
    'dbname'     => '',
    'domains'    => [
        'cheproximity.com.au'
    ]
];

//-------------------------------------- Environment Check

global $databaseConfig;

$ssEnvironment;
$environmentVars;

if (in_array($_SERVER['SERVER_NAME'], $localEnv['domains'])) {
    $_SERVER['HTTP_HOST'] = $localEnv['domains'][0];
    $ssEnvironment        = 'dev';
    $environmentVars      = $localEnv;

    // Display errors
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);

    // Enable default admin for temporary access only - delete this account from the DB once you have set up your new account.
    Security::setDefaultAdmin('admin', 'password');
}

if (in_array($_SERVER['SERVER_NAME'], $stagingEnv['domains'])) {
    if ($deploymentArg == 'staging') {
        $_SERVER['HTTP_HOST'] = $stagingEnv['domains'][0];
    }
    $ssEnvironment   = 'test';
    $environmentVars = $stagingEnv;
}

if (in_array($_SERVER['SERVER_NAME'], $productionEnv['domains'])) {
    if ($deploymentArg == 'live') {
        $_SERVER['HTTP_HOST'] = $productionEnv['domains'][0];
    }
    $ssEnvironment   = 'live';
    $environmentVars = $productionEnv;
}

//-------------------------------------- CLI Variables

// Set silverstripe's file to url map variable
global $_FILE_TO_URL_MAPPING;

// Get the server root directory
$rootDirectory = str_replace('/site', '', __DIR__);

// Set the root path
$_FILE_TO_URL_MAPPING[$rootDirectory] = $environmentVars['domains'][0];

//-------------------------------------- Set Environment

Director::set_environment_type($ssEnvironment);

$databaseConfig = [
    'type'     => 'MySQLDatabase',
    'server'   => $environmentVars['dbserver'],
    'username' => $environmentVars['dbusername'],
    'password' => $environmentVars['dbpassword'],
    'database' => $environmentVars['dbname'],
    'path'     => ''
];

//-------------------------------------- Set constant strings

// Initialise constant strings
$config = [
  'path' => [
    'initialJadeTemplate' => '../../../components/app/template'
  ]
];
