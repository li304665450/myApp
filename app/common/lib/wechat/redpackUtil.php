<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2018/1/5
 * Time: 16:26
 */

namespace app\common\lib\wechat;

header("Content-type: text/html; charset=utf-8");

/**
 * 发送红包工具类
 * Class redPaperUtil
 * @package app\common\lib\wechat
 */
class RedpackUtil{

    private $mch_id;
    private $appid;
    private $key;


    //普通红包接口地址
    const URL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
    //裂变红包接口地址
    const GROUPURL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
    //静态变量保存全局实例
    private static $_instance = null;

    private function __construct(){
        $this->appid = config('wechat.appid');
        $this->mch_id = config('wechat.mch_id');
        $this->key = config('wechat.key');
    }

    //静态方法，单例统一访问入口
    static public function getInstance() {
        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    /**
     * @return mixed
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * 发送普通红包
     * @param $arr hbname 红包名称 money 红包金额 /元 body 内容 openid 微信用户id
     * @return mixed
     */
    public function sendRedPack($arr){
        return $this->send($arr,self::URL);
    }


    /**
     * 发送裂变红包
     * @param $arr hbname 红包名称 money 红包金额 /元 body 内容 openid 微信用户id num 红包数量
     * @return mixed
     */
    public function sendGroupRedPack($arr){
        return $this->send($arr,self::GROUPURL,true);
    }

    /**
     * @param $arr hbname 红包名称 money 红包金额 /元 body 内容 openid 微信用户id
     * @param $url 接口地址
     * @param bool $group 裂变红包开关
     * @return mixed
     */
    private function send($arr,$url,$group = false){
        $data['mch_id'] = $this->mch_id;
        $data['mch_billno'] = $this->mch_id.date("Ymd",time()).date("His",time()).rand(1111,9999);
        $data['nonce_str'] = self::createNoncestr();
        $data['re_openid'] = $arr['openid'];
        $data['wxappid'] = $this->appid;
        $data['send_name'] = $arr['hbname'];
        $data['total_amount'] = $arr['money']*100;
        $data['client_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['act_name'] = '关注有礼';
        $data['remark'] = '关注即可领红包';
        $data['wishing'] = $arr['body'];
        if(!$data['re_openid']) {
            $rearr['return_msg']='缺少用户openid';
            return $rearr;
        }
        /**
         * 裂变红包需设置类型，分发数量自定义
         * 普通红包数量只能为一
         */
        if ($group){
            $data['amt_type'] = 'ALL_RAND';//裂变红包类型，全部随机
            $data['total_num'] = $arr['num'];
        }else{
            $data['total_num'] = 1;
        }
        $data['sign'] = self::getSign($data);
        $xml = self::arrayToXml($data);
        $re = self::wxHttpsRequestPem($xml,$url);
        $rearr = self::xmlToArray($re);
        return $rearr;
    }

    /**
     * 作用：产生随机字符串，不长于32位
     */
    private function createNoncestr( $length = 32 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ ) {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     * 作用：格式化参数，签名过程需要使用
     */
    private function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = "";
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /**
     * 作用：生成签名
     */
    private function getSign($Obj)
    {
        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$this->key; // 商户后台设置的key
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     * 作用：array转xml
     */
    private function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";
            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 作用：将xml转为array
     */
    private function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * 携带证书pem请求方式
     * @param $vars
     * @param $url
     * @param int $second
     * @param array $aHeader
     * @return bool|mixed
     */
    private function wxHttpsRequestPem( $vars,$url, $second=30,$aHeader=array()){
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        //以下两种方式需选择一种
        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,'C:/wwwroot/shop_sj8yy3/web/plugins/payment/weixin/cert_lilei/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,'C:/wwwroot/shop_sj8yy3/web/plugins/payment/weixin/cert_lilei/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO,'PEM');
        curl_setopt($ch,CURLOPT_CAINFO,'C:/wwwroot/shop_sj8yy3/web/plugins/payment/weixin/cert_lilei/rootca.pem');
        //第二种方式，两个文件合成一个.pem文件
        //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }
}