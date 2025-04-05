<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\GlucoseReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GlucoseReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(  
        protected $textPeriod,
        protected $filePath,
        protected $email
    ) {}


    /**
     * Execute the job.
     */
    public function handle(): void
    {       
        //$pdfContent = Storage::disk('local')->get($this->filePath);
        $pdfContent = file_get_contents($this->filePath);
        Mail::send(new GlucoseReportMail($pdfContent, $this->textPeriod, $this->email));
    }
}
