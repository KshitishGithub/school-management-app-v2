<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SendOneSignalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $headings;
    protected $content;
    protected $bigPicture;
    protected $largeIcon;

    /**
     * Create a new job instance.
     */
    public function __construct($headings, $content, $bigPicture = null, $largeIcon = null)
    {
        $this->headings   = $headings;
        $this->content    = $content;
        $this->bigPicture = $bigPicture;
        $this->largeIcon  = $largeIcon;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $setting = Setting::first();

        if (!$setting) {
            Log::error('OneSignal settings not found.');
            return;
        }

        $apiKey = $setting->one_signal_api_key;
        $appId  = $setting->one_signal_app_id;

        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . $apiKey,
        ];

        $body = [
            'app_id'            => $appId,
            'included_segments' => ['ActiveUser'],
            'headings'          => ['en' => $this->headings],
            'contents'          => ['en' => $this->content],
            'big_picture'       => $this->bigPicture,
            'large_icon'        => $this->largeIcon,
            'small_icon'        => $this->largeIcon
        ];

        $response = Http::withHeaders($headers)
            ->post('https://onesignal.com/api/v1/notifications', $body);

        if ($response->failed()) {
            Log::error('OneSignal notification failed: ' . $response->body());
        }
    }
}
