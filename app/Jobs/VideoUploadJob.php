<?php

namespace App\Jobs;

use File;
use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VideoUploadJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video_file_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($video_file_name) {
        $this->video_file_name = $video_file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $file_path = storage_path('uploads/' . $this->video_file_name);

        // upload to s3
        if (Storage::disk('s3_drop_videos')->put($this->video_file_name, fopen($file_path, 'r+'))) {
            File::delete($file_path);
        }
    }
}
