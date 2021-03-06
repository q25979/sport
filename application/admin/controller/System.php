<?php
namespace app\admin\controller;

use app\common\model\Admin;
use think\facade\Request;
use think\Db;

class System extends Validate
{
    // 配置文件路径
    private $confpath = '/usr/local/nginx/conf/vhost/link.conf';

	// 数据库备份
    public function dbbackup()
    {
        return $this->fetch();
    }

    // 数据库备份
    public function dbbackups() 
    {
        // 设置颜色
        echo "<style>body{color:#32CD32;}</style>";
        $dbname = \Config::get()['database']['database'];
        $path = '/backups/';
        is_dir($path) OR mkdir($path);
        $filename = '/backups/'.date('YmdHis').'.sql';
        $byte = 0; // 大小

        echo "==> 数据库备份中, 请稍候......<br />";
        // 获取数据表
        $tables = DB::query("SHOW TABLES FROM $dbname");
        foreach ($tables as $key => $value) {
            // 获取数据表结构
            $showtable = Db::query("SHOW CREATE TABLE ".$value['Tables_in_luodiye']);

            // drop结构
            $isdrop = "DROP TABLE IF EXISTS `".$showtable[0]['Table']."`;\r\n";
            $table_struct = $isdrop.$showtable[0]['Create Table'].";\r\n\r\n";
            echo "==> 备份成功 -------------------------------- ".$showtable[0]['Table'];

            // 获取表数据
            $datatable = Db::query("SELECT * FROM ".$showtable[0]['Table']);
            $sqlstr = '';
            foreach ($datatable as $key => $value) {
                $sqlstr .= "INSERT INTO `".$showtable[0]['Table']."` VALUES (";
                foreach ($value as $key => $value) {
                    // 判断类型
                    if (gettype($value) == NULL || gettype($value) == 'NULL') {
                        $value = "NULL";
                    } elseif (gettype($value) == 'string') {
                        $value = str_replace('\\', '\\\\', $value);
                        $value = "'$value'";
                    }

                    $sqlstr .= $value.",";
                }
                // 去掉最后一个逗号
                $sqlstr = substr($sqlstr, 0, strlen($sqlstr)-1);
                $sqlstr .= ");\r\n";
            }

            // 保存到文件
            $content = "\r\n".$table_struct.$sqlstr;
            $file = file_put_contents($filename, $content, FILE_APPEND);
            $byte += $file;
            echo " ($file byte)<br />";
        }
        echo "==> 数据库备份成功。。。<br />";
        echo "==> 数据库总大小为：".round($byte/1024, 2)." kb<br />";
        echo "==> 数据库文件路径为: $filename<br />";
    }

    // 配置文件
    public function conf()
    {
        // 文件路劲
        $open = fopen($this->confpath, 'r+') or die("配置文件打开失败!");
        $i = 0;
        $confinfo = '';
        while(!feof($open)) {
            $confinfo .= fgets($open);
        }
        fclose($open);
        
        $this->assign('confinfo', $confinfo);
        return $this->fetch();
    }

    // 保存配置文件
    public function save()
    {
        // 接收文件
        $data = Request::post('data');
        $save = fopen($this->confpath, 'w+');
        if (!$save) {
            return json([
                'code'  => -1,
                'msg'   => '文件打开失败'
            ]);
        }

        fwrite($save, $data);
        fclose($save);
        return json([
            'code'  => 0,
            'msg'   => '文件保存成功'
        ]);
    }

    // 重启nginx服务状态
    public function reload()
    {
        exec("service nginx restart", $out);
        return json([
            'code'  => 0,
            'msg'   => '重启成功'
        ]);
    }
}
