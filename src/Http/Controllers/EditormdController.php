<?php

namespace Sungmee\Editormd;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class EditormdController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Editor.md 图片上传控制器
     *
     * @param  Request $requst
     * @return Response
     */
    public function postUploadImage(Request $request)
    {
        $config     = config('editormd');
        $disk       = $config['uploadDisk'] ? $config['uploadDisk'] : 'local';
        $formats    = $config['imageFormats'] ? $config['imageFormats'] : 'jpg,jpeg,gif,png,bmp,webp';
        $maxSize    = $config['uploadMaxSize'] ? $config['uploadMaxSize'] : 5120;
        $uploadPath = 'public/' . $config['uploadPath'] . date('Ym');
        $fullUrl    = $config['fullUrl'];

        $rules      = [
            'editormd-image-file' => "required|file|mimes:{$formats}|max:{$maxSize}",
        ];
        $messages   = [
            'editormd-image-file.file'  => "文件校验失败",
            'editormd-image-file.mimes' => "文件类型不允许，请上传常规的图片（{$formats}）文件",
            'editormd-image-file.max'   => "文件过大，文件大小不得超出 {$maxSize} 字节。"
        ];
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $fail = [
                'success'   => 0,
                'message'   => $errors->first('editormd-image-file'),
                'url'       => '',
            ];
            return response()->json($fail);
        }

        $path       = $request->file('editormd-image-file')->store($uploadPath, $disk);
        $url        = Storage::disk($disk)->url($path);
        $url        = $fullUrl ? asset($url) : $url;
        $success    = ['success' => 1, 'url' => $url];

        return response()->json($success);
    }


    /**
     * 获取七牛上传令牌
     *
     * @param
     * @return Response JSON
     */
    public function getQiniuToken()
    {
        $accessKey = config('editormd.qiniuAccessKey');
        $secretKey = config('editormd.qiniuSecretKey');
        $bucket = config('editormd.qiniuBucket');

        $result = [
            'success' => 0,
            'message' => '失败原因：未能获取七牛令牌',
            'data' => NULL
        ];

        $auth = new \Qiniu\Auth($accessKey, $secretKey);
        $upToken = $auth->uploadToken($bucket);

        if( $upToken ) {
            $result = [
                'success' => 1,
                'message' => '成功获取七牛令牌',
                'data' => $upToken
            ];
        }

        return response()->json($result);
    }

}
