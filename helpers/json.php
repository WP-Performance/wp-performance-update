<?php

namespace WPPerformance\helpers;

function createJsonInfos(string $distDir, string $tag_name, string $zip_url, string $last_updated, string $changelog): void
{
    $data = [
        'name' => $_ENV['INFO_NAME'],
        'slug' => $_ENV['INFO_SLUG'],
        'author' => $_ENV['INFO_AUTHOR'],
        'author_profile' => $_ENV['INFO_AUTHOR_URL'],
        'version' => $tag_name,
        'download_url' => $zip_url,
        'requires' => $_ENV['INFO_WP_MIN_VERSION'],
        'tested' => $_ENV['INFO_WP_TESTED_VERSION'],
        'requires_php' => $_ENV['INFO_PHP_MIN_VERSION'],
        'last_updated' => $last_updated,
        'sections' => [
            'description' => $_ENV['INFO_DESCRIPTION'],
            'installation' => $_ENV['INFO_INSTALLATION'],
            'changelog' => $changelog,
        ],

    ];
    $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
    $filename = 'info.json';

    file_put_contents(dirname(__FILE__) . '/../' . $distDir . $filename, $jsonData);
}
