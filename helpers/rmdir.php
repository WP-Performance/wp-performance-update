<?php

namespace WPPerformance\helpers;

function force_rmdir($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? force_rmdir("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}
