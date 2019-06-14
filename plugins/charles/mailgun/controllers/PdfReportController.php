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

class PdfReportController {

    public function index($user_id, $sendDate=null)
    {
        trace_log($sendDate);
        $myDay;
        if($sendDate != null ) {
            trace_log('la date n est pas null');
            $myDay = Carbon::createFromFormat('d-m-Y', $sendDate );
        } else {
            $myDay = Carbon::today();
        }
        /**
         * Gestion des dates
         */
        $contact = Contact::with('client')->find($user_id);
        $regionId = $contact->region->id;
        $commercials = Region::find($regionId)->commercials();

        $data['contact'] = $contact;

        $compostings = new Collection();
        foreach ($contact->client->cloudis as $cloudi) {
            $compostings->put($cloudi->name, $cloudi->pivot->url );
        }

        $data['compostings'] = $compostings;

        trace_log($myDay);
        $lastWeekStart = $myDay->copy()->subWeek()->startOfWeek();
        $lastWeekEnd = $myDay->copy()->subWeek()->endOfWeek();
        $actualWeek = $lastWeekEnd->weekOfYear;
        $actualMonth = $myDay->month-1;

        $data['global']['date'] = $myDay;
        $data['global']['lastWeekStart'] = $lastWeekStart;
        $data['global']['lastWeekEnd'] = $lastWeekEnd;
        $data['global']['actualWeek'] = $actualWeek;
        $data['global']['actualMonth'] = $actualMonth;

        $startYear = $myDay->copy()->startOfYear();

        $region = [];        
        
        $region['name'] =  Region::find($regionId)->name;
        $region['commercial_count'] =  $commercials->count();
        $coms = new collection();
        foreach($commercials->get() as $commercial) {
            $compilSales = $commercial->sales()->whereBetween('sale_date', [$startYear, $myDay]);
            $weekSales = $commercial->sales()->whereBetween('sale_date', [$lastWeekStart, $lastWeekEnd]);
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

        $regionMonthSales = [];
        for ($i =1; $i<=$actualMonth; $i++) {
            $regionMonthSales[$i] = intVal(Region::find($regionId)->sales()->whereMonth('sale_date', $i)->sum('amount'));
        }
        $region['month_sales'] = $regionMonthSales;

        $all = [];   
        $all['commercial_count'] =  Commercial::count();
        $allComs = new collection();
        foreach(Commercial::get() as $commercial) {
            $compilSales = $commercial->sales()->whereBetween('sale_date', [$startYear, $myDay]);
            $weekSales = $commercial->sales()->whereBetween('sale_date', [$lastWeekStart, $lastWeekEnd]);
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

        $allMonthSales = [];
        for ($i =1; $i<=$actualMonth; $i++) {
            $allMonthSales[$i] = intVal(Sale::whereMonth('sale_date', $i)->sum('amount'));
        }
        $all['month_sales'] = $allMonthSales;

        $AllRepartitionGammes = [];
        $regionRepartitionGammes = [];
        $gammes = Gamme::get();
        foreach($gammes as $gamme) {
           
            $regionRepartitionGammes[$gamme->name] = (int)$gamme->sales()
                ->whereBetween('sale_date', [$lastWeekStart, $lastWeekEnd])
                ->where('region_id', $regionId)
                ->sum('amount');
            $AllRepartitionGammes[$gamme->name] = (int)$gamme->sales()->whereBetween('sale_date', [$lastWeekStart, $lastWeekEnd])->sum('amount');
        }
        $region['repartition_gammes'] = $regionRepartitionGammes;
        $all['repartition_gammes'] = $AllRepartitionGammes;


        $data['region'] = $region;
        $data['all'] = $all;

        $oldReport = [];
        $y = 0;
        for ($i =$actualWeek; $i>5; $i--) {
            $oldReport["S".$i] = "https://admin.charles-saint-olive.com/maker/pdfreport/".$contact->id."/".$myDay->copy()->subWeek($y)->format('d-m-Y');
            $y++;
        }
        $data['old_report'] = $oldReport;
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






        return $data ;
    }

}