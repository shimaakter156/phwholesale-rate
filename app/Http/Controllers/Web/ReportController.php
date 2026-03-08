<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WholeSaleMarketRate;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;
    public function __construct(ReportService $reportService){
        $this->reportService = $reportService;
    }
    public function marketRateReport(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        $wsd = WholeSaleMarketRate::select('LocationCode')
            ->where('EntryDate', '=', $date)
            ->distinct()
            ->get();

        $regions = $wsd->pluck('LocationCode')->toArray();
        $data = [];
        foreach ($regions as $region) {
            $data[$region] = $this->reportService->getMarketRate($region, $date);
        }

        return response()->json([
            'date' => $date,
            'regions' => $data
        ]);
    }
}
