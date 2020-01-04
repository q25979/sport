<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24 0024
 * Time: 16:34
 */
namespace app\admin\controller;

use app\common\model\User;
use think\Controller;
use think\facade\Request;
use think\captcha\Captcha;
use think\facade\Session;

class Login extends Controller
{
    public function index()
    {
        // 账号登录
        if (Request::isPost()) {
            $post = Request::post();

            if (!captcha_check($post['code'])) {
                return json(['code' => -1, 'msg' => '验证码错误', 'data' => Session::get('ThinkPHP.CN')]);
            }

            $info = User::where(['login_user' => $post['username']])
                ->find();

            // 账号不存在
            if (empty($info)) {
                return json(['code' => -1, 'msg' => '账号不存在']);
            }

            // 密码错误
            $password =  md5($post['password'].strtotime($info['create_time']));
            if ($info['login_pass'] != $password) {
                return json(['code' => -1, 'msg' => '密码错误', 'data' => $info]);
            }

            // 都正确,设置cookie
            // json_decode(base64_decode($binfo))
            cookie('authentication', base64_encode($info));

            // 设置ip
            $ip = $_SERVER['REMOTE_ADDR'];
            $update = [
                'last_login_ip' => $ip,
                'last_login_time' => time()
            ];
            User::where('id', $info['id'])->update(['last_login_ip' => $ip]);

            return json(['code' => 0, 'msg' => '登录成功']);
        }

        return $this->fetch();
    }

    // 账号退出
    public function logout()
    {
        cookie(null, 'ld_');
        return json(['code' => 0, 'msg' => '清除成功']);
    }
}
