<?php

namespace App\Repositories;

use App\User;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ParticipantRepository
 * @package App\Repositories
 * @version February 20, 2019, 4:47 pm CST
 *
 * @method Participant findWithoutFail($id, $columns = ['*'])
 * @method Participant find($id, $columns = ['*'])
 * @method Participant first($columns = ['*'])
*/
class ParticipantRepository extends BaseRepository
{
    use \RepoTrait;
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
        return User::class;
    }
}
