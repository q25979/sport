<?php
namespace app\admin\controller;

use app\common\model\User;
use app\common\model\Rule;
use app\common\model\Download;
use think\facade\Request;

class Index extends Validate
{
	// 首页
    public function index()
    {
        echo strtotime("-490minute");
        return $this->fetch();
    }

    // 主页
    public function home()
    {
        $user = User::get($this->id);
        $download = $user->download;

        $this->assign('download', $download);
        return $this->fetch();
    }

    private function plist($data)
    {
        $upload = 'uploads/';
        $path  = $upload.'app/'.time().'.plist';
        $plist = fopen($upload.'template.plist', 'r+');
        $content = "";
        $write = fopen($path, 'w+');

        if (!$plist) {
            return ['code' => -1, 'msg' => '读取文件失败'];
        }
        while(!feof($plist)) {
            $content .= fgets($plist);
        }
        $content = str_replace('{$url}', $data['ios_url'], $content);
        $content = str_replace('{$logo}', $data['logo'], $content);
        $content = str_replace('{$version}', $data['version'], $content);
        $content = str_replace('{$name}', $data['name'], $content);

        if (!fwrite($write, $content)) {
            return ['code' => -1, 'msg' => '文件写入失败'];
        }

        fclose($write);
        fclose($plist);

        return ['code' => 200, 'path' => $path];
    }

    // 修改信息
    public function update() {
        if (Request::isPost()) {
            $post = Request::post();
            $map['uid'] = $this->id;

            if (!empty($post['ios_url'])) {
                $plist = $this->plist($post);
                if ($plist['code'] != 200) {
                    return $plist;
                } else {
                    if (substr($post['ios_url'], strlen($post['ios_url'])-3, 3) == 'ipa') {
                        $post['ios_url'] = 'https://download.vbsfhgsd.cn/'.$plist['path'];
                    }
                }
            }

            $info = Download::where($map)->data($post)->update();
            if ($info > 0) {
                return json(['code' => 0, 'msg' => '数据更新成功，请手动刷新页面']);
            } else {
                return json(['code' => -1, 'msg' => '数据更新失败，请联系客服']);
            }
        }

        $user = User::get($this->id);
        $download = $user->download;
        $this->assign('download', $download);
        return $this->fetch();
    }

    // 侧边菜单
    public function menu()
    {
        $user = User::get($this->id);
        $rule = $user->permissions->rule_id;

        if ($rule != 'all') {
            $list = Rule::all($rule);
        } else {
            $list = Rule::all();
        }

        $result['code'] = 200;
        $result['msg']  = '获取成功';
        $result['menu'] = $list;
        return json($result);
    }

    // 控制台
    public function console()
    {
        $controls = new Controls();

        $this->assign([
            'S' => $controls->S,
        ]);
        return $this->fetch();
    }

    // 实时获取服务器数据
    public function getRealTime()
    {
        $controls = new Controls();

        return json($controls);
    }

    // 密码修改
    public function passwordSave()
    {
        $post = Request::post();
        $user = User::get($this->id);

        // 更改密码
        $user->login_password = md5($post['password'].strtotime($user->create_time));
        $info= $user->save();

        if (empty($info)) {
            return json(['code' => -1, 'msg' => '密码修改失败']);
        }

        return json(['code' => 0, 'msg' => '密码修改成功']);
    }

    // 清除缓存
    public function clearCache()
    {
        $name = Request::get('name');
        return json(cache($name, NULL));
    }
}
