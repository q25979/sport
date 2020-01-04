<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\common\model\User;
use think\facade\Request;

// 数据管理
class Download extends Controller
{
    // 获取下载数据
    public function index()
    {
        $this->origin();
        $get = Request::get();
        $user = User::get($get['id']);
        $download = $user->download;

        // 数据处理
        $list = [
            'android_app_time'  => '2019-12-20 19:35:41',
            'android_app_url'   => $download['domain'].$download['android_url'],
            'android_file_size' => sprintf("%.2f", $download['size'] / 1024 / 1024),
            'app_download_link' => $download['download_page'],
            'app_name'  => $download['name'],
            'app_upload_url'    => $download['domain'].$download['qrcode'],
            'ios_app_time'      => '2019-12-20 19:34:45',
            'ios_app_url'       => $download['domain'].$download['ios_url'],
            'ios_file_size'     => sprintf("%.2f", $download['size'] / 1024 / 1024),
            'present_version_number'    => $download['version'],
            'web_logo'          => $download['domain'].$download['logo']
        ];

        $result = [
            'code'  => 200,
            'msg'   => 'Success',
            'data'  => $list
        ];
        return json($result);
    }

    // 更新下载量
    public function update()
    {
        $this->origin();
        $get = Request::get();
        $user = User::get($get['id']);
        $download = $user->download;

        if ($download->status == 0) {
            return json([
                'code'  => -1,
                'msg'   => '项目已关闭下载功能'
            ]);
        }

        if ($download->user_download <= 0) {
            return json([
                'code' => -1,
                'msg'  => '下载量不足请先充值'
            ]);
        } else {
            $map['uid'] = $get['id'];
            Db::table('ff_download')->where($map)->setInc('total_download');
            Db::table('ff_download')->where($map)->setDec('user_download');
            return json([ 'code' => 200, 'msg' => 'Success' ]);
        }
    }

    // 跨域
    public function origin()
    {
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
    }
}
