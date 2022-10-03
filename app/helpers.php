<?php

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

function ddp($data = [])
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit();
    die;
}
