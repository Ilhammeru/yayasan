<?php

namespace App\Http\Services;

use App\Models\PermissionGroup;
use Spatie\Permission\Models\Permission;

class PermissionService {
    public function get_permission_group($role = null)
    {
        $permissions = Permission::all();
        $groups = PermissionGroup::all();

        $permissions = collect($permissions)->map(function($item) use ($groups, $role) {
            foreach($groups as $g) {
                if ($item['permission_group_id'] == $g->id) {
                    $item['group'] = $g->name;
                }
            }
            $item['active'] = false;

            if ($role) {
                $current_permission = $role->permissions;
                foreach ($current_permission as $cp) {
                    if ($item['id'] == $cp['id']) {
                        $item['active'] = true;
                    }
                }
            }

            return $item;
        })->groupBy('group')->all();

        return $permissions;
    }
}