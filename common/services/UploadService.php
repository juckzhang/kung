<?php
namespace common\services;

use common\services\base\Service;
use common\helpers\CommonHelper;
use common\models\UploadForm;
use common\components\UploadedFile;
use common\constants\CodeConstant;
use yii\base\Exception;

class UploadService extends Service
{
    //上传文件类型
    const FILE_MEDIA_POSTER = 'poster'; //文章

    private $fileScenarios = [];

    public function init()
    {
        //獲取文件上傳配置信息
        $conf = CommonHelper::loadConfig('upload');
        foreach($conf as $key => $item)
        {
            $this->fileScenarios[] = $key;
        }
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function upload($fileType,$fileName = null,$subDir = '')
    {
        if(! in_array($fileType,$this->fileScenarios))
            throw  new Exception($fileType . ' 配置信息不存在!');

        $model = new UploadForm(['scenario' => $fileType]);
        $model->file = UploadedFile::getInstance($model, 'file');

        if($model->file === null) return CodeConstant::UPLOAD_FILE_REQUIRED_ERROR;
        if ($model->validate()) {
            if($model->remoteUpload === true) return $this->saveToRemote($model,$fileName,$subDir);
            else return $this->saveAs($model,$fileName,$subDir); //本地上传
        }else {
            //不是一个文件
            return current(current($model->getErrors()));
        }
    }

    public function multiUpload($fileType,$fileName = null,$path = '')
    {
        if(! in_array($fileType,$this->fileScenarios))
            throw  new Exception($fileType . ' 配置信息不存在!');

        $model = new UploadForm(['scenario' => $fileType]);

        $files = UploadedFile::getInstances($model, 'file');

        if($files === null) return CodeConstant::UPLOAD_FILE_REQUIRED_ERROR;

        if ($model->validate()) {
            $_return = [];
            foreach($files as $file)
            {
                $model->file = $file;
                if($model->remoteUpload === true) $_return[] =  $this->saveToRemote($model,$fileName,$path);
                else $_return[] =  $this->saveAs($model,$fileName,$path); //本地上传
            }
            return $_return;

        }else {
            //不是一个文件
            return current(current($model->getErrors()));
        }
    }

    private function saveToRemote(UploadForm $model,$fileName,$subDirStr = '')
    {
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        //递归创建目录
        if($model->getRecursive())
            $subDirStr .= $this->createSubDirString($model->recursive);

        //获取完整文件名
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) .DIRECTORY_SEPARATOR;
        if($fileName !== null)
            $fullFileName = $model->getPath() . DIRECTORY_SEPARATOR  . $subDirStr .$fileName . '.'.$model->file->extension;
        else
            $fullFileName = $model->getPath() . DIRECTORY_SEPARATOR  . $subDirStr . $model->file->baseName . '.' . $model->file->extension;

        if($model->file->saveToRemote($fullFileName))
            return [
                'url' => \Yii::$app->params['imageUrlPrefix'] . $fullFileName,
                'fullFileName' => $fullFileName,
                'type' => $model->file->type,
                'size' => $model->file->size,
                'width' => $model->file->width,
                'height' => $model->file->height,
            ];

        return CodeConstant::UPLOAD_FILE_FAILED;
    }

    private function saveAs(UploadForm $model,$fileName = null,$subDirStr = '')
    {
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        //递归创建目录
        if($model->getRecursive())
        {
            $subDirStr .= $this->createSubDirString($model->recursive);
            //创建子目录
            mkdir($model->getDir() . DIRECTORY_SEPARATOR .$model->getPath() . DIRECTORY_SEPARATOR .$subDirStr,0755,true);
        }
        //获取完整文件名
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if($fileName !== null)
        {
            $relativePath = $model->getPath() . '/'  . $subDirStr .$fileName . '.'.$model->file->extension;
            $fullFileName = $relativePath;//\Yii::getAlias('@webroot/') . $relativePath;
        }
        else{
            $relativePath = $model->getPath() . '/'  . $subDirStr . $model->file->baseName . '.' . $model->file->extension;
            $fullFileName = $relativePath;//\Yii::getAlias('@webroot/') . $relativePath;
        }

        if($model->file->saveAs($model->getDir().DIRECTORY_SEPARATOR.$fullFileName, false))
            return [
                'url' => \Yii::$app->params['imageUrlPrefix'] . $fullFileName,
                'fullFileName' => $relativePath,
                'type' => $model->file->type,
                'size' => $model->file->size,
                'width' => $model->file->width,
                'height' => $model->file->height,
            ];

        return CodeConstant::UPLOAD_FILE_FAILED;
    }

    private function createSubDirString($recursive = false)
    {
        $subDir = '';
        if(is_int($recursive) && $recursive > 0)
        {
            for($i = 0; $i < $recursive; ++$i)
            {
                $subDir .= CommonHelper::randString(6) . DIRECTORY_SEPARATOR;
            }
        }
        return trim($subDir ,DIRECTORY_SEPARATOR);
    }


    public function base64($base64_image_content)
    {
        header('Content-type:text/html;charset=utf-8');
        if(!$base64_image_content) return ['code'=>404,'msg'=>'数据不能为空','data'=>null];
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = \Yii::$app->basePath."/web/images/tmp/";

            if(!file_exists($new_file))
            {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0755);
            }
            $imgName = time().".{$type}";
            $new_file = $new_file.$imgName;
            
            // base64解码后保存图片
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){

                // 更新用户的头像地址路径
                return ['code'=>200,'msg'=>'保存成功','imgUrl'=>$new_file,'imgName'=>$imgName];
            }else
                return ['code'=>4041,'msg'=>'文件保存失败','data'=>null];
        }
    }
}