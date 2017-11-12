$(function() {
    // console.log(swf);
    // console.log(upload_url);
    // console.log(public_url);
    $("#file_upload").uploadify({
        swf           : swf,
        uploader      : upload_url,
        buttonText    : '图片上传',
        'fileTypeDesc' : 'Image Files',
        'fileTypeExts' : '*.gif; *.jpg; *.png',
        'fileObjName' : 'file',
        onUploadSuccess : function(file, data, response){
            if(response){
                var obj = JSON.parse(data);
                $('#image_src').attr('src',obj.data);
                $('#image_src').attr('height','400');
                $('#image').val(obj.data);
            }
        }
    });
});