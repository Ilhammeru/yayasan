<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'master expense'
            ],
            'transactions' => [],
        ];

        $role = Role::findByName('kepala sekolah');
        foreach ($names as $key => $n) {
            $group = PermissionGroup::insertGetId(['name' => $key, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            if (count($n) > 0) {
                foreach($n as $p) {
                    $create = Permission::create(['name' => $p, 'permission_group_id' => $group]);
                    if ($role) {
                        $role->givePermissionTo($create);
                    }
                }
            }
        }


    }
}
