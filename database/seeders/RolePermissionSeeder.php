<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::table('model_has_roles')
            ->where('role_id', '>', '0')
            ->delete();
        DB::table('model_has_permissions')
            ->where('permission_id', '>', '0')
            ->delete();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        $alls = [
            'kepala sekolah',
            'wakil kepala',
            'tu',
            'wali kelas',
            'guru',
            'bendahara yayasan'
        ];
        foreach ($alls as $all) {
            Role::create(['name' => $all]);
        }
        // $kp = Role::findByName('kepala sekolah');
        // $by = Role::findByname('bendahara yayasan');
        // $wkp = Role::create(['name' => 'wakil kepala']);
        // $tu = Role::create(['name' => 'tu']);
        // $wk = Role::create(['name' => 'wali kelas']);
        // $g = Role::create(['name' => 'guru']);
    }
}
