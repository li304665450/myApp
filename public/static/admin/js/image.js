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
                $('#upload_images').attr('src',obj.data);
                $('#file_upload_image').val(obj.data);
            }
        }
    });
});