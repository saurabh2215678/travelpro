<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\CustomHelper;
use App\Models\MailErrorLog;
use App\Mail\ParseEmail;
use Mail;

class SendEmailJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $cc;
    protected $bcc;
    protected $reply_to;
    protected $mailer;
    protected $from;
    protected $data;
    protected $subject;
    protected $filename;
    protected $file_content;
    protected $from_name;
    protected $attachments;
    protected $module_name;
    protected $record_id;
    protected $added_by;
    protected $IP;
    protected $mail_authenticate;

    public function __construct($to, $cc, $bcc, $reply_to, $mailer, $from, $data, $subject, $filename, $file_content, $from_name, $attachments, $module_name, $record_id, $added_by, $IP, $mail_authenticate) {
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->reply_to = $reply_to;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->data = $data;
        $this->subject = $subject;
        $this->filename = $filename;
        $this->file_content = $file_content;
        $this->from_name = $from_name;
        $this->attachments = $attachments;
        $this->module_name = $module_name;
        $this->record_id = $record_id;
        $this->added_by = $added_by;
        $this->IP = $IP;
        $this->mail_authenticate = $mail_authenticate;
    }

    public function handle() {
        $to = $this->to;
        $cc = $this->cc;
        $bcc = $this->bcc;
        $reply_to = $this->reply_to;
        $mailer = $this->mailer;
        $from = $this->from;
        $data = $this->data;
        $subject = $this->subject;
        $filename = $this->filename;
        $file_content = $this->file_content;
        $from_name = $this->from_name;
        $attachments = $this->attachments;
        $module_name = $this->module_name;
        $record_id = $this->record_id;
        $added_by = $this->added_by;
        $IP = $this->IP;
        $mail_authenticate = $this->mail_authenticate;

        try {
            if (!empty($from) && !empty($to)) {
                $res = Mail::mailer($mailer);
                $res = $res->to($to);
                if (!empty($cc) && $to != $cc) {
                    if(!is_array($cc)) {
                        $cc = explode(",", $cc);
                    }
                    $res = $res->cc($cc);
                }
                if ($bcc) {
                    if(!is_array($bcc)) {
                        $bcc = explode(",", $bcc);
                    }
                    $res = $res->bcc($bcc);
                }
                if ($reply_to) {
                    // $res = $res->replyTo($reply_to);
                }

                if (isset($mail_authenticate) && $mail_authenticate == 'smtp') {
                    $res = $res->send(new ParseEmail($from, $data, $subject, $filename, $file_content, $from_name, $attachments));
                } elseif (isset($mail_authenticate) && $mail_authenticate == 'phpmail') {
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From:<" . $from . ">" . "\r\n";
                    $headers .= "Reply-To:" . $reply_to . "\r\n";

                    try {
                        mail($to, $subject, $data['email_content'], $headers);
                    } catch (\Throwable $th) {
                        $msg = $th->getMessage();

                        $MailErrorLog['msg_body'] = $data['email_content'];
                        $MailErrorLog['msg_subject'] = $subject;
                        $MailErrorLog['attachments'] = json_encode($attachments);
                        $MailErrorLog['msg_from'] = $from;

                        if (isset($cc) && is_array($cc)) {
                            $cc = implode(',', $cc);
                        }
                        $cc_email = isset($cc) && !empty($cc) ? ' , ' . $cc : '';
                        if (isset($bcc) && is_array($bcc)) {
                            $bcc = implode(',', $bcc);
                        }
                        $bcc_email = isset($bcc) && !empty($bcc) ? ' , ' . $bcc : '';
                        $MailErrorLog['msg_to'] = $to . $cc_email . $bcc_email;
                        $MailErrorLog['module_name'] = $module_name;
                        $MailErrorLog['record_id'] = $record_id;
                        $MailErrorLog['added_by'] = $added_by;
                        $MailErrorLog['ip'] = $IP;
                        $MailErrorLog['status'] = 'failed';
                        $MailErrorLog['error_message'] = $msg;
                        $MailErrorLog['type'] = $mail_authenticate;
                        MailErrorLog::create($MailErrorLog);
                        CustomHelper::captureSentryMessage('SendEmailJob handle phpmail error='.$msg);
                    }
                }
            } else {
                $msg = 'From or To is missing!!';
            }
        } catch (\Throwable $th) {
            $msg = $th->getMessage();

            $MailErrorLog['msg_body'] = $data['email_content'];
            $MailErrorLog['msg_subject'] = $subject;
            $MailErrorLog['attachments'] = json_encode($attachments);
            $MailErrorLog['msg_from'] = $from;

            if (isset($cc) && is_array($cc)) {
                $cc = implode(',', $cc);
            }
            $cc_email = isset($cc) && !empty($cc) ? ' , ' . $cc : '';
            if (isset($bcc) && is_array($bcc)) {
                $bcc = implode(',', $bcc);
            }
            $bcc_email = isset($bcc) && !empty($bcc) ? ' , ' . $bcc : '';
            $MailErrorLog['msg_to'] = $to . $cc_email . $bcc_email;

            $MailErrorLog['module_name'] = $module_name ?? '';
            $MailErrorLog['record_id'] = $record_id ?? 0;
            $MailErrorLog['added_by'] = $added_by;
            $MailErrorLog['ip'] = $IP;
            $MailErrorLog['status'] = 'failed';
            $MailErrorLog['error_message'] = $msg;
            $MailErrorLog['type'] = $mail_authenticate;
            MailErrorLog::create($MailErrorLog);
            CustomHelper::captureSentryMessage('SendEmailJob handle error='.$msg);
        }
    }

    public function failed($exception) {
        $msg = $exception->getMessage();
        CustomHelper::captureSentryMessage('SendEmailJob failed error='.$msg);
    }
}