<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use App\Admin\Extensions\Tooltip;
use Encore\Admin\Grid\Column;

Encore\Admin\Form::forget(['map', 'editor']);
Encore\Admin\Form::extend('editor', \App\Admin\Extensions\WangEditor::class);
Column::extend('tooltip',Tooltip::class);
\Encore\Admin\Admin::js('/js/Chart.min.js');
