<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAwardCandidateRequest;
use App\Http\Requests\UpdateAwardCandidateRequest;
use App\Repositories\AwardCandidateRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AwardCandidateController extends AppBaseController
{
    /** @var  AwardCandidateRepository */
    private $awardCandidateRepository;

    public function __construct(AwardCandidateRepository $awardCandidateRepo)
    {
        $this->awardCandidateRepository = $awardCandidateRepo;
    }

    /**
     * Display a listing of the AwardCandidate.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->awardCandidateRepository->pushCriteria(new RequestCriteria($request));
        $awardCandidates = $this->awardCandidateRepository->all();

        return view('award_candidates.index')
            ->with('awardCandidates', $awardCandidates);
    }

    /**
     * Show the form for creating a new AwardCandidate.
     *
     * @return Response
     */
    public function create()
    {
        return view('award_candidates.create');
    }

    /**
     * Store a newly created AwardCandidate in storage.
     *
     * @param CreateAwardCandidateRequest $request
     *
     * @return Response
     */
    public function store(CreateAwardCandidateRequest $request)
    {
        $input = $request->all();

        $awardCandidate = $this->awardCandidateRepository->create($input);

        Flash::success('Award Candidate saved successfully.');

        return redirect(route('awardCandidates.index'));
    }

    /**
     * Display the specified AwardCandidate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $awardCandidate = $this->awardCandidateRepository->findWithoutFail($id);

        if (empty($awardCandidate)) {
            Flash::error('Award Candidate not found');

            return redirect(route('awardCandidates.index'));
        }

        return view('award_candidates.show')->with('awardCandidate', $awardCandidate);
    }

    /**
     * Show the form for editing the specified AwardCandidate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $awardCandidate = $this->awardCandidateRepository->findWithoutFail($id);

        if (empty($awardCandidate)) {
            Flash::error('Award Candidate not found');

            return redirect(route('awardCandidates.index'));
        }

        return view('award_candidates.edit')->with('awardCandidate', $awardCandidate);
    }

    /**
     * Update the specified AwardCandidate in storage.
     *
     * @param  int              $id
     * @param UpdateAwardCandidateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAwardCandidateRequest $request)
    {
        $awardCandidate = $this->awardCandidateRepository->findWithoutFail($id);

        if (empty($awardCandidate)) {
            Flash::error('Award Candidate not found');

            return redirect(route('awardCandidates.index'));
        }

        $awardCandidate = $this->awardCandidateRepository->update($request->all(), $id);

        Flash::success('Award Candidate updated successfully.');

        return redirect(route('awardCandidates.index'));
    }

    /**
     * Remove the specified AwardCandidate from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $awardCandidate = $this->awardCandidateRepository->findWithoutFail($id);

        if (empty($awardCandidate)) {
            Flash::error('Award Candidate not found');

            return redirect(route('awardCandidates.index'));
        }

        $this->awardCandidateRepository->delete($id);

        Flash::success('Award Candidate deleted successfully.');

        return redirect(route('awardCandidates.index'));
    }
}
