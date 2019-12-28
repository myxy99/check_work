<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TestExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExportRequest;
use App\Http\Requests\Admin\SearchRequest;
use App\Models\punch_time_records;
use Maatwebsite\Excel\Facades\Excel;


class StatisticController extends Controller
{
    public function getSearch(SearchRequest $request){
        $statistics=punch_time_records::getSearch($request['department']);
        return $statistics != null ?
            response()->success(200, '获取成功!', $statistics) :
            response()->fail(100, '获取失败!');
    }
    public function getalldata(){
        $statistics=punch_time_records::getall();
        return $statistics != null ?
            response()->success(200, '获取成功!', $statistics) :
            response()->fail(100, '获取失败!');
    }
    public function getexport(ExportRequest $request){
        $date = date('Y-m-n',time());
        return Excel::download(new TestExport($request['startdate'],$request['enddate']),$date.'.xlsx');
    }
}
