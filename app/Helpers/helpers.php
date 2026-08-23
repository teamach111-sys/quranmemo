<?php

declare(strict_types=1);

function deleteClass($model)
{
    $link = 'App\\Models\\' . $model;

    return $link;
}
