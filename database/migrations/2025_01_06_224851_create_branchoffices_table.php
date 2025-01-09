<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {
        Schema::create('branchoffices', function (Blueprint $table) {
            $table->id(); // Genera una columna 'id' autoincremental
            $table->string('brand_name')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('ruc')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('name')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('address')->nullable(); // Dirección, puede ser nula
            $table->string('phone')->nullable(); // Teléfono, puede ser nulo
            $table->string('telephone')->nullable(); // Teléfono, puede ser nulo
            $table->string('email')->unique(); // Correo electrónico, debe ser único
            
            $table->enum('state', ['1', '0'])->default('1'); // Estado
            $table->string('server_id')->nullable(); // id del servidor
            $table->foreignId('company_id')->nullable()->unsigned()->constrained('companies');
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
        Schema::dropIfExists('branchoffices');
    }
};
