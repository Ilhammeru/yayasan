<?php

namespace Database\Seeders;

use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::truncate();

        $role_tu = Role::findByName('tu');
        $role_guru = Role::findByName('guru');
        $role_wali_kelas = Role::findByName('wali kelas');
        $role_kepala = Role::findByName('kepala sekolah');
        $role_wakil = Role::findByName('wakil kepala');
        $role_bendahara = Role::findByName('bendahara yayasan');

        Position::insert([
            ['name' => 'Kepala Sekolah', 'role_id' => $role_kepala->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Wakil Kepala Sekolah', 'role_id' => $role_wakil->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'TU', 'role_id' => $role_tu->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Wali Kelas', 'role_id' => $role_wali_kelas->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Guru', 'role_id' => $role_guru->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bendahara Yayasan', 'role_id' => $role_bendahara->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
