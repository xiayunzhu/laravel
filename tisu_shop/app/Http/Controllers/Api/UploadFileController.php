<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UploadFileHandler;
use App\Handlers\UploadHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UploadFile\StoreRequest;
use App\Lib\Response\Result;

class UploadFileController extends Controller
{
    private $uploadFileHandler;

    public function __construct(UploadFileHandler $uploadFileHandler)
    {
        $this->uploadFileHandler = $uploadFileHandler;
    }

    /**
     * @param StoreRequest $request
     * @param Result $result
     * @param UploadHandler $uploader
     * @return array
     */
    public function store(StoreRequest $request, Result $result ,UploadHandler $uploader)
    {
        try {
            $data = $this->uploadFileHandler->uploadFilesUploader($request,$uploader,$this->uploadFileHandler,'appPics');
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }


        return $result->toArray();
    }



}
