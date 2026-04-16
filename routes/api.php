<?php
    use Illuminate\Support\Facades\Route;


    Route::get('/', function(){
        // echo "Hak Cipta Pengadilan Tinggi Bengkulu@2026";
        return response()->json(["Pengadilan Tinggi Bengkulu. Hak Cipta@2026"]);
    });
    Route::post('login', 'api\authController@login');
    Route::middleware(['jwt.auth', 'isSuperAdmin'])->group(function(){
        Route::get('list-citizen/{page}', 'api\citizenController@listCitizen');
        Route::get('detil-citizen/{slug}', 'api\citizenController@getDetilCitizen');
    });
    
?>