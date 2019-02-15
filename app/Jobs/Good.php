<?php

namespace App\Jobs;

use App\Models\Goods;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Good implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $goods;
        /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($goods)
    {
        $this->goods = $goods;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Goods::where('good_id','=',$this->goods['id'])->increment('good_price');
    }
}
