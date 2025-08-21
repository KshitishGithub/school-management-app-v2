<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $registration_ids;
    protected $title;
    protected $body;
    protected $image;
    /**
     * Create a new job instance.
     */
    public function __construct($registration_ids, $title, $body, $image = null)
    {
        //
        $this->registration_ids = $registration_ids;
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // call the function from helper
        
        FirebasePushNotification(
            $this->registration_ids,
            $this->title,
            $this->body,
            $this->image
        );
    }
}
