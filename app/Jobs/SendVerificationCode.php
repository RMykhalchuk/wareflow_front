<?php

namespace App\Jobs;

use App\Traits\VerificationCodeTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendVerificationCode implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use VerificationCodeTrait;

    public array $request;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @return void
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->sendCode($this->request);
    }
}
