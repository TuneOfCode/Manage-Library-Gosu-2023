<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable {
    use Queueable, SerializesModels;
    /**
     * Thông tin người bị quên mật khẩu
     */
    /**
     * Thông tin người dùng muốn gửi mail
     */
    private string $FULL_NAME = "full_name";
    private string $EMAIL = "email";
    private string $NEW_PASSWORD = "new_password";
    public $data = [
        "name" => "",
        "email" => "",
        "new_password" => ""
    ];
    /**
     * Create a new message instance.
     */
    public function __construct($user) {
        $this->data[$this->FULL_NAME] = $user[$this->FULL_NAME];
        $this->data[$this->EMAIL] = $user[$this->EMAIL];
        $this->data[$this->NEW_PASSWORD] = $user[$this->NEW_PASSWORD];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        $fullName = $this->data[$this->FULL_NAME];
        return new Envelope(
            subject: "Nhận mật khẩu mới giành cho thành viên $fullName",
        );
    }
    /**
     * Hiển thị ra view
     */
    public function build() {
        return $this->markdown('email.forgotPassword', $this->data);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        return [];
    }
}
