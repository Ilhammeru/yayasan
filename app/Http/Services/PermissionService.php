<?php

namespace App\Http\Services;

use App\Models\PermissionGroup;
use Spatie\Permission\Models\Permission;

class PermissionService {
    public function get_permission_group()
    {
        $permissions = Permission::all();
        $groups = PermissionGroup::all();

        $permissions = collect($permissions)->map(function($item) use ($groups) {
            foreach($groups as $g) {
                if ($item['permission_group_id'] == $g->id) {
                    $item['group'] = $g->name;
                }
            }

            return $item;
        })->groupBy('group')->all();

        return $permissions;
    }
}