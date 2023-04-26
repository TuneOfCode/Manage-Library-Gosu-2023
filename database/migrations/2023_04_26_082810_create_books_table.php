<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('image');
            $table->string('description')->nullable();
            $table->string('position'); // vị trí của sách trong thư viện
            $table->integer('quantity'); // số lượng tồn kho
            $table->integer('price'); // giá của cuốn sách
            $table->integer('loan_price'); // giá mượn sách nếu như là sách vip
            $table->string('author')->nullable();
            $table->string('published_at');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('books');
    }
};
