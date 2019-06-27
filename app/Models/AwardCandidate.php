<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AwardCandidate
 * @package App\Models
 * @version February 20, 2019, 4:31 pm CST
 *
 * @property integer award_id
 * @property integer candidate_id
 * @property integer num
 */
class AwardCandidate extends Model
{
    use SoftDeletes;

    public $table = 'award_candidates';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'award_id',
        'candidate_id',
        'num'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'award_id' => 'integer',
        'candidate_id' => 'integer',
        'num' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


    public function candidate()
    {
        return $this->belongsTo('App\Models\Candidate');
    }

    public function getCandidateNameAttribute()
    {
        return optional($this->candidate()->first())->name;
    }

    
}
