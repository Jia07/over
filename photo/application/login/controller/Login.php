<?php

namespace app\login\controller;

use think\Controller;
use think\Db;
use think\Request;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //显示登陆页面
        return view('index');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
//        接收参数
        $param = $request->param();
//        验证参数
        $result = $this->validate($param,
            [
                'user_name|用户名'  => 'require|max:25',
                'user_password|密码'   => 'require|number',
            ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            $this->error($result);
        }
//        账号 密码
        $user_name = $param['user_name'];
        $user_password = $param['user_password'];
//        根据账号查询数据
        $data = Db::table('user')->where('user_name',$user_name)->find();
        if ($data){
//            验证密码
            if ($user_password == $data['user_password']){
//                成功存储session
                session('user',$data);
//                成功跳转
                $this->success('登陆成功','Image/index');
            }else{
//                错误返回
                $this->error('登陆失败，密码错误，请重新输入');
            }
        }else{
//            错误返回
            $this->error('登陆失败，账号错误，请重新输入');
        }
    }


}
