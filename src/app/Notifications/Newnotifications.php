<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Kudos;
use App\Comments;

class Newnotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $kudos,$comment;

    public function __construct(Kudos $kudos,Comments $comment)
    {
        $this->kudos = $kudos;
        $this->comment = $comment;
    }

    public function toDatabase($notifiable)
    {
        return [
            'thread'=>$this->thread,
            'user'=>auth()->user()
        ];
    }

    public function toBrodcast($notifiable)
    {
        return new BroadcastMessage([
            'thread'=>$this->thread,
            'user'=>auth()->user()
        ]);
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'kudos' => $this->kudos->id,
            'comments' => $this->comments->id
        ];
    }

}