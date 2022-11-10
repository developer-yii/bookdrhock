<?php

use App\Model\PollCategory;

function getDateFormateView($date)
{
    return date('d-m-Y h:i a', strtotime($date));
}

function convert_number($number)
{
    if (($number < 0) || ($number > 999999999)) {
        throw new Exception("Number is out of range");
    }
    $giga = floor($number / 1000000);
    // Millions (giga)
    $number -= $giga * 1000000;
    $kilo = floor($number / 1000);
    // Thousands (kilo)
    $number -= $kilo * 1000;
    $hecto = floor($number / 100);
    // Hundreds (hecto)
    $number -= $hecto * 100;
    $deca = floor($number / 10);
    // Tens (deca)
    $n = $number % 10;
    // Ones
    $result = "";
    if ($giga) {
        $result .= convert_number($giga) .  "million";
    }
    if ($kilo) {
        $result .= (empty($result) ? "" : " ") . convert_number($kilo) . " thousand";
    }
    if ($hecto) {
        $result .= (empty($result) ? "" : " ") . convert_number($hecto) . " hundred";
    }
    $ones = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eightteen", "nineteen");
    $tens = array("", "", "twenty", "thirty", "fourty", "fifty", "sixty", "seventy", "eigthy", "ninety");
    if ($deca || $n) {
        if (!empty($result)) {
            $result .= " and ";
        }
        if ($deca < 2) {
            $result .= $ones[$deca * 10 + $n];
        } else {
            $result .= $tens[$deca];
            if ($n) {
                $result .= " " . $ones[$n];
            }
        }
    }
    if (empty($result)) {
        $result = "zero";
    }
    return $result;
}

function getCategoryName($slug = '')
{
    if (isset($slug) && !empty($slug)) {
        $category = PollCategory::query()
            ->where('slug', $slug)
            ->first();

        if (isset($category) && !empty($category)) {
            return $category->name;
        } else {
            return "";
        }
    } else {
        return "";
    }
}

function ddp($data = [])
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit();
    die;
}

function getImagePath($filename = "", $foldername = "", $type = "poll_options")
{
    if ($type == 'poll_feature_image') {
        $path = $foldername;
    } else {
        $path = $foldername . "/option_images";
    }
    $oldfileExists = storage_path('app/public/poll/' . $path) . '/' . $filename;
    if ($filename != "" && file_exists($oldfileExists)) {
        return asset('/storage/poll/' . $path . '/' . $filename);
    } else {
        return "";
    }
}

function cacheclear()
{
    return time();
}

function addAdminJsLink($link)
{
    return asset('assets/js/admin') . "/" . $link . '?' . time();
}

function checkBlockedIP(){
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)){
        $clientIp = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $clientIp = $forward;
    }
    else{
        $clientIp = $remote;
    }
    //$clientIp = "101.110.111.255";
    $ipdat = @json_decode(file_get_contents("http://ip-api.com/json/".$clientIp),true);
    $country = "";
    if(isset($ipdat['status']) && strtolower($ipdat['status']) == "success"){
        \Log::info($clientIp);
        $country = (isset($ipdat['country']) && $ipdat['country'])? strtolower($ipdat['country']) : "";
    }    
    return ($country && in_array($country, ['china']))? true : false; 
}
