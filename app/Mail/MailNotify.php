<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $summary;
    public $cabang;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($summary, $cabang, $type)
    {
        $this->summary = $summary;
        $this->cabang = $cabang;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@aplikasi-pcms.my.id')
                    ->view('mail.notification_user')
                    ->with([
                        'summary'   => $this->summary,
                        'type'      => $this->type,
                    ]);
    }
}
