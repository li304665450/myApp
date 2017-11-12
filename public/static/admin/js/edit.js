
//数据回显方法
function info_display(id,url) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: getUrlParam('id')
        }
    }).done(function (relult) {
        console.log(relult);
        var obj = JSON.parse(relult.data);
        console.log(obj);
        $.each(obj,function(index,value){
            $('#'+index).val(value);
            $('#'+index+'_select').find("option[value="+value+"]").attr("selected",true);
            $('#image_src').attr('height','400');
            $('#'+index+'_src').attr("src",value);
            if(value == 'on'){
                $('#'+index).parent().addClass('checked');
            }
        })
    })
}

//checkbox样式加载
$('.skin-minimal input').iCheck({
    checkboxClass: 'icheckbox-blue',
    radioClass: 'iradio-blue',
    increaseArea: '20%'
});

//表单提交按钮
$('#sub_btn').on('click',function () {
    $('#form-news-add').submit();
});

//表单提交草稿按钮
$('#draft_btn').on('click',function () {
    $('#status').val(0);
    $('#form-news-add').submit();
});