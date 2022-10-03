<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Codeblock;

class CodeblockController extends Controller
{
    public function index(Request $request)
    {
        $header_codeblock = Codeblock::where('type', 'header')->first();
        $footer_codeblock = Codeblock::where('type', 'footer')->first();
        return view('admin.codeblock.index', compact('header_codeblock', 'footer_codeblock'));
    }

    public function createorupdate(Request $request)
    {
        $modelH = Codeblock::where('type', 'header')->first();
        $modelH->codeblock = ($request->header_codeblock) ? $request->header_codeblock : null;
        $modelH->save();

        $modelF = Codeblock::where('type', 'footer')->first();
        $modelF->codeblock = ($request->footer_codeblock) ? $request->footer_codeblock : null;
        $modelF->save();

        return redirect()->route('codeblock')->with('success', 'Codeblock updated successfully');
    }
}
