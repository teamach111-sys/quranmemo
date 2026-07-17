<?php

    function deleteClass($model)
    {
        $link = 'App\\Models\\' . $model;
        return $link;
    }
