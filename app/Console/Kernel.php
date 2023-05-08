<?php

namespace App\Console;

use App\Constants\GlobalConstant;
use App\Jobs\NoticeReturnBooksQueueJob;
use App\Repositories\Book\BookRepository;
use App\Repositories\BookUser\BookUserRepository;
use App\Services\BookUser\BookUserService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel {
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $bookUserService = new BookUserService(
                new BookUserRepository(),
                new BookRepository()
            );

            // xử lý tăng tiền phụ phí theo từng ngày
            $bookUserService::increaseExtraMoney();

            $formatDatetime = 'Y-m-d H:i';
            $borrowingBooks = DB::table('book_user')
                ->where('status', '=', 'borrowing')
                ->get();
            if (count($borrowingBooks) === 0) {
                return;
            }

            $emails = [];
            foreach ($borrowingBooks as $borrowingBook) {
                $estimated_returned_at = Carbon::createFromFormat(
                    GlobalConstant::$FORMAT_DATETIME_DB,
                    $borrowingBook->estimated_returned_at
                )->subDays()->format($formatDatetime);
                $afterNowOneDay = Carbon::createFromFormat(
                    GlobalConstant::$FORMAT_DATETIME_DB,
                    now()
                )->format($formatDatetime);

                if ($estimated_returned_at !== $afterNowOneDay) {
                    continue;
                }
                $user = DB::table('users')
                    ->where('id', '=', $borrowingBook->user_id)->first();
                $book = DB::table('books')
                    ->where('id', '=', $borrowingBook->book_id)->first();

                $borrowedAtFormat = date(
                    'd/m/Y H:i:s',
                    strtotime($borrowingBook->borrowed_at)
                );
                $estimatedReturnedAtFormat = date(
                    'd/m/Y H:i:s',
                    strtotime($borrowingBook->estimated_returned_at)
                );
                $book = [
                    'name' => $book->name,
                    'amount' => $borrowingBook->amount,
                    'payment' => $borrowingBook->payment,
                    'unit' => $borrowingBook->unit,
                    'borrowed_at' =>  $borrowedAtFormat,
                    'estimated_returned_at' => $estimatedReturnedAtFormat,
                ];

                // kiểm tra email này đã được gửi hay chưa
                if (in_array($user->email, $emails)) {
                    continue;
                }

                $data = [
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'books' => [
                        $book
                    ],
                    'estimated_returned_at' => $estimatedReturnedAtFormat,
                ];

                // gửi email
                NoticeReturnBooksQueueJob::dispatch($data);
                $emails[] = $user->email;
            }

            // xử lý khi có sách không trả
            $bookUserService::checkNotReturnBooks();

            // xử lý khi có sách bị quá hạn trả
            $bookUserService::checkOverdueBooks();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
