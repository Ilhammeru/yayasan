<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        $kp = Role::create(['name' => 'kepala sekolah']);
        $wkp = Role::create(['name' => 'wakil kepala']);
        $tu = Role::create(['name' => 'tu']);
        $wk = Role::create(['name' => 'wali kelas']);
        $g = Role::create(['name' => 'guru']);

        $kp_user = User::insert([
            'username' => 'kepalasekolah',
            'password' => Hash::make('admin'),
            'role' => $kp->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        $u_kp = User::where('username', 'kepalasekolah')->first();
        $u_kp->assignRole($kp);
    }
}
