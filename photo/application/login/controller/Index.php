<?php


namespace app\login\controller;


use think\Collection;

class Index extends Collection
{
//  token CSRF安全验证
    public function index()
    {
        $token = $this->request->token('__token__', 'sha1');
        $this->assign('token', $token);
        return $this->fetch();
    }
}