<?php

namespace App\Repositories;

use App\Models\AwardCandidate;
use App\Models\Award;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AwardCandidateRepository
 * @package App\Repositories
 * @version February 20, 2019, 4:31 pm CST
 *
 * @method AwardCandidate findWithoutFail($id, $columns = ['*'])
 * @method AwardCandidate find($id, $columns = ['*'])
 * @method AwardCandidate first($columns = ['*'])
*/
class AwardCandidateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'award_id',
        'candidate_id',
        'num'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AwardCandidate::class;
    }

    public function getNowAward()
    {
        $award = Award::orderBy('sort','desc')->where('show',1)->first();

        if(empty($award))
        {
           return '投票未开始';
        }

        $candidates = $this->getCandidates($award->id);

        return ['award'=>$award,'candidates'=>$candidates];

    }

    public function clearAward($award_id){
        return AwardCandidate::where('award_id',$award_id)->delete();
    }

    public function getCandidates($award_id,$return_id_arr = false)
    {
        $candidates = AwardCandidate::where('award_id',$award_id)->with('candidate')->get();

        foreach ($candidates as $key => $candidate) {
            $candidate['name'] = $candidate['candidate']->name;
        }
        if($return_id_arr)
        {
            $id_arr = [];
            foreach ($candidates as $key => $candidate) {
                $id_arr[] = $candidate->candidate_id;
            }
            $candidates = $id_arr;
        }
        return $candidates;
    }

    public function varifyInputIdArr($input)
    {
        $status = 1;
        if(isset($input['id_arr']))
        {
            if(!is_array($input['id_arr']))
            {
                $input['id_arr'] = explode(',',$input['id_arr']);
            }
            foreach ($input['id_arr'] as $key => $val) 
            {
                    if(is_null($val))
                    {
                        unset($input['id_arr'][$key]);
                    }
            }
            if(count($input['id_arr']) == 0)
            {
                $status = 0;
            }
        }
        return $status;
    }

    public function syncSave($award_id,$input,$action = 'create')
    {
        if($action == 'update')
        {
            $this->clearAward($award_id);
        }
        if(array_key_exists('id_arr',$input))
        {
            if(is_null($input['id_arr']))
            {
                return false;
            }
            if(!is_array($input['id_arr']))
            {
                $input['id_arr'] = explode(',',$input['id_arr']);
            }
            foreach ($input['id_arr'] as $key => $item) {
                if(isset($item) && !empty($item)){
                    AwardCandidate::create([
                        'award_id'      => $award_id,
                        'candidate_id'  => $item
                    ]);
                }
            }
            return true;
        }
        else{
            return false;
        }
    }


    public function getAwardCandidate($award_id,$candidate_id)
    {
        return AwardCandidate::where('award_id',$award_id)
        ->where('candidate_id',$candidate_id)
        ->first();
    }

    public function updateNum($award_id,$candidate_id,$input)
    {
        $log = $this->getAwardCandidate($award_id,$candidate_id);
        
        if(!empty($log))
        {
            if(!isset($input['num']))
            {
                $input['num'] = 0;
            }
            $log->update(['num'=>$input['num']]);
        }
    }
}
