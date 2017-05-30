<?php
Route::post('editormd/upload/image', 'Sungmee\Editormd\EditormdController@postUploadImage');
Route::get('editormd/upload/token', 'Sungmee\Editormd\EditormdController@getQiniuToken');