<?php

namespace App\Jobs;

use App\Mail\NoticeReturnBooks;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NoticeReturnBooksQueueJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Dữ liệu gửi đi
     */
    private mixed $data;
    /**
     * Create a new job instance.
     */
    public function __construct(mixed $data) {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $noticeReturnBooks = new NoticeReturnBooks($this->data);
        Mail::to($this->data['email'])->send($noticeReturnBooks);
    }
}
