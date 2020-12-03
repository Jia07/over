<?php

namespace app\login\controller;

use think\Controller;
use think\Db;
use think\Request;

class Image extends Controller
{
    /**
     * 显示资源列表
     * 相册列表
* @return \think\Response
     */
    public function index()
    {
//        登录身份验证
        $user = session('user');
        $uid = $user['user_id'];
//        取出 查看当前登录用户
        if ($user){
//        分类
            $class = Db::table('class')->select();
//        图片展示
            $img = Db::table('image')->where('uid',$uid)->paginate(2);
//        dump($img);die();
            return view('index',['class' => $class,'img' => $img,'user' => $user]);
        }else{
            $this->error('登录账号','Login/index');
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收参数
        $param = $request->param();
//        dump($param);die();
        //验证参数
        $result = $this->validate($param,
            [
                'title|标题' => 'require',
                'class|分类'   => 'require|number',
                'uid|用户'  => 'require|number',
                'img|图片' => 'require',
            ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            $this->error($result);
        }
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('img');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $param['img'] = $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
//        登录身份验证
        $user = session('user');
        $param['uid'] = $user['user_id'];
//        取出 查看当前登录用户
        if ($user){
            $data = Db::table('image')->insert($param);
            $this->redirect('Image/index');
        }else{
            $this->error('登录账号进行上传','Login/index');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(Request $request)
    {
        //关键词搜索
        $param = $request->param();
        $search = $param['search'];
//        查询数据
        $data = Db::table('image')->where('title',$search)->paginate(1);
//        方法封装
//        $data = search($search);
        return view('read',['data'=>$data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit()
    {
        //
        return view();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
