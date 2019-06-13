<?php


namespace App\Handlers;

use App\Models\File as FileModel;
use Illuminate\Support\Facades\Storage;
use Image;

/**
 * 文件上传工具类
 *
 * Class UploadHandler
 * @package App\Handlers
 */
class UploadHandler
{

    /**
     * 保存上传文件
     *
     * @param $type
     * @param $object_id
     * @param $file
     * @param $folder
     * @param $editor
     *
     * @return array|bool
     */
    public function saveUploadFile($type, $object_id, $file, $folder, $editor)
    {
        $type = strtolower($type);

        // 构建存储的文件夹规则，值如：images/avatars/201709/21/
        $folder_name = $type . "s/$folder/" . date("Ym", time()) . '/' . date("d", time());

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 检查文件后缀是否是规则允许后缀
        if (!in_array($extension, config('filesystems.uploader.' . $type . '.allowed_ext'))) {
            return false;
        }

        // 原始文件名
        $title = $file->getClientOriginalName();

        // 获取文件的 Mime
        $mimeType = $file->getClientMimeType();

        // 获取文件大小
        $size = $file->getSize();

        // 获取文件 MD5 值
        $md5 = md5_file($file->getPathname());

        // 检查文件是否已上传过
        if ($fileModel = $this->checkFile($md5, $type, $folder)) {
            return [
                'id' => $fileModel->id,
                'path' => $fileModel->path,
                'model' => $fileModel->toArray(),
                'url' => $type == 'image' ? $this->storage_image_url($fileModel->path) : $this->storage_url($fileModel->path),
            ];
        }

        // 实例化 Image 对象
        if ($type == 'image') {
            $image = Image::make($file->getPathname());
            $width = $image->width();
            $height = $image->height();
        } else {
            // 文件无宽度属性。默认 0
            $width = 0;
            $height = 0;
        }

        // 将图片移动到我们的目标存储路径中 或 云存储中
        if (!($path = $file->store($folder_name))) {
            return false;
        }

        // 将文件信息记录到数据库
        if ($result = $this->saveFile($object_id, $type, $path, $mimeType, $md5, $title, $folder, $size, $width, $height, $editor)) {
            return [
                'id' => $result->id,
                'path' => $path,
                'model' => $result->toArray(),
                'url' => $type == 'image' ? $this->storage_image_url($result->path) : $this->storage_url($result->path),
            ];
        } else {
            Storage::delete($path);
        }

        return false;
    }

    /**
     * 剪裁图片
     *
     * @param $image
     * @param $max_width
     * @return array
     */
    public function reduceSize($image, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($image);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        return ['data' => $image->encode(pathinfo($image->basePath(), PATHINFO_EXTENSION)), 'image' => $image];
    }

    /**
     * 检查文件是否已存在
     *
     * @param $md5
     * @return mixed
     */
    public function checkFile($md5, $type, $folder)
    {
        return FileModel::where('md5', '=', $md5)->where('type', '=', $type)->first();
    }

    /**
     * 保存文件
     *
     * @param $object_id
     * @param $type
     * @param $path
     * @param $mimeType
     * @param $md5
     * @param $title
     * @param $folder
     * @param $size
     * @param $width
     * @param $height
     * @param $editor
     * @return mixed
     */
    public function saveFile($object_id, $type, $path, $mimeType, $md5, $title, $folder, $size, $width, $height, $editor = 0)
    {
        return FileModel::create([
            'object_id' => $object_id,
            'type' => $type,
            'path' => $path,
            'mime_type' => $mimeType,
            'md5' => $md5,
            'title' => $title,
            'folder' => $folder,
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'editor' => (string)$editor
        ]);
    }

    /**
     * 获取图片完整 URL
     */
    function storage_image_url($path)
    {
        return !empty($path) ? $this->storage_url($path) : config('app.url') . '/images/pic-none.png';
    }

    /**
     * 获取完整的 URL
     */
    function storage_url($path)
    {
        return \Illuminate\Support\Facades\Storage::url($path);
    }
}
