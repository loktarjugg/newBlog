<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SetController extends ApiController
{
    const CACHE_KEY = 'settings';

    public function index()
    {
       if (! Cache::has(self::CACHE_KEY)){
           $this->initCache();
       }

       $config = Cache::get(self::CACHE_KEY);

        return $this->jsonRespond($config);
    }

    public function update(Request $request)
    {
        return $this->initCache($request->except('_token','_url'));
    }

    protected function initCache($config = null)
    {
        $config = $config?:config('personal.config');

        Cache::forever(self::CACHE_KEY , $config);

        return Cache::get(self::CACHE_KEY);
    }
}
