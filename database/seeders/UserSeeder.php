<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       if(User::count() == 0){
           User::create([
              'name' => 'Admin',
              'email' => 'admin@example.com',
              'password' => Hash::make('admin123'),
              // role dibatasi oleh enum: presidium, pimpinan, pengurus
              'role' => 'presidium',
              'jabatan' => 'Admin'
           ]);
           $this->command->info('User berhasil dibuat');
       }else{
           $this->command->info('User sudah ada');
       }

    }
}
