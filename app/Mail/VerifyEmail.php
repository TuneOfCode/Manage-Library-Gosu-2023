<?php

namespace App\Mail;

use App\Constants\GlobalConstant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable {
    use Queueable, SerializesModels;
    /**
     * Thông tin người dùng muốn gửi mail
     */
    private string $FULL_NAME = "full_name";
    private string $EMAIL = "email";
    private string $ID = "id";
    // private string $TOKEN = "token";
    private string $LINK = "link";
    private string $OTP_EMAIL_CODE = "otp_email_code";
    private string $OTP_EMAIL_EXPIRED_AT = "otp_email_expired_at";
    public $data = [
        'full_name' => '',
        'email' => '',
        'id' => '',
        'link' => '',
        'otp_email_code' => '',
        'otp_email_expired_at' => '',
        // 'token' => '',
    ];
    /**
     * Create a new message instance.
     */
    public function __construct(mixed $user) {
        $this->data[$this->FULL_NAME] = $user[$this->FULL_NAME];
        $this->data[$this->EMAIL] = $user[$this->EMAIL];
        $this->data[$this->ID] = $user[$this->ID];
        $this->data[$this->LINK] = config('app.url') . ':' . config('app.port') . GlobalConstant::$BASE_RESEND_OTP_EMAIL;
        $this->data[$this->OTP_EMAIL_CODE] = $user[$this->OTP_EMAIL_CODE];
        $this->data[$this->OTP_EMAIL_EXPIRED_AT] = $user[$this->OTP_EMAIL_EXPIRED_AT];
        // $this->data[$this->TOKEN] = $user[$this->TOKEN];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        $fullName = $this->data[$this->FULL_NAME];
        return new Envelope(
            subject: "Xác thực email của thành viên $fullName",
        );
    }
    /**
     * Hiển thị ra view markdown
     */
    public function build() {
        return $this->markdown('email.verify', $this->data);
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
