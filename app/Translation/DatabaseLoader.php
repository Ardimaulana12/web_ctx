<?php

namespace App\Translation;

use App\Services\WebsiteContent;
use Illuminate\Translation\FileLoader;

class DatabaseLoader extends FileLoader
{
    protected function loadJsonPaths($locale)
    {
        // Framework validation files remain file-based; all website copy comes from the DB.
        return app(WebsiteContent::class)->shared($locale)['translations'];
    }
}
