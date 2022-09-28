<?php

function getDateFormateView($date)
{
    return date('d-m-Y h:i a', strtotime($date));
}

function ddp($data = [])
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit();
    die;
}
