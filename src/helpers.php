<?php
/**
 * editor.md css 相关依赖
 *
 * @return string
 */
function editor_css()
{

    return '<!-- Editor.md css-->
<link rel="stylesheet" href="/vendor/editor.md/css/editormd.min.css" />
<style>
.editormd-fullscreen {
    z-index: 9999999;
}
</style>';

}

/**
 * editor.md js 相关依赖
 *
 * @return string
 */
function editor_js()
{
    return '<!-- Editor.md js -->
<script src="/vendor/editor.md/js/editormd.min.js"></script>
<script src="/vendor/editor.md/plugins/qiniu-dialog/qiniu-dialog.min.js"></script>';

}

/**
 * editor.md 初始化配置js代码
 *
 * @param  string $editor_id 编辑器 `textarea` 所在父div层id值，默认取 `editormd` 字符串
 * @return string
 */
function editor_config($editor_id = 'editormd')
{
    $imageFormats = explode(',', config('editormd.imageFormats'));
    $imageFormats = '["' . join('", "', $imageFormats) . '"]';

    return '<!-- Editor.md config -->
<script>
var _'.$editor_id.';
$(function() {
    editormd.emoji     = {
        path  : "//staticfile.qnssl.com/emoji-cheat-sheet/1.0.0/",
        ext   : ".png"
    };
    _'.$editor_id.' = editormd({
            id : "'.$editor_id.'",
            width : "'.config('editormd.width').'",
            height : '.config('editormd.height').',
            saveHTMLToTextarea : '.config('editormd.saveHTMLToTextarea').',
            emoji : '.config('editormd.emoji').',
            taskList : '.config('editormd.taskList').',
            tex : '.config('editormd.tex').',
            toc : '.config('editormd.toc').',
            tocm : '.config('editormd.tocm').',
            codeFold : '.config('editormd.codeFold').',
            flowChart: '.config('editormd.flowChart').',
            sequenceDiagram: '.config('editormd.sequenceDiagram').',
            path : "/vendor/editor.md/lib/",
            imageUpload : '.config('editormd.imageUpload').',
            imageFormats : '.$imageFormats.',
            imageUploadURL : "'.config('editormd.imageUploadURL').'?_token='.csrf_token().'&from=laravel-editor-md",
            toolbarIcons : function() {
                return  ['.config('editormd.toolbarIcons').'];
            },
            toolbarIconsClass : { qiniu : "fa-gitlab" },
            toolbarHandlers : {
                qiniu : function(cm, icon, cursor, selection) {
                    this.qiniuDialog();
                }
            },
            lang : {
                toolbar : {
                    qiniu : "添加图片到七牛"
                }
            },
            qiniuPublishUrl : "'.config('editormd.qiniuPublishUrl').'",
            qiniuUploadUrl: "'.config('editormd.qiniuUploadUrl').'"
    });
});
</script>';

}