<?php

/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2020/3/4
 * Time: 12:00
 * 阿里云短信类 4
 */
namespace App\Services;
use App\Models\Configuration;
use Mrgoon\AliSms\AliSms;
class AliyunSms
{
//验证码
    const VERIFICATION_CODE = 'verification_code';

    //模板CODE
    public static $templateCodes = [
        self::VERIFICATION_CODE => 'SMS_178500248',
    ];

    /**
     * 发送短信
     */
    public static function sendSms($mobile, $scene, $params = [])
    {
        if (empty($mobile)) {
            throw new \Exception('手机号不能为空');
        }

        if (empty($scene)) {
            throw new \Exception('场景不能为空');
        }

        if (!isset(self::$templateCodes[$scene])) {
            throw new \Exception('请配置场景的模板CODE');
        }

        $template_code = self::$templateCodes[$scene];

        try {
            $ali_sms = new AliSms();
            $response = $ali_sms->sendSms($mobile, $template_code, $params);

            if ($response->Code == 'OK') {
                return true;
            }

            throw new \Exception($response->Message);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}