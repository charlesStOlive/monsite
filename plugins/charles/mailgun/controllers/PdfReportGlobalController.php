<?php

namespace Charles\Mailgun\Controllers;

use Config;
use Illuminate\Http\Request;
use October\Rain\Support\Collection;
use Carbon\Carbon;


use Charles\Mailgun\Models\Contact;
use Charles\Crm\Models\Region;
use Charles\Crm\Models\Commercial;
use Charles\Crm\Models\Gamme;
use Charles\Crm\Models\Sale;
use Charles\Mailgun\Models\Log;


class PdfReportGlobalController {


    public function getRegions($region_id) {
        return Region::get();
    }

    public function index($user_id, $region_id,$is_pdf, $sendDate=null)
    {
        
        


        /**
         * Gestion des dates
         */
        $myDay;
        $realDay = Carbon::today()->subWeek();
        if($sendDate != null ) {
            trace_log('la date n est pas null');
            $myDay = Carbon::createFromFormat('d-m-Y', $sendDate );
        } else {
            $myDay = Carbon::today()->subWeek();
        }
        $weekStart = $myDay->copy()->startOfWeek();
        $weekEnd = $myDay->copy()->endOfWeek();
        $weekNum = $weekEnd->weekOfYear;
        $startYear = $myDay->copy()->startOfYear();
        //
        $contact = Contact::with('client')->where('key', $user_id)->first();
        //
        $data['regions'] =  $this->getRegions($region_id);
        $regionId = $region_id;
        $commercials = Region::find($regionId)->commercials();

        $data['contact'] = $contact;

        // $compostings = new Collection();
        // foreach ($contact->cloudis as $cloudi) {
        //     $compostings->put($cloudi->name, $cloudi->pivot->url );
        // }

        // $data['compostings'] = $compostings;
        $data['global']['regionId'] = $regionId;
        $data['global']['date'] = $myDay->format('d-m-Y');
        $data['global']['weekStart'] = $weekStart->format('d-m-Y');
        $data['global']['weekEnd'] = $weekEnd->format('d-m-Y');
        $data['global']['weekNum'] = $weekNum;

        

        $region = [];        
        
        $region['name'] =  Region::find($regionId)->name;
        $region['commercial_count'] =  $commercials->count();
        $coms = new collection();
        foreach($commercials->get() as $commercial) {
            $compilSales = $commercial->sales()->whereBetween('sale_date', [$startYear, $myDay]);
            $weekSales = $commercial->sales()->whereBetween('sale_date', [$weekStart, $weekEnd]);
            $coms->put($commercial->name, [
                    'clients_count'=> $commercial->clients()->count(),
                    'compil_sales_sum' => intval($compilSales->sum('amount')),
                    'compil_sales_count' => $compilSales->count(), 
                    'week_sales_sum' => intval($weekSales->sum('amount')),
                    'week_sales_count' => $weekSales->count(), 
                    'name' => $commercial->name 
            ]);
        }
        $region['compil_total_sales'] = $coms->sum('compil_sales_sum');
        $region['week_total_sales'] = $coms->sum('week_sales_sum');
        $region['commercials'] = $coms;
        $region['best_compil_sales_sum'] =  $coms->sortByDesc('compil_sales_sum')->take(3);
        $region['best_week_sales_sum'] =  $coms->sortByDesc('week_sales_sum')->take(3);

        // $regionMonthSales = [];
        // for ($i =1; $i<=$actualMonth; $i++) {
        //     $regionMonthSales[$i] = intVal(Region::find($regionId)->sales()->whereMonth('sale_date', $i)->sum('amount'));
        // }
        // $region['month_sales'] = $regionMonthSales;

        $regionWeekSales = [];
        for ($i =0; $i<=6; $i++) {
            $date = $weekStart->copy()->addDay($i);
            $regionWeekSales[$date->format('d-m-Y')] = intVal(Region::find($regionId)->sales()->whereDate('sale_date', $date->format('Y-m-d'))->sum('amount'));
        }
        $region['week_sales'] = $regionWeekSales;

        $all = [];   
        $all['commercial_count'] =  Commercial::count();
        $allComs = new collection();
        foreach(Commercial::get() as $commercial) {
            $compilSales = $commercial->sales()->whereBetween('sale_date', [$startYear, $myDay]);
            $weekSales = $commercial->sales()->whereBetween('sale_date', [$weekStart, $weekEnd]);
            $allComs->put($commercial->name, [
                    'clients_count'=> $commercial->clients()->count(),
                    'compil_sales_sum' => intval($compilSales->sum('amount')),
                    'compil_sales_count' => $compilSales->count(), 
                    'week_sales_sum' => intval($weekSales->sum('amount')),
                    'week_sales_count' => $weekSales->count(),
                    'region' => $commercial->region->name, 
                    'name' => $commercial->name 
            ]);
        }
        $all['compil_total_sales'] = $allComs->sum('compil_sales_sum');
        $all['week_total_sales'] = $allComs->sum('week_sales_sum');
        $all['best_compil_sales_sum'] =  $allComs->sortByDesc('compil_sales_sum')->take(3);
        $all['best_week_sales_sum'] =  $allComs->sortByDesc('week_sales_sum')->take(3);

        // $allMonthSales = [];
        // for ($i =1; $i<=$actualMonth; $i++) {
        //     $allMonthSales[$i] = intVal(Sale::whereMonth('sale_date', $i)->sum('amount'));
        // }
        // $all['month_sales'] = $allMonthSales;

        $allWeekSales = [];
        for ($i =0; $i<=6; $i++) {
            $date = $weekStart->copy()->addDay($i);
            $allWeekSales[$date->format('d-m-Y')] = intVal(Sale::whereDate('sale_date', $date->format('Y-m-d'))->sum('amount'));
        }
        $all['week_sales'] = $allWeekSales;

        $AllRepartitionGammes = [];
        $regionRepartitionGammes = [];
        $gammes = Gamme::get();
        foreach($gammes as $gamme) {
           
            $regionRepartitionGammes[$gamme->name] = (int)$gamme->sales()
                ->whereBetween('sale_date', [$weekStart, $weekEnd])
                ->where('region_id', $regionId)
                ->sum('amount');
            $AllRepartitionGammes[$gamme->name] = (int)$gamme->sales()->whereBetween('sale_date', [$weekStart, $weekEnd])->sum('amount');
        }
        $region['repartition_gammes'] = $regionRepartitionGammes;
        $all['repartition_gammes'] = $AllRepartitionGammes;


        $data['region'] = $region;
        $data['all'] = $all;

        $oldReport = [];
        $reportWeekStart = $realDay->copy()->startOfWeek();
        $reportWeekNum = $reportWeekStart->weekOfYear;

        $y = 0;
        for ($i =$reportWeekNum; $i>5; $i--) {
            $url = null;
            if($is_pdf) {
                $oldReport["S".$i] = "https://admin.charles-saint-olive.com/maker/pdfreport/".$contact->key."/".$regionId."/".$is_pdf."/".$myDay->copy()->subWeek($y)->format('d-m-Y');
            }  else {
                $oldReport[$y] = [
                    "name" => "Semaine : ".($reportWeekNum-$y),
                    "date" => $reportWeekStart->copy()->subWeek($y)->format('d-m-Y')
                
                ];
            }
            $y++;
        }
        $data['old_report'] = $oldReport;
        if($is_pdf) {
            /**
             * Construction du pdf
             */
            try {
                /** @var PDFWrapper $pdf */
                $pdf = app('dynamicpdf');

                $options = [
                    'logOutputFile' => storage_path('temp/log.htm'),
                    'isRemoteEnabled' => true,
                ];

                return $pdf
                    ->setPaper('A4', 'landscape')
                    ->loadTemplate('report', compact('data'))
                    ->setOptions($options)
                    ->stream();

            } catch (Exception $e) {
                throw new ApplicationException($e->getMessage());
            }

        } else {
            return $data;
        }

    }

}