<?php

use App\Enums\RentBookStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('book_user', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->unsigned()->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->unsigned()->constrained('books')->onDelete('cascade');
            $table->integer('amount')->default(1);
            $table->decimal('payment', 10, 2)->default(0); // phí mượn sách nếu có
            $table->decimal('discount', 10, 2)->default(0); // tiền giảm giá nếu có
            $table->string('unit')->default('VND'); // đơn vị tiền tệ (VND, USD, EUR, ...)
            $table->decimal('extra_money', 10, 2)->default(0); // tiền phạt nếu trả sách quá hạn
            $table->enum('status', RentBookStatus::MAP_VALUE)->default(RentBookStatus::MAP_VALUE[RentBookStatus::PENDING()->value]);
            $table->dateTime('approved_at')->nullable(); // thời điểm phê duyệt yêu cầu mượn sách
            $table->dateTime('rejected_at')->nullable(); // thời điểm từ chối yêu cầu mượn sách
            $table->dateTime('canceled_at')->nullable(); // thời điểm hủy yêu cầu mượn sách
            $table->dateTime('paid_at')->nullable(); // thời điểm trả tiền sách (nếu có)
            $table->dateTime('borrowed_at')->nullable(); // thời điểm mượn sách
            $table->dateTime('estimated_returned_at')->nullable(); // thời điểm dự kiến trả sách
            $table->dateTime('returned_at')->nullable(); // thời điểm thực tế trả sách
            $table->dateTime('extra_money_at')->nullable(); // thời điểm trả tiền phụ phí sách (nếu có)
            $table->string('note')->nullable(); // ghi chú
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('book_user');
    }
};
