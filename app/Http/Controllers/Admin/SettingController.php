<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = [];
        return view('admin.setting.index', compact('settings'));
    }

    public function createorupdate(Request $request)
    {
        ddp($request->all());
    }
}
