<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     // php artisan db:seed --class=CreateAdminUserSeeder
    public function run()
    {
        $user = User::create([
            'name' => 'Harry', 
            'email' => 'test@test.com',
            'password' => bcrypt('123456'),
        ]);

        $user_two = User::create([
            'name' => 'Tim', 
            'email' => 'fullforce@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    
        $role = Role::create(['name' => 'admin']);
        $role_two = Role::create(['name' => 'user']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);
        $user_two->assignRole([$role->id]);
    }
}
