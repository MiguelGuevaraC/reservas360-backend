<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('description')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('photo')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('stock')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('price')->nullable(); // Tipo de documento, puede ser nulo

            $table->string('status')->default('Activo'); // Estado
            $table->string('server_id')->nullable(); // id del servidor
            $table->foreignId('category_id')->nullable()->unsigned()
                ->constrained('categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
