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
        Schema::create('people', function (Blueprint $table) {
            $table->id(); // Genera una columna 'id' autoincremental
            $table->string('typeofDocument')->nullable(); // Tipo de documento, puede ser nulo
            $table->string('documentNumber')->unique(); // Número de documento, debe ser único
            $table->string('names')->nullable(); // Nombres
            $table->string('fatherSurname')->nullable(); // Apellido del padre
            $table->string('motherSurname')->nullable(); // Apellido de la madre
            $table->string('businessName')->nullable(); // Razón social o nombre del negocio, puede ser nulo
            $table->string('representativePersonDni')->nullable(); // DNI de la persona representante, puede ser nulo
            $table->string('representativePersonName')->nullable(); // Nombre de la persona representante, puede ser nulo
            $table->string('address')->nullable(); // Dirección, puede ser nula
            $table->string('phone')->nullable(); // Teléfono, puede ser nulo
            $table->string('email')->unique(); // Correo electrónico, debe ser único
            $table->string('origin')->nullable(); // Origen, puede ser nulo
            $table->string('ocupation')->nullable(); // Ocupación, puede ser nulo
            $table->enum('state', ['1', '0'])->default('0'); // Estado, por defecto 'active'
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
        Schema::dropIfExists('people');
    }
};