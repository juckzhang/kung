<?php
namespace common\services\base;

use yii\base\Model;
class Service extends Model
{
    const DEFAULT_PAGE = 0;  //默认显示页
    const DEFAULT_PRE_PAGE = 20; //默认每页显示数量

    protected $lang = [
        'en_US' => 'en',
    ];

    public $redis = null;
    public $cache = NULL;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * 服务对象实例
     * @var array
     */
    protected static $_services = [];

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getService(){
        $class_name = static::className();
        if( ! isset(static::$_services[$class_name]) || ! static::$_services[$class_name] instanceof Service)
            static::$_services[$class_name] = \yii::createObject($class_name);
        return static::$_services[$class_name];
    }

    /**
     * 解析分页参数
     * @param $page
     * @param $prePage
     * @return array
     */
    protected function parsePageParam($page,$prePage)
    {
        $limit = is_numeric($prePage) ? (int)$prePage : static::DEFAULT_PRE_PAGE;
        $offset = (is_numeric($page) AND $page > 0 ) ? $page * $limit : static::DEFAULT_PAGE;
        return [(int)$offset,(int)$limit];
    }

    /**
     * 获取数据页数
     * @param $dataCount
     * @param $prePage
     * @return float|int
     */
    protected function reckonPageCount($dataCount,$prePage)
    {
        if( !is_numeric($dataCount) OR ! is_numeric($prePage) OR $prePage <= 0) return 0;

        return ceil($dataCount / $prePage);
    }

    /**
     * 检查资源是否存在
     * @param $id
     * @param $modelName
     * @return mixed
     */
    protected function checkSource($id,$modelName)
    {
        $primaryKey = $modelName::primaryKey()[0];
        return $modelName::find()->where([$primaryKey => $id,'status' => $modelName::STATUS_ACTIVE])->exists();
    }

    public function modelList($modeName, $keyWord,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [], 'pageCount' => 0, 'dataCount' => 0];
        $models = $modeName::find()->where(['status' => $modeName::STATUS_ACTIVE]);
            //->andFilterWhere(['like','name',$keyWord]);

        if(!$keyWord){
            $models = $models->andFilterWhere(['like','name',$keyWord]);
        }

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'], $limit);

        if ($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();
        }

        return $data;
    }

    protected function changeLang($lang){
        return isset($this->lang[$lang]) ? $this->lang[$lang] : $lang;
    }
}