<?php
namespace Common\Service\SMS;

class BaoService
{
    private $_api;
    public function send ($mobile, $message, $tpl=1)
    {
        $query          =   $this->config($mobile, $message, $tpl);
        $url            =   $this->_api . '?' . http_build_query( $query );
        $curl_options   =   [
            CURLOPT_URL     =>  $url,
        ];

        return curl( $curl_options );
    }

    public function config ($mobile,$message,$tpl)
    {
        $this->_api         =   C('SMS_BAO.API');
        return [
            'u'             =>  C('SMS_BAO.USERNAME'),
            'p'             =>  md5( C('SMS_BAO.PASSWORD') ),
            'm'             =>  $mobile,
            'c'             =>  $this->template($tpl,$message),
        ];
    }

    public function template ($tpl=1,$content)
    {
        $template       =   [
            1   =>  '【小莺出国】您的密码重置验证码为{content}有效期为20分钟',
        ];

        return  str_replace(  '{content}', $content, $template[$tpl] );
    }
}