<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mengubah format email Reset Password bawaan Laravel
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            return (new MailMessage)
                ->subject('Pulihkan Kata Sandi - SmartDo')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Kamu menerima email ini karena kami mendapat permintaan untuk mengatur ulang kata sandi akunmu di SmartDo.')
                ->action('Atur Ulang Kata Sandi', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('Tautan ini hanya berlaku selama 60 menit ke depan.')
                ->line('Jika kamu tidak pernah merasa meminta pengaturan ulang kata sandi, abaikan saja pesan ini. Akunmu tetap aman!')
                ->salutation('Salam hangat, Tim SmartDo 📝');
        });
    }
}