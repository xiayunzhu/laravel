<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\UploadFileHandler;
use App\Handlers\UploadHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    //

    /**
     * 文件上传
     *
     * @param Request $request
     * @param UploadHandler $uploader
     * @return array
     */
    public function uploader(Request $request, UploadHandler $uploader)
    {
        // 检测是否是允许的类型
        if (!in_array($request->folder, config('filesystems.uploader.folder', []))) {
            return $this->responseAjax(2, false, '非法上传，上传类型错误');
        }

        // 判断是否有上传文件，并赋值给 $file
        if (!($file = $request->upload_file)) {
            return $this->responseAjax(3, false, '上传文件不允许未空');
        }

        // 获取上传的类型
        $file_type = $request->file_type ?? 'file';

        // 检查文件大小是否合法
        if ($file->getSize() <= 0) {
            return $this->responseAjax(7, false, '文件大小不能为: 0 ');
        }

        if ($file->getSize() > config('filesystems.uploader.' . $file_type . '.size_limit')) {
            $message = '大小不能超过 ' . $this->byte_to_size(config('filesystems.uploader.' . $file_type . '.size_limit')) . '';
            return $this->responseAjax(8, false, $message);
        }


        // 保存附件到文件系统
        $result = $uploader->saveUploadFile($file_type, $this->create_object_id(), $file, $request->folder, intval($request->editor ?? 0));

        if ($result) {
            // 上传成功
            return $this->responseAjax(0, true, '上传成功', $result['path'], $result['url'], $result['id']);
        } else {
            // 上传失败
            return $this->responseAjax(6, false, '上传失败');
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
            'url' => $url,              // 完整可访问URL
            'message' => $message,          // 提示消息
            'path' => $path,             // 文件相对地址
            'id' => $id,               // 文件ID
            'multiple_id' => $multiple_id,      // 文件ID

            // 兼容 Simditor
            # 'success'           => $success,
            'msg' => $message,
            'file_path' => $url,

            // 兼容 Zui Uploader
            'result' => $success === true ? 'ok' : 'failed',
            # 'message'           => $message,
            # 'url'               => $url,

        ];


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

    /**
     * 生成 object_id
     */
    function create_object_id()
    {
        return base_convert(uniqid(), 16, 10);
    }


    /**
     * 图片库相关上传
     *
     * @param Request $request
     * @param UploadHandler $uploader
     * @param UploadFileHandler $uploadFileHandler
     * @param int $isRichText
     * @return array
     */
    public function uploadFilesUploader(Request $request, UploadHandler $uploader, UploadFileHandler $uploadFileHandler)
    {
        // 检测是否是允许的类型
        if (!in_array($request->folder, config('filesystems.uploader.folder', []))) {
            return $this->responseAjax(2, false, '非法上传，上传类型错误');
        }

        // 判断是否有上传文件，并赋值给 $file
        if (!$request->isRichText){
            if (!($file = $request->upload_file)) {
                return $this->responseAjax(3, false, '上传文件不允许未空');
            }
        }else{
            $file = $request->file;
        }


        // 获取上传的类型
        $file_type = $request->file_type ?? 'file';

        // 检查文件大小是否合法
        if ($file->getSize() <= 0) {
            return $this->responseAjax(7, false, '文件大小不能为: 0 ');
        }

        if ($file->getSize() > config('filesystems.uploader.' . $file_type . '.size_limit')) {
            $message = '大小不能超过 ' . $this->byte_to_size(config('filesystems.uploader.' . $file_type . '.size_limit')) . '';
            return $this->responseAjax(8, false, $message);
        }

        \DB::beginTransaction();
        // 保存附件到文件系统
        $result = $uploader->saveUploadFile($file_type, $this->create_object_id(), $file, $request->folder, intval($request->editor ?? 0));

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
                return $this->responseAjax(300, false, '上传失败【' . $exception->getMessage() . '】');
            }
            if ($request->get('isRichText'))
                return ['code'=>0,'msg'=>'上传成功','data'=>['src'=>$result['url']]];

            // 上传成功
            return $this->responseAjax(0, true, '上传成功', $result['path'], $result['url'], $result['id']);
        } else {
            \DB::rollBack();
            // 上传失败
            return $this->responseAjax(6, false, '上传失败');
        }
    }
}
