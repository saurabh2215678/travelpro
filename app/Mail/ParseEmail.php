<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\CustomHelper;

class ParseEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $from_email;
    public $from_name;
    public $subject;
    public $email_data;
    public $mail_view_file;
    public $filename;
    public $message;
    public $attachments_files;
    public function __construct($from_email, $email_data, $subject, $filename='', $file_content="",$from_name="", $attachments_files=[])
    {
        $this->from_email = $from_email;
        $this->from_name = $from_name;
        $this->email_data = $email_data;
        $this->subject = $subject;
        $this->mail_view_file = 'emails.default';
        $this->message = $file_content;
        $this->filename = $filename;
        $this->attachments_files = $attachments_files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->filename) {
            $response = $this->from($this->from_email, $this->from_name)->subject($this->subject)->markdown($this->mail_view_file)->attachData($this->message,$this->filename,['mime'=>'text/plain']);
        } else {
            $response = $this->from($this->from_email, $this->from_name)->subject($this->subject)->markdown($this->mail_view_file);
        }

        if (count($this->attachments_files) > 0) {
            foreach ($this->attachments_files as $key => $attachments_file) {
                $response->attach($attachments_file, ['as' => $key]);
            }
        }

        return $response;
    }
}