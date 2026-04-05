<?php

function image_path(): string
{
    $project = config('app.slug');
    return $project . '/avatars';
}

function image_url($path = null): string | null
{
    $url = config('filesystems.disks.s3.url') . '/'. $path;
    return $path ? $url : null;
}
