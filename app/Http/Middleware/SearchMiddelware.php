<?php

namespace App\Http\Middleware;

use App\Models\CountryTranslation;
use App\Models\HouseTypeTranslation;
use App\Models\RegionTranslation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class SearchMiddelware
{
    /**
     * possible uri's
     * /{type^country}/{country^region}{/region}
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $uri = $request->route('search');
        $uriList = explode('/', $uri);
        $country = null;
        $region = null;
       
        $houseTypes = Cache::remember('house_types', 10000, function () {
            return HouseTypeTranslation::whereLang(App::currentLocale())->orderBy('name', 'asc')->get();
        });
        $houseType = $houseTypes->keyBy('slug')->get(strtolower($uriList[0]));
        if(count($uriList) <= 2 && $houseType) {
            $country = CountryTranslation::whereSlug(strtolower($uriList[count($uriList)-1]))->first();
        } elseif(count($uriList) <= 2 && !$houseType) {
            $country = CountryTranslation::whereSlug(strtolower($uriList[0]))->get();
        } else {
            $region = RegionTranslation::whereSlug(strtolower($uriList[count($uriList)-1]))->first();
        }
        $result = [
            'country' => $country??null,
            'region' => $region??null,
            'houseType' => $houseType??null,
        ];
        $request->route()->setParameter('search', $result);
        return $next($request);
    }
}
