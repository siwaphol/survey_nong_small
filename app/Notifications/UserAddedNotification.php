<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $u, $p)
    {
        $this->user = $u;
        $this->password = $p;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = "http://survey-ee.ete.eng.cmu.ac.th";
        return (new MailMessage)
            ->subject('แจ้งข้อมูลการเข้าใช้ระบบ')
            ->greeting('เรียน '.$this->user->name)
            ->line('โครงการศึกษาศักยภาพอนุรักษ์พลังงานและพลังงานทดแทน พื้นที่ภาคเหนือ และพัฒนาระบบฐานข้อมูลศักยภาพอนุรักษ์พลังงาน ขอแจ้งข้อมูลสำหรับการเข้าใช้ระบบ ดังนี้')
            ->line('URL: '.$url)
            ->line('Username: '.$this->user->email)
            ->line('Password: '.$this->password)
            ->line('')
            ->action('เข้าสู่ระบบ', $url)
            ->line('')
            ->line('ท่าสามารถเข้าใช้งานและเปลี่ยนรหัสผ่านได้ที่เว็บไวต์ดังกล่าว');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
