<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class Tooltip extends AbstractDisplayer{


    public function display($placement="top")
    {
        // TODO: Implement display() method.
        Admin::script("$('[data-toggle=\"tooltip\"]').tooltip()");
        $label = $this->value?substr_replace($this->value,"****",3,4):"";
        return <<<EOT
<a href="#" data-toggle="tooltip" data-placement="{$placement}" title="{$this->value}">
{$label}</a>
EOT;
    }
}