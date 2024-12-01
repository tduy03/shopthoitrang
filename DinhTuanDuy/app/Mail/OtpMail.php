<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp; // Thêm thuộc tính để lưu mã OTP

    /**
     * Tạo một thể hiện mới của thông điệp.
     *
     * @param int $otp
     */
    public function __construct($otp)
    {
        $this->otp = $otp; // Gán mã OTP vào thuộc tính
    }

    /**
     * Lấy envelope của thông điệp.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mã OTP của bạn',
        );
    }

    /**
     * Lấy định nghĩa nội dung của thông điệp.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp', // Đường dẫn đến view email
            with: ['otp' => $this->otp], // Truyền mã OTP vào view
        );
    }

    /**
     * Lấy các tệp đính kèm cho thông điệp.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
