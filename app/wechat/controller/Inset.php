<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2018/1/5
 * Time: 17:07
 */

namespace app\wechat;


class Inset{

    /**
     * 入口方法
     */
    public function index()
    {
        if (isset($_GET['echostr'])) {
            $this->valid();
        }else{
            $this->responseMsg();
        }
    }

    /**
     * 公众号接入验证回复
     */
    public function valid(){
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    /**
     * 公众号接入验证方法
     * @return bool
     */
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array(C('WX_TOKEN'), $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 响应消息处理
     */
    public function responseMsg()
    {
        //接收微新传过来的xml消息数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //如果接收到了就处理并回复
        if (!empty($postStr)){
            //将接收到的XML字符串写入日志， 用R标记表示接收消息
            $this->logger("R \n".$postStr);
            //将接收的消息处理返回一个对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            //从消息对象中获取消息的类型 text  image location voice vodeo link
            $RX_TYPE = trim($postObj->MsgType);

            //消息类型分离, 通过RX_TYPE类型作为判断， 每个方法都需要将对象$postObj传入
            switch ($RX_TYPE)
            {
                case "text":
                    $result = $this->receiveText($postObj);     //接收文本消息
                    break;
                case "event":
                    $result = $this->receiveEvent($postObj);     //接收事件推送
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);   //接收图片消息
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);  //接收位置消息
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);   //接收语音消息
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);  //接收视频消息
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);  //接收链接消息
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;   //未知的消息类型
                    break;
            }
            //将响应的消息再次写入日志， 使用T标记响应的消息！
            $this->logger("T \n".$result);
            //输出消息给微新
            echo $result;
        }else {
            //如果没有消息则输出空，并退出
            echo "";
            exit;
        }
    }

    /**
     * 接收文本消息
     * @param $object
     * @return string|void
     */
    private function receiveText($object)
    {
        //从接收到的消息中获取用户输入的文本内容， 作为一个查询的关键字， 使用trim()函数去两边的空格
        $keyword = trim($object->Content);

        //自动回复模式
        if (strstr($keyword, "文本")){
            $content = "这是个文本消息";

        }else if (strstr($keyword, "单图文")){

            $content = array();
            $content[] = array("Title"=>"小规模低性能低流量网站设计原则",  "Description"=>"单图文内容", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");

        }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
            $content = array();
            $content[] = array("Title"=>"多图文1标题", "Description"=>"动手构建站点的时候，不要到处去问别人该用什么，什么熟悉用什么，如果用自己不擅长的技术手段来写网站，等你写完，黄花菜可能都凉了。", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");
            $content[] = array("Title"=>"多图文2标题", "Description"=>"动手构建站点的时候，不要到处去问别人该用什么，什么熟悉用什么，如果用自己不擅长的技术手段来写网站，等你写完，黄花菜可能都凉了。", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");
            $content[] = array("Title"=>"多图文3标题", "Description"=>"动手构建站点的时候，不要到处去问别人该用什么，什么熟悉用什么，如果用自己不擅长的技术手段来写网站，等你写完，黄花菜可能都凉了。", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");
        }else if (strstr($keyword, "音乐")){
            $content = array();
            $content = array("Title"=>"小歌曲你听听", "Description"=>"歌手：不是高洛峰", "MusicUrl"=>"http://wx.buqiu.com/app/hlw.mp3", "HQMusicUrl"=>"http://wx.buqiu.com/app/hlw.mp3");
        }else{
            $content = date("Y-m-d H:i:s",time())."\n你好，由于小二比较忙，有什么问题请您留言，小二看到会及时回复您！";
        }

        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }

        return $result;
    }

    private function receiveEvent($object){

        //从消息对象中获取事件的类型 text  image location voice vodeo link
        $event = trim($object->Event);

        switch ($event){
            case 'subscribe':
                $result = $this->eventSubscribe($object);  //关注事件
                break;
            case 'unsubscribe':
                $result = $this->eventUnsubscribe($object);  //取注事件
                break;
        }

        return $result;
    }

    /**
     * 关注事件处理方法
     * @param $object
     * @return string|void
     */
    private function eventSubscribe($object){

        //从消息对象中获取关注用户openid
        $FromOpenid = trim($object->FromUserName);

        $user = M('users')->where("openid = '%s'",$FromOpenid)->find();

        if (!$user){
            //微信接口获取用户信息
            $user = WeixinUtil::get_user($FromOpenid);

            $info['openid'] = $user['openid'];
            $info['nickname'] = $user['nickname'];//昵称
            $info['sex'] = $user['sex'];//性别
            $info['city'] = $user['city'];//城市
            $info['province'] = $user['province'];//省份
            $info['head_pic'] = $user['headimgurl'];//头像
            $info['reg_time'] = $user['subscribe_time'];//注册时间

            //存入用户表
            $user_id = M('users')->add($info);

            //发放首关红包
            $arr['openid']='otXi-1dl6vk1NRSg86qErPlvOImc';
            $arr['hbname']="关注奖励";
            $arr['body']="欢迎关注寻城觅意";
            $arr['fee']=1;
            //$comm = new RedPaperUtil();
            //$re = $comm->sendhongbaoto($arr);
        }else{
            //已关注过用户重新开启关注
            $info['subscribe'] = 1;
            $user = M('users')->where("openid = '%s'",$FromOpenid)->save($info);
        }

        $content = array();
        //关注回复消息，改这个数组
        $content[] = array("Title"=>"欢迎关注寻城觅意\n再见2017，你好2018！", "Description"=>$user_id, "PicUrl"=>"https://mmbiz.qpic.cn/mmbiz_jpg/P9KKPaHlBz2hSCicFq0Pconibf1ffNOOTqvdIbQc5sQiaMunlibZxswPlj5ibTWrM8GorA2bticGbTbic88Sce1nGFk4w/640?wx_fmt=jpeg&tp=webp&wxfrom=5&wx_lazy=1", "Url" =>"http://mp.weixin.qq.com/s?__biz=MzU4NjAyOTc0OQ==&mid=2247483716&idx=1&sn=61f42fac906d56614785ef3699f77aa2&chksm=fd80ce9ecaf74788824b4d736233067a645d9a1a91a754e1f1fb96178d50782b54c201375df2#rd");

        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }

        return $result;
    }

    /**
     * 取关事件处理方法
     * @param $object
     */
    private function eventUnsubscribe($object){

        //从消息对象中获取关注用户openid
        $FromOpenid = trim($object->FromUserName);

        //关注状态设为取关
        $info['subscribe'] = 0;
        $user = M('users')->where("openid = '%s'",$FromOpenid)->save($info);

        return;
    }

    /**
     * 接收图片消息
     * @param $object
     * @return string
     */
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    /**
     * 接收位置消息
     * @param $object
     * @return string
     */
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /**
     * 接收语音消息
     * @param $object
     * @return string
     */
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }

    /**
     * 接收视频消息
     * @param $object
     * @return string
     */
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "Title"=>"this is a test", "Description"=>"pai pai");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    /**
     * 接收链接消息
     * @param $object
     * @return string
     */
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /**
     * 回复文本消息
     * @param $object
     * @param $content
     * @return string
     */
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    /**
     * 回复图片消息
     * @param $object
     * @param $imageArray
     * @return string
     */
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    /**
     * 回复语音消息
     * @param $object
     * @param $voiceArray
     * @return string
     */
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    /**
     * 回复视频消息
     * @param $object
     * @param $videoArray
     * @return string
     */
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    /**
     * 回复图文消息
     * @param $object
     * @param $newsArray
     * @return string|void
     */
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    /**
     * 回复音乐消息
     * @param $object
     * @param $musicArray
     * @return string
     */
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    /**
     * post请求方法
     * @param $url
     * @param $data
     * @return mixed|string
     */
    function https_post($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }

    /**
     *
     * 日志记录
     * @param $log_content
     */
    private function logger($log_content)
    {

        $max_size = 100000;   //声明日志的最大尺寸

        $log_filename = "log.xml";  //日志名称

        //如果文件存在并且大于了规定的最大尺寸就删除了
        if(file_exists($log_filename) && (abs(filesize($log_filename)) > $max_size)){
            unlink($log_filename);
        }

        //写入日志，内容前加上时间， 后面加上换行， 以追加的方式写入
        file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\n", FILE_APPEND);

    }

}