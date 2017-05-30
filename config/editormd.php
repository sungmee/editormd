<?php
return [
    'width' => '100%',      // 编辑器宽度
    'height' => '540',      // 编辑器高度
    'emoji' => 'false',     // emoji表情
    'toc' => 'true',        // 目录
    'tocm' => 'false',      // 目录下拉菜单
    'taskList' => 'false',  // 任务列表
    'flowChart' => 'false', // 流程图
    'tex' => 'false',       // 开启科学公式TeX语言支持，默认关闭
    'imageUpload' => 'true',// 图片上传支持
    'imageFormats' => "jpg,jpeg,gif,png,bmp,webp",  // 允许上传的文件格式，以英文逗号分隔每种格式。
    'imageUploadURL' => '/editormd/upload/image',   // 图片上传路由，除非你重写上传控制器，否则不要修改此项
    'saveHTMLToTextarea' => 'true',                 // 保存 HTML 到 Textarea
    'codeFold' => 'true',           // 代码折叠
    'sequenceDiagram' => 'false',   // 开启时序/序列图支持，默认关闭
    'toolbarIcons' => '"undo", "redo", "|", "bold", "del", "italic", "quote", "|", "list-ul", "list-ol", "hr", "|", "qiniu", "image", "table", "html-entities", "datetime", "||", "watch", "preview", "|", "fullscreen"', // 定制编辑器工具栏

    'uploadDisk' => 'local', // 存储驱动
    'uploadMaxSize' => 5120, // 上传文件大小字节
    'uploadPath' => 'editormd/', // 上传文件路径，斜杆收尾。例如以 local 驱动，即相对于 storage/app/public/ 目录
    'fullUrl' => true, // 返回前端的图片 URL。false：返回相对路径；true：返回完整链接

    'qiniuAccessKey' => '', // 七牛 AccessKey
    'qiniuSecretKey' => '', // 七牛 SecretKey
    'qiniuBucket' => '',                                        // 七牛 Bucket 名字
    'qiniuPublishUrl' => '',      // 七牛 CDN 加速域名
    // 七牛储存上传 URL
    // http 协议 http://up.qiniu.com 或 http://upload.qiniu.com
    // https 协议 - 请根据 Bucket 的储存区域选择，否则出错
    // 华东 (https://up.qbox.me)
    // 华北（https://up-z1.qbox.me)
    // 华南（https://up-z2.qbox.me)
    // 北美（https://up-na0.qbox.me）
    'qiniuUploadUrl' => 'https://up-z2.qbox.me',
];
