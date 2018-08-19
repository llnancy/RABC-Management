<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return '前台首页<br>' . '<a href="/login">后台管理</a>';
    }
}
