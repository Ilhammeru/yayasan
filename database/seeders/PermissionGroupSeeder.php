<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        PermissionGroup::truncate();

        $names = [
            'master' => [
                'master role',
                'master institution',
                'master permission',
                'master position',
                'master employee',
                'master expense',
            ],
            'transactions' => [
                'income create',
                'income edit',
                'income view',
                'income delete',
                'income show',
                'income list',
                'foundation finance',
            ],
            'proposal' => [
                'approve proposal',
                'reject proposal',
                'cash out proposal budget',
                'create proposal',
                'edit proposal',
                'list proposal',
                'detail proposal',
            ],
        ];

        $role = Role::findByName('kepala sekolah');
        $role1 = Role::findByName('kepala yayasan');
        foreach ($names as $key => $n) {
            $group = PermissionGroup::insertGetId(['name' => $key, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            if (count($n) > 0) {
                foreach ($n as $p) {
                    $f = Permission::where('name', $p)->first();
                    if ($f != null) {
                        $f->delete();
                    }
                    $create = Permission::create(['name' => $p, 'permission_group_id' => $group]);
                    if ($role) {
                        $role->givePermissionTo($create);
                        $role1->givePermissionTo($create);
                    }
                }
            }
        }
    }
}
