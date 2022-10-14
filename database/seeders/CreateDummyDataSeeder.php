<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectApiSystem;

class CreateDummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     //php artisan db:seed --class=CreateDummyDataSeeder
    public function run()
    {
        ProjectApiSystem::create([
            'project_id' => 1,
            'name' => 'Xero DEV key',
            'description' => 'Xero api key for project',
            'api_key' => '3666D97845F149E6B558D16F1030BB70',
            'api_secret' => 's5NprJNRqgTpWryeAlqgQ_2EwBVYZXk46rFe-biDMDPfYsW2'
        ]);
    }
}
