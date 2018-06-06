<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"/Users/tuyou/mywork/myApp/public/../app/admin/view/login/login.html";i:1528272993;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit|ie-comp|ie-stand">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <!--[if lt IE 9]>
  <script type="text/javascript" src="__/static__/lib/html5shiv.js"></script>
  <script type="text/javascript" src="__/static__/lib/respond.min.js"></script>
  <![endif]-->
  <link href="/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
  <link href="/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
  <link href="/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
  <link href="/static/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
  <!--[if IE 6]>
  <script type="text/javascript" src="__/static__/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
  <script>DD_belatedPNG.fix('*');</script>
  <![endif]-->
  <title>后台登录</title>
  <meta name="keywords" content="易玩畅游">
  <meta name="description" content="易玩畅游后台系统。">
</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form class="form form-horizontal" method="post" id="form-login">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
          <input id="login_name" name="login_name" type="text" placeholder="账户" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          <input id="password" name="password" type="password" placeholder="密码" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input class="input-text size-L" name="code" type="text" placeholder="验证码" onblur="if(this.value==''){this.value='验证码:'}" onclick="if(this.value=='验证码:'){this.value='';}" value="验证码:" style="width:150px;">
          <img src="/captcha" alt="点击刷新验证码">
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <label for="online">
            <input type="checkbox" name="online" id="online" value="">
            使我保持登录状态</label>
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input name="" type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>
      </div>
    </form>
  </div>
</div>
<div class="footer">易玩畅游</div>
<script type="text/javascript" src="/static/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/static/lib/layer/2.4/layer.js"></script>
<script type="text/javascript">

  //点击刷新验证码
  $('img').click(function () {
      $(this).attr('src','/captcha?id='+Math.random());
  })

  //提交登陆信息
  $('form').bind('submit',function () {

      $.ajax({
          type : "POST",
          url : "<?php echo url('loginAjax'); ?>",
          async : true,
          data : $("#form-login").serialize(),
          dataType : "json"
      }).done(function(relult){

          //显示回调信息
          layer.msg(relult,{icon:1,time:1000});
          var t = setTimeout('window.location.reload()',1000);

          //登陆成功跳转页面
          if(relult == "登陆成功"){
              var wt = setTimeout('window.location.href="<?php echo url('index/index'); ?>"',1000);
          }

      });

      //阻止表单直接提交
      return false;

  });

</script>
</body>
</html>