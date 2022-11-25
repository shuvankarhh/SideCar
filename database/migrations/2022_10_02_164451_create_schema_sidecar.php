<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSchemaSidecar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");

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

        DB::statement("CREATE TABLE `addresses` (
                `Address_ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Database generated unique Address number',
                `Address1` VARCHAR(45) NULL DEFAULT NULL COMMENT 'Address line 1' COLLATE 'utf8_general_ci',
                `Address2` VARCHAR(45) NULL DEFAULT NULL COMMENT 'Address line 2' COLLATE 'utf8_general_ci',
                `City` VARCHAR(45) NULL DEFAULT NULL COMMENT 'City' COLLATE 'utf8_general_ci',
                `State` VARCHAR(45) NULL DEFAULT NULL COMMENT 'State' COLLATE 'utf8_general_ci',
                `ZIP` VARCHAR(15) NULL DEFAULT NULL COMMENT 'Postal Code' COLLATE 'utf8_general_ci',
                `Country` VARCHAR(45) NULL DEFAULT NULL COMMENT 'Country' COLLATE 'utf8_general_ci',
                `Phone` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Phone' COLLATE 'utf8_general_ci',
                `Fax` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Fax' COLLATE 'utf8_general_ci',
                PRIMARY KEY (`Address_ID`) USING BTREE,
                UNIQUE INDEX `Address_ID_UNIQUE` (`Address_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;"
        );

        DB::statement("CREATE TABLE `clients` (
                `Client_ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Database generated unique Client number',
                `Client_Number` SMALLINT(6) NOT NULL,
                `Client_Type` CHAR(1) NOT NULL COMMENT 'Client type' COLLATE 'utf8_general_ci',
                `Client_Status` TINYINT(1) NOT NULL DEFAULT '0',
                `Company_ID` INT(11) NOT NULL DEFAULT '0' COMMENT 'Company ID from Companies table',
                `Client_Approval_Amount_1` DECIMAL(8,2) NULL DEFAULT NULL,
                `Client_Approval_Amount_2` DECIMAL(8,2) NULL DEFAULT NULL,
                `Client_Logo_Name` VARCHAR(30) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                `Client_Logo` LONGBLOB NULL DEFAULT NULL,
                PRIMARY KEY (`Client_ID`) USING BTREE,
                UNIQUE INDEX `Client_ID_UNIQUE` (`Client_ID`) USING BTREE,
                INDEX `ix_Clients_Client_Number` (`Client_Number`) USING BTREE,
                INDEX `ix_Clients_Client_Type` (`Client_Type`) USING BTREE,
                INDEX `fk_Comapny_ID_idx` (`Company_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `companies` (
                `Company_ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Database generated unique Company number',
                `Company_Name` VARCHAR(80) NOT NULL COMMENT 'Company name' COLLATE 'utf8_general_ci',
                `Company_Fed_ID` VARCHAR(11) NOT NULL COLLATE 'utf8_general_ci',
                `SSN` VARCHAR(11) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                `Email` VARCHAR(80) NULL DEFAULT NULL COMMENT 'Default company email address' COLLATE 'utf8_general_ci',
                `Business_NameW9` VARCHAR(90) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                `Auth_Code` VARCHAR(8) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                `Auth_Url` VARCHAR(8) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                `Temp_Fed_ID_Flag` VARCHAR(1) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                PRIMARY KEY (`Company_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `company_addresses` (
                `Company_ID` INT(11) NOT NULL COMMENT 'Company ID from Companies table',
                `Address_ID` INT(11) NOT NULL COMMENT 'Address ID from Addresses table',
                PRIMARY KEY (`Company_ID`, `Address_ID`) USING BTREE,
                INDEX `fk_Address_ID_idx` (`Address_ID`) USING BTREE,
                INDEX `fk_Company_ID_idx` (`Company_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `persons` (
                `Person_ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Database generated unique Person number',
                `First_Name` VARCHAR(45) NOT NULL COMMENT 'First Name' COLLATE 'utf8_general_ci',
                `Last_Name` VARCHAR(45) NOT NULL COMMENT 'Last Name' COLLATE 'utf8_general_ci',
                `Email` VARCHAR(80) NOT NULL COMMENT 'Email address' COLLATE 'utf8_general_ci',
                `Mobile_Phone` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Person mobile phone' COLLATE 'utf8_general_ci',
                `Direct_Phone` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Direct line phone' COLLATE 'utf8_general_ci',
                `Direct_Fax` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Direct line fax' COLLATE 'utf8_general_ci',
                PRIMARY KEY (`Person_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `person_addresses` (
                `Person_ID` INT(11) NOT NULL,
                `Address_ID` INT(11) NOT NULL,
                PRIMARY KEY (`Person_ID`, `Address_ID`) USING BTREE,
                INDEX `Address_ID` (`Address_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `projects` (
                `Project_ID` INT(11) NOT NULL AUTO_INCREMENT,
                `Client_ID` INT(11) NOT NULL,
                `Project_Name` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
                `Project_Description` VARCHAR(125) NOT NULL COLLATE 'utf8_general_ci',
                `Project_Prod_Number` VARCHAR(30) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                `PO_Starting_Number` INT(11) NOT NULL DEFAULT '1000',
                `Ck_Req_Starting_Numb` INT(11) NOT NULL DEFAULT '1000',
                `COA_Manual_Coding` TINYINT(1) NOT NULL DEFAULT '1',
                `COA_Break_Character` VARCHAR(1) NOT NULL DEFAULT '/' COLLATE 'utf8_general_ci',
                `COA_Lookup` VARCHAR(25) NOT NULL DEFAULT '3' COLLATE 'utf8_general_ci',
                `COA_Break_Number` TINYINT(4) NOT NULL DEFAULT '0',
                `PJ_Flag_For_Deletion` TINYINT(1) NULL DEFAULT '0',
                `Deletion_Date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `Deletion_Complete` TINYINT(1) NULL DEFAULT '0',
                `PJ_Deletion_Requestor` INT(11) NULL DEFAULT '0',
                `PJ_Deletion_Name` VARCHAR(255) NULL DEFAULT '' COLLATE 'utf8_general_ci',
                PRIMARY KEY (`Project_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `project_api_systems` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `project_id` INT(11) NOT NULL,
                `client_id` INT(11) NOT NULL,
                `tanent_id` VARCHAR(82) NULL DEFAULT NULL,
                `name` VARCHAR(26) NOT NULL,
                `description` VARCHAR(255) NULL DEFAULT NULL,
                `software` VARCHAR(10) NULL DEFAULT NULL,
                `api_key` TEXT NOT NULL,
	            `api_secret` TEXT NOT NULL,
                `access_details` LONGTEXT NULL DEFAULT NULL,
                `created_at` TIMESTAMP NULL DEFAULT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `api_access_tokens` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `project_api_system_id` INT(11) NOT NULL,
            `details` LONGTEXT NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `users_client_list` (
                `User_ID` INT(11) NOT NULL,
                `Client_ID` INT(11) NOT NULL,
                `User_Type` VARCHAR(50) NOT NULL DEFAULT 'User' COLLATE 'utf8_general_ci',
                `User_Approval_Value` INT(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`User_ID`, `Client_ID`) USING BTREE,
                INDEX `Client_ID` (`Client_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `users_project_list` (
                `User_ID` INT(11) NOT NULL,
                `Project_ID` INT(11) NOT NULL,
                `Client_ID` INT(11) NOT NULL,
                PRIMARY KEY (`User_ID`, `Project_ID`) USING BTREE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ;");


        DB::statement("CREATE TABLE `chart_of_accounts` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `project_api_system_id` INT(11) NOT NULL,
            `account_id` VARCHAR(64) NULL DEFAULT NULL,
            `code` VARCHAR(24) NULL DEFAULT NULL,
            `name` VARCHAR(64) NULL DEFAULT NULL,
            `type` VARCHAR(24) NULL DEFAULT NULL,
            `tax_type` VARCHAR(24) NULL DEFAULT NULL,
            `currency_code` VARCHAR(4) NULL DEFAULT NULL,
            `status` VARCHAR(10) NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;");


        DB::statement("CREATE TABLE `tracking_categories` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `project_api_system_id` INT(11) NOT NULL,
            `tracking_category_id` VARCHAR(64) NULL DEFAULT NULL,
            `name` VARCHAR(64) NULL DEFAULT NULL,
            `status` VARCHAR(10) NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;");

        DB::statement("CREATE TABLE `tracking_options` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `tracking_category_id` INT(11) NOT NULL,
            `tracking_option_id` VARCHAR(64) NULL DEFAULT NULL,
            `name` VARCHAR(64) NULL DEFAULT NULL,
            `status` VARCHAR(10) NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;");



        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('company_addresses');
        Schema::dropIfExists('persons');
        Schema::dropIfExists('person_addresses');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users_client_list');
        Schema::dropIfExists('users_project_list');
        Schema::dropIfExists('project_api_systems');
        Schema::dropIfExists('api_access_tokens');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('tracking_categories');
        Schema::dropIfExists('tracking_options');
    }
}
