<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'polls';

    public static $capthcaType = [
        '0' => 'No captcha',
        '1' => 'Google recaptcha',
        '2' => 'Maths captcha',
    ];

    public static function getImagePath($filename = "", $foldername = "", $type = "poll_options")
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

    public static function getImageStoragePath($filename = "", $foldername = "", $type = "poll_options")
    {
        if ($type == 'poll_feature_image') {
            $path = $foldername;
        } else {
            $path = $foldername . "/option_images";
        }
        $oldfileExists = storage_path('app/public/poll/' . $path) . '/' . $filename;
        if ($filename != "" && file_exists($oldfileExists)) {
            return $oldfileExists;
        } else {
            return "";
        }
    }
}
