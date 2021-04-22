<?php

use App\Http\Controllers\HomeController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use PragmaRX\Countries\Package\Services\Countries;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {

    return view('dashboard');

})->name('dashboard');


Route::get('add-permission',function(Request $request){

    $user=User::where('name','customer')->first();
    dd($user->can('browse orders'),$user->getPermissionsViaRoles());

});


Route::get('update-exchange-rate',[HomeController::class,'UpdateExchangeRate']);
Route::get('currencyConversion/{from}/{amount}/{to}',[HomeController::class,'currencyConversion']);
Route::get('countries',function(Request $request)  {
//    loginSuccessCall('103.115.196.209');
dd(localizeDateTime($request->user()->created_at));
});
Route::get('lang/{lang}',function(Request $request){

//    dd($request->user()->rate()->first());
//    App::setLocale($request->lang);
//    dd(__('this is the title'));


//    $utc_date=Auth::user()->created_at;
//    $utc_date1=Auth::user()->created_at;
//    $utc_date2=Auth::user()->created_at;
//    $utc_date3=Auth::user()->created_at;
//    dd($utc_date,$utc_date2->setTimeZone('Europe/Paris'),$utc_date1->setTimeZone('Asia/Karachi'),$utc_date3->setTimeZone('America/Toronto'));

    $num = NumberFormatter::create('en_US', NumberFormatter::DECIMAL);
    echo  "US: ".$num->format(123123.12);
    echo "<br/>";
    $num1 = NumberFormatter::create('de', NumberFormatter::DECIMAL);
    echo  "Germany: ".$num1->format(123123.12);
    echo "<br/>";
    $num2 = NumberFormatter::create('fr', NumberFormatter::DECIMAL);
    echo "France: ".$num2->format(123123.12);

    echo "-------------------------------------------------<br/>";

    $currency1 = NumberFormatter::create('de_DE', NumberFormatter::CURRENCY);
    echo "Germany: ".$currency1->format(50);
    echo "<br/>";
    $currency2 = NumberFormatter::create('en_UG', NumberFormatter::CURRENCY);
    echo "US: ".$currency2->format(50);

});
