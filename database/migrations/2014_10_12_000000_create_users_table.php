<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE `users` (
                `User_ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Database generated unique User number',
                `Default_Project` INT(11) NOT NULL COMMENT 'Client ID from Clients table',
                `User_Login` VARCHAR(30) NOT NULL COMMENT 'User login name' COLLATE 'utf8_general_ci',
                `User_Pwd` VARBINARY(40) NOT NULL COMMENT 'User password',
                `Person_ID` INT(11) NOT NULL DEFAULT '0' COMMENT 'Person ID from Persons table',
                `User_Icon` BLOB NULL DEFAULT NULL COMMENT 'User defined Icon',
                `Active` INT(1) NOT NULL DEFAULT '0' COMMENT 'Is user active',
                `User_Type` VARCHAR(50) NOT NULL DEFAULT 'User' COMMENT 'User type' COLLATE 'utf8_general_ci',
                `Last_Login` DATETIME NULL DEFAULT NULL COMMENT 'Last login time stamp',
                `Last_IP` VARCHAR(15) NULL DEFAULT NULL COMMENT 'Last login IP address' COLLATE 'utf8_general_ci',
                `Invited_by_UID` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`User_ID`) USING BTREE,
                UNIQUE INDEX `User_Login_UNIQUE` (`User_ID`) USING BTREE,
                UNIQUE INDEX `User_name_UNIQUE` (`User_Login`) USING BTREE,
                INDEX `fk_Person_ID_idx` (`Person_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
