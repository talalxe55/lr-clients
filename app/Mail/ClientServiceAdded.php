<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Clients;
use App\Models\Services;
use App\Models\ClientServices;
use Illuminate\Mail\Mailables\Address;

class ClientServiceAdded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $client;
    public $service;
    public $added_service;
    public function __construct(User $user, Clients $client, Services $service, ClientServices $added_service)
    {
        //
        $this->user = $user;
        $this->client = $client;
        $this->service = $service;
        $this->added_service = $added_service;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('info@lrblling.com', 'LR Billing'),
            subject: 'LR Billing Dashboard Notification | Client Service Added',
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        //return $this->view('view.name');
        return $this->markdown('emails.clients.service-added');
        //->with('body',$this->body);
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.clients.service-added',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
