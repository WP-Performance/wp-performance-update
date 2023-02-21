<?php

namespace WPPerformance\helpers;

function getLastRelease(string $owner, string $repo, string $token): array | null
{
    // load infos from releases
    $url = 'https://api.github.com/repos/' . $owner . '/' . $repo . '/releases';
    $options = [
        'http' => [
            'header' => [
                'User-Agent: PHP',
                'Authorization: Bearer ' . $token
            ]
        ]
    ];
    $context = stream_context_create($options);
    $data = file_get_contents($url, false, $context);
    $releases = json_decode($data, true);

    // take last release
    return $releases[0] ?? null;
}
