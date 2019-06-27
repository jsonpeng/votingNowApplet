<?php

namespace App\Repositories;

use App\Models\Candidate;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CandidateRepository
 * @package App\Repositories
 * @version February 20, 2019, 4:27 pm CST
 *
 * @method Candidate findWithoutFail($id, $columns = ['*'])
 * @method Candidate find($id, $columns = ['*'])
 * @method Candidate first($columns = ['*'])
*/
class CandidateRepository extends BaseRepository
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
        return Candidate::class;
    }
}
