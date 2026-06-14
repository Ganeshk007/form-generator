<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('form_template_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_template_id');
            $table->string('type');
            $table->string('name');
            $table->longText('stub');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_template_fields');
    }
};
