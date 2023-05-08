<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NoticeReturnBooks extends Mailable {
    use Queueable, SerializesModels;
    /**
     * Dữ liệu gửi đi
     */
    private mixed $data;
    /**
     * Create a new message instance.
     */
    public function __construct(mixed $data) {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        $full_name = $this->data['full_name'];
        return new Envelope(
            subject: "Thông báo về việc trả sách cho thành viên $full_name",
        );
    }

    /**
     * Hiển thị ra view
     */
    public function build() {
        return $this->markdown('email.noticeReturnBooks', $this->data);
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
