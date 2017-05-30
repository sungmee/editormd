# Editor.md with Qiniu upload for Laravel. 

**Laravel** 的 Markdown 编辑器 **Editor.md**，配置图片本地上传（可自定义储存系统）以及**七牛云**上传。

注意，如果使用本地上传出现**500**错误，一般是运行环境没有安装 PHP 的 FILEINFO 扩展所致，请自行 Google 解决。

## 安装与配置

在 **Laravel** 项目根目录的 `composer.json` 的 `require` 中新增 `"sungmee/editormd": "dev-master",` 依赖，然后执行 `composer update` 进行安装。

安装好依赖后，在 `config/app.php` 中添加：

```php
'providers' => [
    Sungmee\Editormd\EditormdServiceProvider::class::class,
],
```

然后在 **SSH** 中 cd 到 **Laravel** 项目根目录，执行下面 `Artisan` 命令，发布本扩展包的配置和路由：

    php artisan vendor:publish

### 使用本地文件储存系统

Editor.md 编辑器图片默认使用 **Local** 储存系统并会上传到 **Laravel** 项目的 `storage/app/public` 目录下。

为了能公开访问，你需要创建 `public/storage` 文件夹，然后作为符号链接到 `storage/app/public` 文件夹。可以使用以下 `Artisan` 命令创建符号链接：

    php artisan storage:link

### 使用七牛云文件储存系统

根据实际情况，修改 **Laravel** 项目根目录下的 `config/editormd.php` 配置文件下面列出的几个七牛相关项：

```php
'qiniuAccessKey' => '',  // 七牛 AccessKey
'qiniuSecretKey' => '',  // 七牛 SecretKey
'qiniuBucket'    => '',  // 七牛 Bucket 名字
'qiniuPublishUrl'=> '',  // 七牛 CDN 加速域名
'qiniuUploadUrl' => '',  // 七牛储存上传 URL
```

至此，所有配置完毕。

## 使用说明

在需要使用 **Editor.md** Markdown 编辑器的 `blade` 模板里面使用下面三个方法：`editor_css()` 、`editor_js()` 和 `editor_config()` 即可调用 **Editor.md** 编辑器。

- `editor_css()`：编辑器 CSS 样式表文件（必须）。
- `editor_js()`：编辑器 Javascript 文件（必须）。
- `editor_config()`：编辑器配置的 JS 代码，如果你不使用本扩展包的方法，可以根据 Editor.md 说明自行配置。

**注意：本扩展包编辑器 TEXTAREA 的父级容器默认使用 `editormd` ID。**

其它详细配置，请看 `config/editormd.php` 文件的注释。