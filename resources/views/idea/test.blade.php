<form action="/idea/upload" enctype="multipart/form-data" method="post" name="uploadfile">
    {!! csrf_field() !!}
上传文件：<input type="file" name="src" /><br> 
<input type="submit" value="上传" /></form> 
