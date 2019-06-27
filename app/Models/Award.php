<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Award
 * @package App\Models
 * @version February 20, 2019, 4:29 pm CST
 *
 * @property string name
 */
class Award extends Model
{
    use SoftDeletes;

    public $table = 'awards';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name','sort','status','show'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    
    public function getStatusShowAttribute()
    {
        $span = '<span class="btn btn-success status_btn" data-id="'.$this->id.'">开启</span>';
        if($this->status == '关闭')
        {
               $span = '<span class="btn btn-danger status_btn" data-id="'.$this->id.'">关闭</span>';
        }
        return $span;
    }

    public function getShowStatusAttribute()
    {
        $span = '<span class="btn btn-success status_btn" data-id="'.$this->id.'">显示</span>';
        if($this->show == 0)
        {
               $span = '<span class="btn btn-danger status_btn" data-id="'.$this->id.'">不显示</span>';
        }
        return $span;
    }


    
}
