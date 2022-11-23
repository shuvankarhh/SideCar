<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectApiSystem;

use Illuminate\Support\Facades\DB;

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
        //`Client_ID`, `Client_Number`, `Client_Type`
        DB::statement("INSERT INTO `clients` VALUES (1,1,'1',1,1,NULL,NULL,NULL,NULL),(2,1,'1',0,2,NULL,NULL,NULL,NULL),(3,1,'1',0,3,NULL,NULL,NULL,NULL);");

        // `Company_ID` , `Company_Name`, `Company_Fed_ID`
        DB::statement("INSERT INTO `companies` VALUES 
            (1,'ASA AP','12-3456789','',NULL,NULL,NULL,NULL,NULL),
            (2,'I love 2, LLC','20-2999338','',NULL,NULL,'25y5tbkH',NULL,NULL),
            (3,'20th Century Props','95-4478033','',NULL,NULL,'8KZkKehh',NULL,NULL);");

        // `Project_ID`, `Client_ID` 
        DB::statement("INSERT INTO `projects` VALUES 
            (1,1,'Corporate','Description of the Project','100000',100000,1000,1,'/',0,0,'0000-00-00 00:00:00',0,0,''),
            (2,2,'Corporate','Description of the Project','100000',100000,1000,0,'/',1,0,'0000-00-00 00:00:00',0,0,''),
            (3,3,'Corporate','Description of the Project','100000',100000,1000,0,'/',1,0,'0000-00-00 00:00:00',0,0,'');
        ");


        ProjectApiSystem::create([
            "project_id" => 1,
            "client_id" => 1,
            "name" => "Demo App",
            "description" => "",
            "software" => "Xero",
            "api_key" => "3666D97845F149E6B558D16F1030BB70",
            "api_secret"=> "89apmCt7e-z_snmdrquiNvUtollIcYxEnEMpKQfgAkH7Msmq"
        ]);

        ProjectApiSystem::create([
            "project_id" => 2,
            "client_id" => 2,
            "name" => "Demo App",
            "description" => "",
            "software" => "Xero",
            "api_key" => "DECBE2A6A10343E58518A449A6F8BFC1",
            "api_secret"=> "LiYP-jdHPdf4yTTPpGlsxtAS01h3fUwHtr0hCfy0aqT8Vasd"
        ]);

        
    }
}
