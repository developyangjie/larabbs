<?php

namespace App\Admin\Controllers;

use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            // 选填
            $content->header('用户列表');

            // 选填
//            $content->description('填写页面描述小标题');

            // 添加面包屑导航 since v1.5.7
            $content->breadcrumb(
                ['text' => '用户管理', 'url' => '/users'],
                ['text' => '用户列表']
            );

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            // 选填
            $content->header('修改信息');

            // 选填
//            $content->description('填写页面描述小标题');

            // 添加面包屑导航 since v1.5.7
            $content->breadcrumb(
                ['text' => '用户管理', 'url' => '/users'],
                ['text' => '用户修改']
            );

            $content->body($this->form()->edit($id));
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->model()->orderBy("created_at","desc");
            $grid->perPages([10, 20, 30, 40, 50]);

            $grid->disableCreateButton();
            $grid->disableExport();


            $grid->id('ID')->sortable();

            $grid->avatar()->image("null",50,50);

            $grid->phone("手机号")->tooltip();

            $grid->name("用户名");
            $states = [
                'on' => ['text' => 'YES'],
                'off' => ['text' => 'NO'],
            ];

            $grid->email("邮箱")->display(function($email){
                return "<span style='color:blue'>$email</span>";
            });
            // filter($callback)方法用来设置表格的简单搜索框
            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 设置created_at字段的范围查询
                $filter->between('created_at',"开始时间")->datetime();
            });

            $grid->created_at("创建时间");
            $grid->updated_at("上次操作");
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {


                $form->display('id', 'ID');
                $form->editor('name', '用户名');
                $form->display('email', '邮箱');
                $form->display('created_at', '创建时间');
                $form->display('updated_at', '上次操作');
                $form->divide();
                //$form->display('avatar')->resize(100,100)->move('imgaes/avatars/'.date("Ym").'/'.date("d"))->uniqueName();
                $form->display('avatar',"头像")->with(function ($value) {
                    return "<img src='$value' />";
                });
        });
    }
}
