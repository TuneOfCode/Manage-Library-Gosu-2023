<?php

namespace App\Jobs;

use App\Mail\ForgotPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordQueueJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Email của người dùng
     */
    private string $email;
    /**
     * Dữ liệu gửi đi
     */
    private mixed $data;
    /**
     * Create a new job instance.
     */
    public function __construct(string $email, mixed $data) {
        $this->email = $email;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $forgotPassword = new ForgotPassword($this->data);
        Mail::to($this->email)->send($forgotPassword);
    }
}
