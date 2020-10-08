<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        return response()->download( Storage::path($filename), $filename )->deleteFileAfterSend(true);
    }
}
