<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    /**
     * Delete file after download
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string
     * @return \Illuminate\Http\Response
     */
    public function dumpDownload($filename, Request $request)
    {
        return response()->download( storage_path($filename), $filename )->deleteFileAfterSend(true);
    }
}
