<?php

namespace App\Http\Controllers\Api;

use App\Model\Information;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformationController extends Controller
{
    public function index()
    {
        $informations = DB::table('informations')->orderBy('created_at', 'desc')->get();
        
        return $informations;
    }
}
