<?php

namespace App\Jobs;

use File;
use Image;
use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImageUploadJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $channelOrUser;
    public $file_id;
    public $image_type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($channelOrUser, $file_id, $image_type) {
        $this->channelOrUser = $channelOrUser;
        $this->file_id = $file_id;
        $this->image_type = $image_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $file_path = storage_path('uploads/' . $this->file_id);
        $file_name = $this->file_id . '.png';

        // Image Intervention to resize the image
        Image::make($file_path)->encode('png')->fit(200, 200, function($constraints) {
            $constraints->upsize();
        })->save();

        if (Storage::disk('s3_images')->put($this->image_type . '/' . $file_name, fopen($file_path, 'r+'))) {
            File::delete($file_path);
            $this->channelOrUser->image_filename = $file_name;
            $this->channelOrUser->save();
        }
    }
}