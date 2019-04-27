<?php
namespace frontend\services;

use common\models\mysql\AdModel;
use common\services\AdService;
use common\services\ArticleService;
use frontend\services\base\FrontendService;
use common\services\VideoService;
use common\services\UserService;
use common\services\FilmService;

class IndexService extends FrontendService
{
    public function recommendList(){
        $videoService = VideoService::getService();
        $articleService = ArticleService::getService();
        $userService = UserService::getService();
        $FilmService = FilmService::getService();
        //取最新视频列表
        $data['articleList'] = $articleService->articleList(1, 10);
        $data['newList'] = $videoService->newAlbum(15,0);
        $data['hotList'] = $videoService->hotAlbum(13,0);
        $data['clipList'] = $videoService->recommendAlbum(1,18);//短片
        $data['dramaList'] = $videoService->recommendAlbum(2,13);//网络剧
        $data['movieList'] = $videoService->recommendAlbum(3,15);//网络电影
        $data['columnList'] = $videoService->recommendAlbum(4,15);//网络栏目
        $data['carouselList'] = AdService::getService()->adList(AdModel::AD_POSITION_INDEX_TOP);
        $data['userList'] = $userService->getPushUser(8);
        $data['filmList'] = $FilmService->getPushFilm(3);
//        $data['carouselList'] = $videoService->carouselList(3);
        return $data;
    }
}

