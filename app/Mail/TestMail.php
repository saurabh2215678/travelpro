<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $from_name;
    public $email_content_data;
    public $subject;
    public $sender_email;
    public $attachments_files;
    public $reply_to;

    public function __construct($from_name ,$email_content_data, $subject, $sender_email, $attachments_files, $reply_to)
    {
        $this->from_name = $from_name;
        $this->subject = $subject;
        $this->sender_email = $sender_email;
        $this->email_content_data = $email_content_data;
        $this->attachments_files = $attachments_files;
        $this->reply_to = $reply_to;
    }

    public function build()
    {
        $email = $this->sender_email;

        $response = $this->from($email, $this->from_name)
            ->subject($this->subject)
            ->replyTo($this->reply_to)
            ->markdown('emails.common_mail');

        if (count($this->attachments_files) > 0) {
            foreach ($this->attachments_files as $key => $attachments_file) {
                $response->attach($attachments_file, ['as' => $key]);
            }
        }

        return $response;
    }

}