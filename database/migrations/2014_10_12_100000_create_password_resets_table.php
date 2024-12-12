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
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');

            // Descripción detallada de la actividad
            $table->ipAddress('ip_address'); // Dirección IP del usuario
            $table->boolean('is_used')->default(0);
            $table->text('user_agent'); // Información sobre el navegador o dispositivo
            $table->text('full_url');
            $table->text('http_method');
            $table->foreignId('user_id')->nullable()->unsigned()->constrained('users');
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('password_resets');
    }
};
