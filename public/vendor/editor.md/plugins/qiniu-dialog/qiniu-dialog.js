/*
 * Image (upload) dialog By Qiniu plugin for Editor.md
 *
 * @file        qiniu-dialog.js
 * @author      M.Chan <mo@lxooo.com>
 * @version     1.4.0
 * @updateTime  2017-05-30
 * {@link       https://github.com/sungmee/editormd}
 * @license     MIT
 */
(function () {

    var factory = function (exports) {

        var pluginName = "qiniu-dialog";

        exports.fn.qiniuDialog = function () {

            var _this = this;
            var cm = this.cm;
            var lang = this.lang;
            var editor = this.editor;
            var settings = this.settings;
            var cursor = cm.getCursor();
            var selection = cm.getSelection();
            var imageLang = lang.dialog.image;
            var classPrefix = this.classPrefix;
            var iframeName = classPrefix + "image-iframe";
            var dialogName = classPrefix + pluginName, dialog;
            var ajaxToken = "";

            cm.focus();

            var loading = function (show) {
                var _loading = dialog.find("." + classPrefix + "dialog-mask");
                _loading[(show) ? "show" : "hide"]();
            };

            if (editor.find("." + dialogName).length < 1) {
                var guid = (new Date).getTime();
                var action = settings.imageUploadURL + (settings.imageUploadURL.indexOf("?") >= 0 ? "&" : "?") + "guid=" + guid;

                if (settings.crossDomainUpload) {
                    action += "&callback=" + settings.uploadCallbackURL + "&dialog_id=editormd-image-dialog-" + guid;
                }

                var dialogContent = ((settings.imageUpload) ? "<form id=\"qiniuUploadForm\" method=\"post\" enctype=\"multipart/form-data\" class=\"" + classPrefix + "form\" onsubmit=\"return false;\">" : "<div class=\"" + classPrefix + "form\">") +
                    "<label>" + imageLang.url + "</label>" +
                    "<input type=\"text\" data-url />" + (function () {
                        return (settings.imageUpload) ? "<div class=\"" + classPrefix + "file-input\">" +
                            "<input type=\"file\" name=\"file\" accept=\"image/*\" />" +
                            "<input type=\"submit\" value=\"七牛上传\" click=\"alert('dd')\" />" +
                            "</div>" : "";
                    })() +
                    "<br/>" +
                    "<input name=\"token\" type=\"hidden\" value=\"" + ajaxToken + "\">" +        //七牛的上传token
                    "<label>" + imageLang.alt + "</label>" +
                    "<input type=\"text\" value=\"" + selection + "\" data-alt />" +
                    "<br/>" +
                    "<label>" + imageLang.link + "</label>" +
                    "<input type=\"text\" value=\"https://\" data-link />" +
                    "<br/>" +
                    "<label>图片宽度</label>" +
                    "<input type=\"text\" value=\"\" data-width />" +
                    "<br/>" +
                    "<label>图片高度</label>" +
                    "<input type=\"text\" value=\"\" data-height />" +
                    "<br/>" +
                    ((settings.imageUpload) ? "</form>" : "</div>");

                dialog = this.createDialog({
                    title: imageLang.title,
                    width: (settings.imageUpload) ? 465 : 380,
                    height: 350,
                    name: dialogName,
                    content: dialogContent,
                    mask: settings.dialogShowMask,
                    drag: settings.dialogDraggable,
                    lockScreen: settings.dialogLockScreen,
                    maskStyle: {
                        opacity: settings.dialogMaskOpacity,
                        backgroundColor: settings.dialogMaskBgColor
                    },
                    buttons: {
                        enter: [lang.buttons.enter, function () {
                            var url = this.find("[data-url]").val();
                            var alt = this.find("[data-alt]").val();
                            var link = this.find("[data-link]").val();
                            var width = this.find("[data-width]").val();
                            var height = this.find("[data-height]").val();

                            if (url === "") {
                                alert(imageLang.imageURLEmpty);
                                return false;
                            }

                            var size = '';
                            if(width || height){
                                size += '?imageView2/1';
                                size += width ? '/w/'+width : '';
                                size += height ? '/h/'+height : '';
                                size += '/q/75|imageslim';
                            }

                            var altAttr = (alt !== "") ? " \"" + alt + "\"" : "";

                            if (link === "" || link === "https://") {
                                cm.replaceSelection("![" + alt + "](" + url + size + altAttr + ")");
                            }
                            else {
                                cm.replaceSelection("[![" + alt + "](" + url + size + altAttr + ")](" + link + altAttr + ")");
                            }

                            if (alt === "") {
                                cm.setCursor(cursor.line, cursor.ch + 2);
                            }

                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }],

                        cancel: [lang.buttons.cancel, function () {
                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }]
                    }
                });

                dialog.attr("id", classPrefix + "image-dialog-" + guid);

                if (!settings.imageUpload) {
                    return;
                }

                var fileInput = dialog.find("[name=\"file\"]");

                var submitHandler = function () {
                    $.ajax({
                        url: "/editormd/upload/token",
                        type: "get",
                        dataType: "json",
                        timeout: 2000,
                        beforeSend: function () { loading(true); },
                        success: function (result) {
                            if (result.success == 1) {
                                ajaxToken = result.data;

                                if (ajaxToken === "") {
                                    loading(false);
                                    alert("没有获取到有效的上传令牌，无法上传！");
                                    return;
                                }
                                dialog.find("[name=\"token\"]").val(ajaxToken);
                                var formData = new FormData($("#qiniuUploadForm")[0]);
                                dialog.find("[name=\"token\"]").val();    //隐藏令牌
                                $.ajax({
                                    url: settings.qiniuUploadUrl,
                                    type: 'POST',
                                    data: formData,
                                    dataType: "json",
                                    beforeSend: function () { loading(true); },
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    timeout: 30000,
                                    success: function (result) {
                                        dialog.find("[data-url]").val(settings.qiniuPublishUrl + result.key);
                                    },
                                    error: function () { alert("上传超时"); },
                                    complete: function () { loading(false); }
                                });
                            }
                            else
                                alert(result.message);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log('XMLHttpRequest: '+XMLHttpRequest);
                            console.log('textStatus: '+textStatus);
                            console.log('errorThrown: '+errorThrown);
                        }
                    });
                };

                dialog.find("[type=\"submit\"]").bind("click", submitHandler);

                fileInput.bind("change", function () {
                    var fileName = fileInput.val();
                    var isImage = new RegExp("(\\.(" + settings.imageFormats.join("|") + "))$");

                    if (fileName === "") {
                        alert(imageLang.uploadFileEmpty);

                        return false;
                    }

                    if (!isImage.test(fileName)) {
                        alert(imageLang.formatNotAllowed + settings.imageFormats.join(", "));
                        return false;
                    }

                    dialog.find("[type=\"submit\"]").trigger("click");
                });
            }

            dialog = editor.find("." + dialogName);
            dialog.find("[type=\"text\"]").val("");
            dialog.find("[type=\"file\"]").val("");
            dialog.find("[data-link]").val("https://");

            this.dialogShowMask(dialog);
            this.dialogLockScreen();
            dialog.show();

        };

    };

    // CommonJS/Node.js
    if (typeof require === "function" && typeof exports === "object" && typeof module === "object") {
        module.exports = factory;
    }
    else if (typeof define === "function")  // AMD/CMD/Sea.js
    {
        if (define.amd) { // for Require.js

            define(["editormd"], function (editormd) {
                factory(editormd);
            });

        } else { // for Sea.js
            define(function (require) {
                var editormd = require("./../../editormd");
                factory(editormd);
            });
        }
    }
    else {
        factory(window.editormd);
    }

})();