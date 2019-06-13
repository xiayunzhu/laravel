<?php

namespace App\Handlers;

use App\Http\Requests\Api\UploadFile\StoreRequest;
use App\Models\UploadFile;


class UploadFileHandler extends BaseHandler
{
    /**
     * @var array
     */
    protected $scenes = [
        'create' => ['folder', 'object_id', 'group_id', 'path', 'file_url', 'file_name', 'file_size', 'file_type', 'extension', 'shop_id'],
        'modify' => ['id', 'folder', 'object_id', 'group_id', 'path', 'file_url', 'file_name', 'file_size', 'file_type', 'extension', 'shop_id']
    ];

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['folder', 'object_id', 'group_id', 'path', 'file_url', 'file_name', 'file_size', 'file_type', 'extension', 'shop_id'];

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $data = $this->currentScene($data, 'create');
        return UploadFile::create($data);
    }

    /**
     * 图片库相关上传
     *
     * @param StoreRequest $request
     * @param \App\Handlers\UploadHandler $uploader
     * @param UploadFileHandler $uploadFileHandler
     * @param $file_folder
     * @return array
     */
    public function uploadFilesUploader(StoreRequest $request, UploadHandler $uploader, UploadFileHandler $uploadFileHandler, $file_folder)
    {

        // 判断是否有上传文件，并赋值给 $file
        if (!($file = $request->upload_file)) {
            return $this->responseAjax(1, false, '上传文件不允许未空');
        }

        // 获取上传的类型
        $file_type = 'image';


        // 检查文件大小是否合法
        if ($file->getSize() <= 0) {
            return $this->responseAjax(2, false, '文件大小不能为: 0 ');
        }

        if ($file->getSize() > config('filesystems.uploader.' . $file_type . '.size_limit')) {
            $message = '大小不能超过 ' . $this->byte_to_size(config('filesystems.uploader.' . $file_type . '.size_limit')) . '';
            return $this->responseAjax(3, false, $message);
        }

        \DB::beginTransaction();
        // 保存附件到文件系统
        $result = $uploader->saveUploadFile($file_type, $this->create_object_id(), $file, $file_folder, intval($request->editor ?? 0));

        if ($result) {
            try {

                //保存到图片文件数据库
                if (isset($result['model']) && is_array($result['model'])) {
                    $model = $result['model'];
                    $model['file_url'] = $model['path'];
                    $model['file_name'] = $model['title'];
                    $model['file_size'] = $model['size'];
                    $model['file_type'] = $model['mime_type'];
                    $model['extension'] = strtolower($file->getClientOriginalExtension()) ?: 'png';
                    unset($model['id']);
                    $uploadFileHandler->create($model);
                    \DB::commit();
                }
            } catch (\Exception $exception) {
                \DB::rollBack();
                return $this->responseAjax(4, false, '上传失败【' . $exception->getMessage() . '】');
            }

            // 上传成功
            return $this->responseAjax(0, true, '上传成功', $result['path'], $result['url'], $result['id']);
        } else {
            \DB::rollBack();
            // 上传失败
            return $this->responseAjax(4, false, '上传失败');
        }
    }

    /**
     * 生成响应结构
     *
     * @param int $code
     * @param bool $success
     * @param string $message
     * @param string $path
     * @param string $url
     * @param int $id
     * @param int $multiple_id
     *
     * @return array
     */
    protected function responseAjax($code = 1, $success = false, $message = '上传失败', $path = '', $url = '', $id = 0, $multiple_id = 0)
    {

        return [
            // 默认
            'code' => $code,
            'success' => $success,          // 状态
            'id' => $id,              // id
            'url' => $url,              // 完整可访问URL
            'message' => $message,          // 提示消息
            'path' => $path,             // 文件相对地址
            'result' => $success === true ? 'ok' : 'failed',
        ];


    }

    /**
     * 生成 object_id
     */
    function create_object_id()
    {
        return base_convert(uniqid(), 16, 10);
    }

    /**
     *
     * @param $byte
     * @return string
     */
    function byte_to_size($byte)
    {
        if ($byte > pow(2, 40)) {
            $size = round($byte / pow(2, 40), 2) . ' TB';
        } elseif ($byte > pow(2, 30)) {
            $size = round($byte / pow(2, 30), 2) . ' GB';
        } elseif ($byte > pow(2, 20)) {
            $size = round($byte / pow(2, 20), 2) . ' MB';
        } elseif ($byte > pow(2, 10)) {
            $size = round($byte / pow(2, 10), 2) . ' KB';
        } else {
            $size = round($byte, 2) . ' B';
        }

        return $size;
    }
}