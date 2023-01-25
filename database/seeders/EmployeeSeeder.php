<?php

namespace Database\Seeders;

use App\Models\Employees;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employees::truncate();
        User::truncate();
        DB::table('model_has_roles')
            ->where('role_id', '>', '0')
            ->delete();

        // roles
        $role_tu = Role::findByName('tu');
        $role_guru = Role::findByName('guru');
        $role_wali_kelas = Role::findByName('wali kelas');
        $role_treasurer = Role::findByName('Bendahara Yayasan');
        $role_kepala = Role::findByName('kepala sekolah');
        $role_kepala_yayasan = Role::findByName('kepala yayasan');

        // positions
        $pos_treasurer = Position::select('id')->where('name', 'Bendahara Yayasan')->first();
        $pos_guru = Position::select('id')->where('name', 'guru')->first();
        $pos_tu = Position::select('id')->where('name', 'tu')->first();
        $pos_wali = Position::select('id')->where('name', 'wali kelas')->first();
        $pos_kepala = Position::select('id')->where('name', 'kepala sekolah')->first();
        $pos_kepala_yayasan = Position::select('id')->where('name', 'kepala yayasan')->first();

        // user 1 as TU
        $user_1 = User::insertGetId([
            'username' => 'tu1',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_tu->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_1 = User::find($user_1);
        $user_d_1->assignRole($role_tu);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_1,
            'email' => fake()->email(),
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_tu->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // user 2 as guru 1
        $user_2 = User::insertGetId([
            'username' => 'guru1',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_guru->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_2 = User::find($user_2);
        $user_d_2->assignRole($role_guru);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_2,
            'email' => fake()->email(),
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_guru->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // user 3 as guru 2
        $user_3 = User::insertGetId([
            'username' => 'guru2',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_guru->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_3 = User::find($user_3);
        $user_d_3->assignRole($role_guru);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_3,
            'email' => fake()->email(),
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_guru->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // user 4 as guru 3
        $user_4 = User::insertGetId([
            'username' => 'guru3',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_guru->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_4 = User::find($user_4);
        $user_d_4->assignRole($role_guru);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_4,
            'email' => fake()->email(),
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_guru->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // user 5 as guru 4
        $user_5 = User::insertGetId([
            'username' => 'guru4',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_guru->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_5 = User::find($user_5);
        $user_d_5->assignRole($role_guru);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_5,
            'email' => fake()->email(),
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_guru->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // user 6 as guru 5
        $user_6 = User::insertGetId([
            'username' => 'guru5',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_guru->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_6 = User::find($user_6);
        $user_d_6->assignRole($role_guru);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_6,
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_guru->id,
            'email' => fake()->email(),
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // treasurer
        $user_tr = User::insertGetId([
            'username' => 'bendahara',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_treasurer->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_tr = User::find($user_tr);
        $user_d_tr->assignRole($role_treasurer);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_tr,
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_treasurer->id,
            'email' => fake()->email(),
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $user_tr_1 = User::insertGetId([
            'username' => 'bendahara1',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_treasurer->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_d_tr_1 = User::find($user_tr_1);
        $user_d_tr_1->assignRole($role_treasurer);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_tr_1,
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_treasurer->id,
            'email' => fake()->email(),
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Kepala Sekolah
        $user_k = User::insertGetId([
            'username' => 'kepalasekolah',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_kepala->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_k_1 = User::find($user_k);
        $user_k_1->assignRole($role_kepala);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_k,
            'email' => fake()->email(),
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_kepala->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Kepala yayasan
        $user_ky = User::insertGetId([
            'username' => 'kepalayayasan',
            'password' => Hash::make('admin'),
            'email' => fake()->email(),
            'role' => $role_kepala_yayasan->id,
            'user_type' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user_ky_1 = User::find($user_ky);
        $user_ky_1->assignRole($role_kepala_yayasan);
        Employees::insert([
            'name' => fake()->name(),
            'user_id' => $user_ky,
            'email' => fake()->email(),
            'nip' => 99388383822,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'account_number' => fake()->numberBetween(1000000,9999999),
            'institution_id' => 1,
            'position_id' => $pos_kepala_yayasan->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
