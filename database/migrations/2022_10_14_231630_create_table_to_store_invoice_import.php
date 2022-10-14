<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTableToStoreInvoiceImport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_imports', function (Blueprint $table) {
            $table->id();
            $table->string('vendorid');
            $table->string('invnum')->nullable();
            $table->string('invamt')->nullable();
            $table->string('invdate')->nullable();
            $table->string('invdue')->nullable();
            $table->string('glcode')->nullable();
            $table->string('glamt')->nullable();
            $table->string('gldesc')->nullable();
            $table->string('filename');
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
        Schema::dropIfExists('invoice_imports');
    }
}
