<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockSummaryController extends Controller
{
    /**
     * Generate asset stock summary report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset         $asset
     * @return \Illuminate\Http\Response
     */
    public function assetStockSummary(Request $request, Asset $asset)
    {
        $result = $asset->load([
            'receiveItems' => function ($query) {
                return $query->whereBetween('date', [request()->from, request()->to]);
            },
            'distributionItems' => function ($query) {
                return $query->whereHas('invoice', function ($query) {
                    return $query->whereBetween('date', [request()->from, request()->to]);
                });
            },
            'returnItems' => function ($query) {
                return $query->whereHas('invoice', function ($query) {
                    return $query->whereBetween('date', [request()->from, request()->to]);
                });
            },
            'consumes' => function ($query) {
                return $query->whereBetween('created_at', [request()->from, request()->to]);
            },
        ]);

        dump($result);
        return view('pdf.pages.asset-stock-summary', compact('asset'));
    }
}
