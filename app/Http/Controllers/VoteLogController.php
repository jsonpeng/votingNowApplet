<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVoteLogRequest;
use App\Http\Requests\UpdateVoteLogRequest;
use App\Repositories\VoteLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class VoteLogController extends AppBaseController
{
    /** @var  VoteLogRepository */
    private $voteLogRepository;

    public function __construct(VoteLogRepository $voteLogRepo)
    {
        $this->voteLogRepository = $voteLogRepo;
    }

    /**
     * Display a listing of the VoteLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->voteLogRepository->pushCriteria(new RequestCriteria($request));
        $voteLogs = $this->voteLogRepository->repoPaginate();

        return view('vote_logs.index')
            ->with('voteLogs', $voteLogs);
    }

    /**
     * Show the form for creating a new VoteLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('vote_logs.create');
    }

    /**
     * Store a newly created VoteLog in storage.
     *
     * @param CreateVoteLogRequest $request
     *
     * @return Response
     */
    public function store(CreateVoteLogRequest $request)
    {
        $input = $request->all();

        $voteLog = $this->voteLogRepository->create($input);

        Flash::success('Vote Log saved successfully.');

        return redirect(route('voteLogs.index'));
    }

    /**
     * Display the specified VoteLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $voteLog = $this->voteLogRepository->findWithoutFail($id);

        if (empty($voteLog)) {
            Flash::error('Vote Log not found');

            return redirect(route('voteLogs.index'));
        }

        return view('vote_logs.show')->with('voteLog', $voteLog);
    }

    /**
     * Show the form for editing the specified VoteLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $voteLog = $this->voteLogRepository->findWithoutFail($id);

        if (empty($voteLog)) {
            Flash::error('Vote Log not found');

            return redirect(route('voteLogs.index'));
        }

        return view('vote_logs.edit')->with('voteLog', $voteLog);
    }

    /**
     * Update the specified VoteLog in storage.
     *
     * @param  int              $id
     * @param UpdateVoteLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVoteLogRequest $request)
    {
        $voteLog = $this->voteLogRepository->findWithoutFail($id);

        if (empty($voteLog)) {
            Flash::error('Vote Log not found');

            return redirect(route('voteLogs.index'));
        }

        $voteLog = $this->voteLogRepository->update($request->all(), $id);

        Flash::success('Vote Log updated successfully.');

        return redirect(route('voteLogs.index'));
    }

    /**
     * Remove the specified VoteLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $voteLog = $this->voteLogRepository->findWithoutFail($id);

        if (empty($voteLog)) {
            Flash::error('Vote Log not found');

            return redirect(route('voteLogs.index'));
        }

        $this->voteLogRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('voteLogs.index'));
    }
}
