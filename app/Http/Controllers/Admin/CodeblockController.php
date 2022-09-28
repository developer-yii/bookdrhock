<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CodeblockController extends Controller
{
    public function index(Request $request)
    {
        $codeblock = [];
        return view('admin.codeblock.index', compact('codeblock'));
    }

    public function createorupdate(Request $request)
    {
        ddp($request->all());
    }
}
