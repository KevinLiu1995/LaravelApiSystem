<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
class CaptchasController extends Controller
{
    /**
     * 生成验证码
     *
     * @param CaptchaBuilder $captchaBuilder
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function store(CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . Str::random(10);

        $captcha = $captchaBuilder->build();

        //缓存code
        Cache::put($key, ['code' => $captcha->getPhrase()], config('app.image_captcha_ttl',60));

        $data = [
            'captcha_key' => $key,
            'captcha_image_content' => $captcha->inline()
        ];

        return responseData($data);
    }
}
