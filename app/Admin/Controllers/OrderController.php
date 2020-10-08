<?php

namespace App\Admin\Controllers;

use App\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', ('編號'));
        $grid->column('user_id', ('使用者編號'));
        $grid->column('address', ('寄送地址'));
        $grid->column('total', ('總計'));
        $grid->column('closed', ('是否關閉'));
        $grid->column('created_at', ('建立時間'));
        $grid->column('updated_at', ('更新時間'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('address', __('Address'));
        $show->field('total', __('Total'));
        $show->field('closed', __('Closed'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->number('user_id', __('User id'));
        $form->textarea('address', __('Address'));
        $form->number('total', __('Total'));
        $form->switch('closed', __('Closed'));

        return $form;
    }

    public function index(Content $content)
{
    return $content
        ->header('訂單管理')
        ->description('管理所有訂單')
        ->body($this->grid());
}
}
