<?php

namespace App\Providers;
use App\Helpers\CustomHelper;
use Config;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $userName = CustomHelper::getSMTPDetails('mail_username');
        $passWord = CustomHelper::getSMTPDetails('mail_password');
        $mailHost = CustomHelper::getSMTPDetails('mail_host');
        $mailPort = CustomHelper::getSMTPDetails('mail_port');
        $mailEncryption = CustomHelper::getSMTPDetails('mail_encryption');
        $fromName = CustomHelper::getSMTPDetails('from_name');
        $fromMail = CustomHelper::getSMTPDetails('from_mail');

        // Config::set(['mail.username'=>$userName, 'mail.password'=>$passWord]);
        // Config::set('mail.username',$userName);
        // Config::set('mail.password',$passWord);

        Config::set('mail.mailers.smtp.username',isset($userName) && !empty($userName) ? $userName : '');
        Config::set('mail.mailers.smtp.password',isset($passWord) && !empty($passWord) ? decrypt($passWord) : '');
        Config::set('mail.mailers.smtp.host',isset($mailHost) && !empty($mailHost) ? $mailHost : '');
        Config::set('mail.mailers.smtp.port',isset($mailPort) && !empty($mailPort) ? $mailPort : '');
        Config::set('mail.mailers.smtp.encryption',isset($mailEncryption) && !empty($mailEncryption) ? $mailEncryption : '');
        Config::set('mail.from.name',isset($fromName) && !empty($fromName) ? $fromName : '');
        Config::set('mail.from.address',isset($fromMail) && !empty($fromMail) ? $fromMail : '');
    }
}
