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
        $above_first_option_codeblock = Codeblock::where('type', 'above first option')->first();
        $above_middle_option_codeblock = Codeblock::where('type', 'above middle option')->first();
        $above_last_option_codeblock = Codeblock::where('type', 'above last option')->first();
        return view('admin.codeblock.index', compact('header_codeblock', 'footer_codeblock','above_first_option_codeblock','above_middle_option_codeblock','above_last_option_codeblock'));
    }

    public function createorupdate(Request $request)
    {
        $modelH = Codeblock::where('type', 'header')->first();
        $modelH->codeblock = ($request->header_codeblock) ? $request->header_codeblock : null;
        $modelH->save();

        $modelF = Codeblock::where('type', 'footer')->first();
        $modelF->codeblock = ($request->footer_codeblock) ? $request->footer_codeblock : null;
        $modelF->save();

        $modelFO = Codeblock::where('type', 'above first option')->first();
        $modelFO->codeblock = ($request->above_first_option_codeblock) ? $request->above_first_option_codeblock : null;
        $modelFO->save();

        $modelMO = Codeblock::where('type', 'above middle option')->first();
        $modelMO->codeblock = ($request->above_middle_option_codeblock) ? $request->above_middle_option_codeblock : null;
        $modelMO->save();

        $modelLO = Codeblock::where('type', 'above last option')->first();
        $modelLO->codeblock = ($request->above_last_option_codeblock) ? $request->above_last_option_codeblock : null;
        $modelLO->save();

        return redirect()->route('codeblock')->with('success', 'Codeblock updated successfully');
    }
}
