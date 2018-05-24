<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Permission;

class PermissionTransformer extends TransformerAbstract{
    public function transfrom(Permission $permission){
        return [
            'id'=>$permission->id,
            'name'=>$permission->name
        ];
    }
}