<?php
/**
 * 描述：公共上传类
 * 作者：671
 * 时间：2018年8月28日21:16:20
 */

namespace app\admin\controller;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class Upload
{
    private $ak = '3-0rR_0ud4z_2W77oWxngrS5gutZ1taSQNWSvmu1';
    private $sk = 'SOExn8wPnRHOK0O-hD_3XxHMaboKjt-uOHDnFWK2';
    private $bucket = 'appdownloady';
    private $endpoint = 's3-cn-east-1.qiniucs.com';
    private $domain = 'https://qn.wrongdoing.wang/';

    // 七牛上传
    public function qnupload()
    {
        $auth = new Auth($this->ak, $this->sk);
        $token = $auth->uploadToken($this->bucket);

        $file = $_FILES['file'];
        $ext  = pathinfo($file['name'])['extension'];
        $save = date('Ym').'/'.time().'.'.$ext;

        try {
            $uploadMgr = new UploadManager();
            list($res, $err) = $uploadMgr->putFile($token, $save, $file['tmp_name']);

            if ($err !== null) {
                $result = ['code' => -1, 'msg' => '上传失败'];
            } else {
                $path = $this->domain.$save;
                $result = ['code' => 0, 'path' => $path, 'data' => $file];
            }
            return json($result);
        } catch(Exception $e) {
            return json([
                'code'  => 0,
                'errmsg'=> $e->getMessage(),
                'msg'   => '上传异常，请联系客服处理'
            ]);
        }
    }

    // 获取七牛token
    public function gettoken()
    {
        $auth = new Auth($this->ak, $this->sk);
        $token = $auth->uploadToken($this->bucket);

        return json([
            'code'  => 200,
            'msg'   => 'Sucess',
            'token' => $token
        ]);
    }

    // 分片上传测试
    public function test()
    {
        $data = $_FILES['data'];
        $name = $_REQUEST['name'];
        $chunks = $_REQUEST['chunks'];
        $chunk  = $_REQUEST['chunk'];

        if ($data['error'] > 0) {
            $status = 500;
        } else {
            // 追加数据
            if (!file_put_contents('uploads/'.$name, file_get_contents($_FILES['data']['tmp_name']), FILE_APPEND)) {
                $status = 501;
            } else {
                if ($chunks === $chunk) {
                    if (filesize('uploads/'.$name) == $total_size) {
                        $status = 200;
                    } else {
                        $status = 502;
                    }
                } else {
                    $status = 200;
                }
            }
        }

        return json([
            'code' => $status,
            'total_size' => filesize('uploads/'.$name),
            'msg'  => $data
        ]);
    }

    // 文件上传
    public function index()
    {
        // 接收数据进行上传分类
        $type = \think\facade\Request::post('type');
        $path = './uploads/'.$type;

        $file = request()->file('file');
        $info = $file->move($path);

        if ($info) {
            return json([
                'code' => 0,
                'msg' => '上传成功',
                'path' => '/uploads/'.$type.'/'.$info->getSaveName()
            ]);
        }

        return json([
            'code' => -1,
            'msg' => '上传失败',
            'data' => $file->getError()
        ]);
    }

    // app上传
    public function app()
    {
        // 接收数据进行上传分类
        $type = \think\facade\Request::post('type');
        $name = \think\facade\Request::post('name');
        $path = './uploads/'.$type;
        $namemsg = 'android上传成功';

        if ($name == 'android') $namemsg = 'android上传成功';
        if ($name == 'ios') $namemsg = 'ios上传成功';
        $file = request()->file('app');
        $info = $file->move($path);

        if ($info) {
            return json([
                'code' => 0,
                'msg' => '上传成功',
                'path' => '/uploads/'.$type.'/'.$info->getSaveName(),
                'name' => $namemsg
            ]);
        }

        return json([
            'code' => -1,
            'msg' => '上传失败',
            'data' => $file->getError()
        ]);
    }
}
