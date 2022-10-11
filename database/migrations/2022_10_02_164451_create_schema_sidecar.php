<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemaSidecar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('companies', function (Blueprint $table) {
            $table->id('Company_ID');
            $table->string('Company_Name', 80);
            $table->string('Company_Fed_ID', 11)->nullable();
            $table->string('SSN', 11)->nullable();
            $table->string('Email', 80)->nullable();
            $table->string('Business_NameW9', 90)->nullable();
            $table->string('Auth_Code', 8)->nullable();
            $table->string('Auth_Url', 8)->nullable();
            $table->tinyInteger('temp_fed_id_flag')->default(0);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id('Client_ID');
            $table->integer('Client_Number');
            $table->integer('Client_Type');
            $table->tinyInteger('Client_Status')->default(0);
            $table->integer('Company_ID');
            $table->decimal('Client_Approval_Amount_1', 8, 2)->nullable();
            $table->decimal('Client_Approval_Amount_2', 8, 2)->nullable();
            $table->string('Client_Logo_Name', 32);
            $table->longText('Client_Logo');
            $table->timestamps();
        });

        // its can be user_details
        Schema::create('persons', function (Blueprint $table) {
            $table->id('Person_ID');
            $table->string('First_Name', 45);
            $table->string('Last_Name', 45);
            $table->string('Email', 80);
            $table->string('Mobile_Phone', 30);
            $table->string('Direct_Phone', 30);
            $table->string('Direct_Fax', 30);
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id('Address_ID');
            $table->string('Address1', 42);
            $table->string('Address2', 42);
            $table->string('City', 42);
            $table->string('State', 42);
            $table->string('ZIP', 15);
            $table->string('Country', 45);
            $table->string('Phone', 32);
            $table->string('Fax', 32);
            $table->timestamps();
        });

        Schema::create('person_addresses', function (Blueprint $table) {
            $table->integer('Address_ID');
            $table->integer('Person_ID');
            //$table->unique(['Address_ID', 'Person_ID']);
            $table->timestamps();
        });

        Schema::create('company_addresses', function (Blueprint $table) {
            $table->integer('Address_ID');
            $table->integer('Company_ID');
            //$table->unique(['Address_ID', 'Company_ID']);
            $table->timestamps();
        });

        Schema::create('users_client_list', function (Blueprint $table) {
            $table->integer('User_ID');
            $table->integer('Client_ID');
            $table->string('User_Type',50);
            $table->integer('User_Approval_Value')->default(0);
            $table->unique(['user_id', 'client_id']);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id('Project_ID');
            $table->integer('Client_ID');
            $table->string('Project_Name', 25);
            $table->string('Project_Description', 125);
            $table->string('Project_Prod_Number',30);
            $table->integer('PO_Starting_Number')->default(1000);
            $table->integer('Ck_Req_Starting_Numb')->default(1000);
            $table->tinyInteger('COA_Manual_Coding')->default(1000);
            $table->string('COA_Break_Character',1)->default('/');
            $table->tinyInteger('COA_Break_Number')->default(0);
            $table->tinyInteger('PJ_Flag_For_Deletion')->default(0);
            $table->tinyInteger('Deletion_Complete')->default(0);
            $table->integer('PJ_Deletion_Requestor', 11)->default(0);
            $table->string('PJ_Deletion_Name', 164);
            $table->dateTime('Deletion_Date')->nullable();
            $table->timestamps();
        });

        Schema::create('users_project_list', function (Blueprint $table) {
            $table->integer('User_ID');
            $table->integer('Project_ID');
            $table->integer('Client_ID');
            $table->unique(['User_ID', 'Project_ID', 'Client_ID']);
            $table->timestamps();
        });

        Schema::create('project_api_systems', function (Blueprint $table) {
            $table->id();
            $table->integer('Project_ID');
            $table->string('name', 26);
            $table->string('description');
            $table->string('api_key');
            $table->string('api_secret');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('persons');
        Schema::dropIfExists('users_client_list');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('company_addresses');
        Schema::dropIfExists('person_addresses');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users_project_list');
        Schema::dropIfExists('project_api_systems');
    }
}
