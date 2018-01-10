<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends ApiController
{
    public function __construct()
    {
    }

    public function upload(Request $request)
    {
        if (! $request->hasFile('files')) {
            return $this->errorRespond('上传的文件名必须是files');
        }

        $file = $request->file('files');

        $fileName = Storage::put('', $file); //自动命名

        if (! Storage::exists($fileName)) {
            return $this->errorRespond('上传失败');
        }

        if ($request->has('attachment')){
            return $this->jsonRespond([
                'filename' => qiniu_path($fileName),
            ]);
        }

        return $this->jsonRespond([
            'full_path' => qiniu_path($fileName),
            'path' => $fileName
        ]);
    }

    /**
     * 获取七牛上传token
     * @return \Illuminate\Http\JsonResponse
     */
    public function token()
    {
        $token = Storage::disk('qiniu')->getAdapter()
            ->getUploadToken();

        if (! $token) {
            return $this->errorRespond();
        }

        return $this->jsonRespond([
            'token' => $token
        ]);
    }
}
