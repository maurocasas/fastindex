<?php

namespace App\Events\Pages;

use App\Models\Page;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Indexed
{
    use Dispatchable, SerializesModels;

    public function __construct(protected Page $page)
    {
        //
    }

    public function page(): Page
    {
        return $this->page;
    }
}
