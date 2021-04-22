<?php

use App\Models\ExchangeRate;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PragmaRX\Countries\Package\Services\Countries;

if (! function_exists('localizeDateTime')) {
    /**
     * @param $dateTime Carbon
     * @param null $timeZone
     * @return mixed
     */
    function localizeDateTime($dateTime){
            $user=request()->user();
           if($user && $user->detail){

               $timeZone=$user->detail->timezone;
               return $dateTime->setTimeZone($timeZone);
           }else{
               return $dateTime;
           }

    }
}
if (!function_exists('currencyConversion')) {

    /**
     * @param $amount 'string'
     */
    function currencyConversion($amount,$base=null,$destination=null){
        $user=request()->user();

        if(is_null($base)){
            $base=config('app.base_currency');

        }
        if(is_null($destination)){
            if($user && $user->detail){
                $destination=$user->detail->currency;
            }else{
                $destination=config('app.base_currency');

            }
        }
        $baseCurrency=ExchangeRate::where('code',$base)->first();
        $destinationCurrency=ExchangeRate::where('code',$destination)->first();

        if(!is_null($baseCurrency) && !is_null($destinationCurrency)){
            return (floatval($amount) / floatval($baseCurrency->exchange_rate)) * $destinationCurrency->exchange_rate;
        }
    }

    if (!function_exists('formatNumber')) {
        function formatNumber($number){
            $user=request()->user();
            if($user && $user->detail){
                $countryLocale=$user->detail->country_locale;
            }else{
                $countryLocale=config('app.locale');

            }
            $num = NumberFormatter::create($countryLocale, NumberFormatter::DECIMAL);
            return  $num->format($number);

        }
    }

     if (!function_exists('formatCurrency')) {
            function formatCurrency($amount){
                $user=request()->user();
                if($user && $user->detail){
                    $countryLocale=$user->detail->country_locale;
                }else{
                    $countryLocale=config('app.locale');

                }
                $currency= NumberFormatter::create($countryLocale, NumberFormatter::CURRENCY);
               return $currency->format($amount);

            }
        }

        if (!function_exists('locationAgainstIp')) {
            function locationAgainstIp($ip){
                $url="http://api.ipstack.com/$ip?access_key=".config('app.ip_stack_key');
                $response=  Http::get($url);
                return $response->collect();
            }
        }

        if (!function_exists('getCountryDetail')) {
            function getCountryDetail($country){
                $countries = new Countries();
                $language= $countries->all()->where('name.common',$country)->first();
                $lang='';
                $currency='';
                $timezone='';
                if(count($language['languages'])> 0){
                    $lang=substr($language['languages']->keys()->first(),0,2);
                }
                if(count($language['currencies'])> 0){
                    $currency=$language['currencies'][0];
                }
                $timezone=$countries->where('name.common', $country)->first()->hydrate('timezones')->timezones->first()->zone_name;

                return [
                    'lang'=>$lang,
                    'timezone'=>$timezone,
                    'currency'=>$currency,
                    'country_locale'=>strtolower($language['cca2'])

                ];

            }
        }

 if (!function_exists('loginSuccessCall')) {
            function loginSuccessCall($ip){
                $response=locationAgainstIp($ip);
               if($response->has('country_name')){
                  $detail= getCountryDetail($response['country_name']);
                   $detail['language']=$detail['lang'] ?? config('app.locale');
                   $detail['timezone']=$detail['timezone'] ?? config('app.timezone');
                   $detail['currency']=$detail['currency'] ?? 'USD123';
                   $detail['country_locale']=$detail['lang'] ?? config('app.locale');
               }
                $id=request()->user()->id;
               $detail['user_id']=$id;
               UserDetail::updateOrCreate(['user_id'=>$id],$detail);

            }
        }




    }
