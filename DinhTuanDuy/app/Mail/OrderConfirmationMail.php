<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        // Khởi tạo mail
        $mail = $this->view('emails.order_confirmation')
                     ->with(['order' => $this->order]);

        // Đính kèm hình ảnh sản phẩm
        foreach ($this->order->items as $item) {
            if ($item->product->image) {
                $path = public_path('storage/' . $item->product->image); // Đường dẫn đến hình ảnh
                $mail->attach($path, [
                    'as' => 'product-image.jpg', // Tên tệp đính kèm
                    'mime' => 'image/jpeg', // Định dạng tệp
                    'cid' => basename($item->product->image) // CID để hiển thị trong email
                ]);
            }
        }

        return $mail;
    }
}
