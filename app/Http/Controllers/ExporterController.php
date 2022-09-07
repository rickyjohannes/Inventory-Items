<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RequesterExport;
use App\Http\Controllers\Controller;
use Excel;
use PDF;

class ExporterController extends Controller
{
    public function ERequester(){

        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new RequesterExport, 'requester.xlsx'); 
    }
}
