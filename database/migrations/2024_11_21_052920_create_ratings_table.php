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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('member_id'); // Tipe string untuk mencocokkan tabel member
            $table->unsignedBigInteger('biblio_id'); // Tipe unsignedBigInteger untuk mencocokkan tabel biblio
            $table->tinyInteger('rating_value')->unsigned(); // Nilai rating (1-5)
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
        Schema::dropIfExists('ratings');
    }
};
