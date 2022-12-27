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
use App\Models\Invoices;
use Illuminate\Mail\Mailables\Address;

class InvoiceDeleted extends Mailable
{
    use Queueable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $client;
    public $invoice;
    public function __construct(User $user, Clients $client, Invoices $invoice)
    {
        //
        $this->user = $user;
        $this->client = $client;
        $this->invoice = $invoice;
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
            subject: 'LR Billing Dashboard Notification | Invoice Deleted',
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
        return $this->markdown('emails.invoices.deleted');
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
            view: 'emails.invoices.deleted',
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
