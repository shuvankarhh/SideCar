<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     // php artisan db:seed --class=PermissionTableSeeder
    public function run()
    {
        $permissions = [
            'user_management_access',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete'
         ];
      
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
