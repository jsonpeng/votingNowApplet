<?php

namespace App\Repositories;

use App\Models\VoteLog;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class VoteLogRepository
 * @package App\Repositories
 * @version February 20, 2019, 4:37 pm CST
 *
 * @method VoteLog findWithoutFail($id, $columns = ['*'])
 * @method VoteLog find($id, $columns = ['*'])
 * @method VoteLog first($columns = ['*'])
*/
class VoteLogRepository extends BaseRepository
{
    use \RepoTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'award_id',
        'candidate_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return VoteLog::class;
    }
}
