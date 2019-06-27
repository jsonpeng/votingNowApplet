<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VoteLog
 * @package App\Models
 * @version February 20, 2019, 4:37 pm CST
 *
 * @property integer user_id
 * @property integer award_id
 * @property integer candidate_id
 */
class VoteLog extends Model
{
    use SoftDeletes;

    public $table = 'vote_logs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'award_id',
        'candidate_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'award_id' => 'integer',
        'candidate_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function award()
    {
        return $this->belongsTo('App\Models\Award');
    }

    public function candidate()
    {
        return $this->belongsTo('App\Models\Candidate');
    }

    public function getUserNameAttribute()
    {
        return optional($this->user()->first())->name;
    }

     public function getAwardNameAttribute()
    {
        return optional($this->award()->first())->name;
    }

     public function getCandidateNameAttribute()
    {
        return optional($this->candidate()->first())->name;
    }

    
}
