<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Request;
use app\common\model\NewData;

class Index extends Controller
{
	// 首页
    public function index()
    {
        return $this->fetch();
    }

    // 体育新闻
    public function sportData()
    {
        if (Request::isAjax()) {
            $get = Request::get();
            $new = new NewData();
            $list = $new->page($get['page'], $get['limit'])->select();

            return json([
                'code'  => 0,
                'msg'   => 'Success',
                'data'  => $list,
                'count' => $new->count()
            ]);
        }
        return $this->fetch();
    }

    // 添加新闻数据
    public function addData()
    {
        return $this->fetch();
    }

    // 今日赛事
    public function today()
    {
        return $this->fetch();
    }

    // 用户管理
    public function user()
    {
        return $this->fetch();
    }
}
