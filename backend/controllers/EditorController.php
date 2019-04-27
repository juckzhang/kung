<?php
namespace backend\controllers;

use crazydb\ueditor\UEditorController;
use common\services\UploadService;

/**
 * Site controller
 */
class EditorController extends UEditorController
{
    public function actionUploadImage()
    {
        $uploadService = UploadService::getService();
        $res = $uploadService->upload('article');

        $result = [];
        if (is_array($res)) {
            $parts = explode('/', $res['url']);
            $filename = end($parts);

            $parts = explode('.', $filename);
            $ext = end($parts);

            $result['state'] = 'SUCCESS';
            $result['url'] = $res['url'];
            $result['title'] = $filename;
            $result['original'] = $filename;
            $result['type'] = ".$ext";
            $result['size'] = $res['size'];
            $result['height'] = $res['height'];
            $result['width'] = $res['width'];
        } else {
            $result['state'] = '上传失败';
        }

        return $this->show($result);
    }
}
