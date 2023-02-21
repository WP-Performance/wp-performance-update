<?php

namespace WPPerformance\helpers;

/**
 * Download a zip file from a github release
 */
function downloadZip(string $tag_name, string $zipball_url, string $token): void
{
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                "Authorization: Bearer $token",
                'User-Agent: PHP'
            ]
        ]
    ];
    $filename = "$tag_name.zip";

    // download zip file
    $context = stream_context_create($opts);
    $zipFile = file_get_contents($zipball_url, false, $context);
    file_put_contents($filename, $zipFile);
}


/**
 * Extract a zip file
 */
function extractZip(string $filename, string $dir, string $repo): void
{
    // extract zip file
    $zip = new \ZipArchive();
    $res = $zip->open($filename);

    if ($res === TRUE) {
        $zip->extractTo($dir);
    } else {
        echo 'Fail to extract zip files.';
    }

    $extractedFolder = basename($zip->getNameIndex(0, \ZipArchive::FL_NODIR));
    rename($dir . '/' . $extractedFolder, $dir . '/' . $repo);

    $zip->close();
}


/**
 * Zip a folder with good structure and name
 */
function zipFinal(string $dir, string $repo, string $distDir): void
{
    $zip = new \ZipArchive();
    $res = $zip->open(dirname(__FILE__) . '/../' . $distDir  . $repo . '.zip', \ZipArchive::CREATE);
    if ($res === TRUE) {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($dir) + 1);
                // repo in repo zip
                $zip->addFile($filePath, $repo . '/' . $relativePath);
            }
        }
        $zip->close();
    }
}
