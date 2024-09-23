<?php

namespace App\Notifications\Sitemap;

use App\Models\Sitemap;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UnreachableSitemap extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Sitemap $sitemap)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'url' => route('sites.show', $this->sitemap->site) . '?tab=Sitemaps',
            'message' => "We cannot reach sitemap {$this->sitemap->path}, please review your settings"
        ];
    }
}
