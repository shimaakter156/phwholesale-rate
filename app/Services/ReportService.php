<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getMarketRate($region, $date)
    {
        $products = DB::table('WholeSaleMarketRate as pr')
            ->join('Location as l', 'l.LocationCode', '=', 'pr.LocationCode')
            ->join('product as p', 'p.ProductCode', '=', 'pr.ProductCode')
            ->where('pr.LocationCode', $region)
            ->whereDate('pr.EntryDate', $date)
            ->select(
                'p.ProductName',
                'l.LocationName as RegionName',
                'pr.CompanyPrice as CRate',
                'pr.MarketPrice as MRate',
                DB::raw('(pr.CompanyPrice - pr.MarketPrice) as Diff'),
                DB::raw('ROUND(((pr.CompanyPrice - pr.MarketPrice) / pr.CompanyPrice) * 100, 2) as PercentURate')
            )
            ->orderBy('p.ProductName')
            ->get();

        $avg = [
            'CRate'        => round($products->avg('CRate'), 2),
            'MRate'        => round($products->avg('MRate'), 2),
            'Diff'         => round($products->avg('Diff'), 2),
            'PercentURate' => round($products->avg('PercentURate'), 2),
        ];

        return [
            'products' => $products,
            'average'  => $avg
        ];
    }
}