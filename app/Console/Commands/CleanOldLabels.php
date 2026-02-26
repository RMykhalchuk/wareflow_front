<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOldLabels extends Command
{
    protected $signature   = 'labels:clean';
    protected $description = 'Delete generated label PDFs older than 1 hour';

    public function handle(): void
    {
        $files = Storage::disk('public')->files('labels');

        $deleted = 0;
        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);

            if (now()->timestamp - $lastModified > 3600) {
                Storage::disk('public')->delete($file);
                $deleted++;
            }
        }

        $this->info("Deleted {$deleted} label file(s).");
    }
}
