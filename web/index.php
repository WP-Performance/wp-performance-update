<?php

namespace WPPerformance;

use Dotenv\Dotenv;
use function WPPerformance\helpers\downloadZip;
use function WPPerformance\helpers\extractZip;
use function WPPerformance\helpers\force_rmdir;
use function WPPerformance\helpers\getLastRelease;
use function WPPerformance\helpers\zipFinal;

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../helpers/releases.php');
require_once(dirname(__FILE__) . '/../helpers/zip.php');
require_once(dirname(__FILE__) . '/../helpers/rmdir.php');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// github infos
$owner = $_ENV['GITHUB_OWNER'];
$repo = $_ENV['GITHUB_REPO'];
$token = $_ENV['GITHUB_TOKEN'];

// get infos releases
$last = getLastRelease($owner, $repo, $token);

if (!$last) {
    echo 'No release found';
    exit;
}

// tag release
$tag_name = $last['tag_name'];
// zipball url release
$zipball_url = $last['zipball_url'];

// download zip file for last release
downloadZip($tag_name, $zipball_url, $token);

$distDir = 'web/dist/';
// path for dir
$dir = dirname(__FILE__) . '/../' . $distDir . $repo;

// delete dir if exists
if (file_exists($dir)) {
    force_rmdir($dir);
    echo "Dir $repo deleted.";
}

// recreate dir
mkdir($dir, 0777, true);

// extract zip release
extractZip("$tag_name.zip", $dir, $repo);

// delete first zip
unlink("$tag_name.zip");

// zip folder with good structure and name
zipFinal($dir, $repo, $distDir);

// delete dir
force_rmdir($dir);
