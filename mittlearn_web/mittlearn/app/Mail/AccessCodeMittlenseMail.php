<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccessCodeMittlenseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $accessCode;

    public function __construct($accessCode)
    {
        $this->accessCode = $accessCode;
    }
    public function build()
    {
        return $this->subject('Your Exclusive License/ Access Code')
            ->html("
            <p><strong>Hello!</strong> 🎉</p>
            <p>You're just one step away from unlocking premium content. Use your exclusive access code:</p>
            <p style='font-size: 18px; font-weight: bold; color: #00438C;'>{$this->accessCode->licence_key}</p>
            <p>Enter this code on our platform and start exploring <strong style='font-size: 15px; font-weight: bold; color: #30C768;'>" .
                ($this->accessCode->type === 'mittlense' ? 'Mittsurelense' : 'Teachlite') .
                "</strong> now! 🚀</p>
            <p>If you have any questions, feel free to reach out. Happy learning! 📚</p>
            <p><strong>Best regards,</strong><br>The MittSure Team</p>
        ");

    }
    // public function build()
    // {
    //     return $this->subject('Your Access Code')
    //                 ->view('emails.access-code')
    //                 ->with(['accessCode' => $this->accessCode]);
    // }

}
