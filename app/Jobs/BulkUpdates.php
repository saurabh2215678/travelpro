<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Accommodation;
use App\Models\Package;
use App\Models\CabRoute;
use App\Helpers\CustomHelper;

class BulkUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $params;

    public function __construct($params=[])
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $params = $this->params;
        $module_name = $params['module_name']??'';
        if($module_name) {
            if($module_name == 'hotel_listing') {
                $records = Accommodation::select('id')->where('status',1)->get();
                if($records) {
                    foreach($records as $record) {
                        CustomHelper::updateAccommodationPublishPrice($record->id);
                    }
                }
            } else if($module_name == 'package_listing') {
                $records = Package::select('id')->where('is_activity',0)->where('status',1)->get();
                if($records) {
                    foreach($records as $record) {
                        CustomHelper::updatePackagePublishPrice($record->id);
                    }
                }
            } else if($module_name == 'activity_listing') {
                $records = Package::select('id')->where('is_activity',1)->where('status',1)->get();
                if($records) {
                    foreach($records as $record) {
                        CustomHelper::updatePackagePublishPrice($record->id);
                    }
                }
            } else if($module_name == 'cab') {
                $records = CabRoute::select('id')->where('status',1)->get();
                if($records) {
                    foreach($records as $record) {
                        CustomHelper::updateCabRoutePublishPrice($record->id);
                    }
                }
            } else if($module_name == 'rental') {
                // $records = Package::select('id')->where('is_activity',1)->where('status',1)->get();
                // if($records) {
                //     foreach($records as $record) {
                //         CustomHelper::updatePackagePublishPrice($record->id);
                //     }
                // }
            }
        }
    }
}
