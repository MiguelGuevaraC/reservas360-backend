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
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // Genera una columna 'id' autoincremental

            $table->string('ruc')->unique(); // Número de documento, debe ser único
            $table->string('name')->nullable(); // Nombres
           
            $table->string('address')->nullable(); // Dirección, puede ser nula
            $table->string('phone')->nullable(); // Teléfono, puede ser nulo
            $table->string('telephone')->nullable(); // Teléfono, puede ser nulo
            $table->string('email')->unique(); // Correo electrónico, debe ser único
            
            $table->string('status')->default('Activo'); // Estado
            $table->string('server_id')->nullable(); // id del servidor
            $table->timestamps(); // 'created_at' y 'updated_at'
            $table->softDeletes(); // Agrega el campo 'deleted_at' para el soft delete
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
    }
};
