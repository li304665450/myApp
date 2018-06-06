<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 2018/6/5
 * Time: 16:12
 */
namespace app\common\lib\wechat;

/**
 * 微信基础接口工具类
 * Class wexinUtil
 */
class WexinUtil{

    private $appid;
    private $appsecret;

    //静态变量保存全局实例
    private static $_instance = null;

    private function __construct()
    {
        $this->appid = config('wechat.appid');
        $this->appsecret = config('wechat.appsecret');
    }

    /**
     * @return mixed
     */
    public function getAppsecret()
    {
        return $this->appsecret;
    }

    //静态方法，单例统一访问入口
    static public function getInstance() {
        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    /**
     * post请求方法
     * @param $url
     * @param null $data
     * @return mixed
     */
    public static function http_request($url, $data = null)
    {
        // 初始化一个新的会话，返回一个cURL句柄，供curl_setopt(), curl_exec()和curl_close() 函数使用。 顾客
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //获取accesstoken
    public function get_accesstoken(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
        $res = self::http_request($url);
        $result = json_decode($res, true);
        return $result["access_token"];
    }

    //使用code换取openid
    public function code_accesstoken($code){
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code';
        $res = self::http_request($url);
        $result = json_decode($res, true);
        return $result['openid'];
    }

    //获取ticket
    public function get_ticket(){
        $access_token = $this->get_accesstoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
        $res =self::http_request($url);
        $result = json_decode($res, true);
        return $result["ticket"];
    }

    //获取用户信息
    public function get_user($openid){
        $access_token = $this->get_accesstoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh\_CN';
        $res =self::http_request($url);
        return json_decode($res, true);
    }

    //获取生成二维码的ticket
    public function get_picticket($scene){
        $access_token = $this->get_accesstoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        $data = '{"action_info":{"scene":{"scene_str":"' . $scene . '"} },"action_name":"QR_LIMIT_STR_SCENE"}';
        $res = self::http_request($url,$data);
        $result = json_decode($res, true);
        return $result["ticket"];
        // return $result;
    }

    //二维码专用请求
    public static function downpic($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        return array_merge( array('body' =>$package),array('heaer' => $httpinfo));
    }

    //获取二维码
    public function get_picture($scene,$filename){
        $ticket = UrlEncode($this->get_picticket($scene));
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
        $img = self::downpic($url);
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $img["body"])) {
                fclose($local_file);
            }
        }
        return $filename;
    }

    //获取点赞猫个人专属二维码
    public function get_personalpic($openid){
        $filename = '../upload/'.$openid.'.jpg';
        if (is_file($filename)) {
            return $filename;
        }else{
            $uniacid = 3;
            $poster = 2;
            $scene_str = md5('ewei_shop_poster:' . $uniacid . ':' . $openid . ':' . $poster);
            return $this->get_picture($scene_str,$filename);
        }
    }
}