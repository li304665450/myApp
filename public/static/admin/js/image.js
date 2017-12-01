$(function() {
    $("#file_upload").uploadify({
        swf           : swf,
        uploader      : upload_url,
        buttonText    : '图片上传',
        'fileTypeDesc' : 'Image Files',
        'fileTypeExts' : '*.gif; *.jpg; *.png',
        'fileObjName' : 'file',
        onUploadSuccess : function(file, result, response){
            if(response){
                $('#image_src').attr('src',result.data);
                $('#image_src').attr('height','400');
                $('#image').val(result.data);
            }
        }
    });
});