<?php

namespace App\Http\Controllers;

use App\Services\masterDataService;
use Illuminate\Http\Request;

class masterDataController extends Controller
{
    protected $masterDataService;
    public function __construct(masterDataService $master_data_service)
    {
        // throw new \Exception('Not implemented');
        $this->masterDataService = $master_data_service;
    }

    public function listBagian($page){
        $get_data = $this->masterDataService->listBagian($page);
    }
}
