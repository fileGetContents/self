<?php

namespace App\Jobs;

use App\Models\Groups;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GroupCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public $tries = 1; // 最大尝试次数

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $id = Groups::insertGetId($this->data);
        $name = $this->data['group_user_id'] . 'group' . $this->data['group_good_id'] . 'has';
        Cache::put($name, $id, now()->addMinutes(20));
//        if (!$bool) {
//            Log::channel('group')->info(serialize($this->data) . '----------------添加失败');
//        }
        echo 'success' . $this->data['group_user_id'];
    }

    public function failed()
    {
        Log::channel('group')->info('队列运行失败');
    }


}

