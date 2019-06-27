<?php

namespace App\Repositories;

use App\Models\Award;
use InfyOm\Generator\Common\BaseRepository;
use Cache;

/**
 * Class AwardRepository
 * @package App\Repositories
 * @version February 20, 2019, 4:29 pm CST
 *
 * @method Award findWithoutFail($id, $columns = ['*'])
 * @method Award find($id, $columns = ['*'])
 * @method Award first($columns = ['*'])
*/
class AwardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Award::class;
    }

    public function updateStatus($award_id)
    {
        $award = $this->findWithoutFail($award_id);
        if(!empty($award))
        {
                if($award->status == '关闭')
                {
                    Award::where('id','<>',$award_id)->update(['status'=>'关闭','show'=>0]);
                    $award->update(['status'=>'开启','show'=>1]);
                    app('common')->setSignEndTime();
                }
                else{
                    $award->update(['status'=>'关闭','show'=>0]);
                    Cache::forget('sign_end_time');
                }
        }
    }


    public function updateShowStatus($id)
    {
        $award = $this->findWithoutFail($id);
        if(!empty($award))
        {
            if($award->show == 1)
            {
                $award->update(['show'=>0]);
            }
            else{
                  Award::orderBy('created_at','desc')->update(['show'=>0]);
                  $award->update(['show'=>1]);
            }
        }
    }

}
