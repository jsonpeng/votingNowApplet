<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Repositories\ParticipantRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ParticipantController extends AppBaseController
{
    /** @var  ParticipantRepository */
    private $participantRepository;

    public function __construct(ParticipantRepository $participantRepo)
    {
        $this->participantRepository = $participantRepo;
    }

    /**
     * 退出操作
     * @param Request $request [description]
     * @param [type]  $user_id [description]
     */
    public function setLogoutAction(Request $request,$user_id)
    {
        app('common')->setLogout($user_id);
        Flash::success('操作成功');
        return redirect(route('participants.index'));
    }

    /**
     * Display a listing of the Participant.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->participantRepository->pushCriteria(new RequestCriteria($request));
        $participants = $this->participantRepository->repoPaginate();

        return view('participants.index')
            ->with('participants', $participants);
    }

    /**
     * Show the form for creating a new Participant.
     *
     * @return Response
     */
    public function create()
    {
        return view('participants.create');
    }

    /**
     * Store a newly created Participant in storage.
     *
     * @param CreateParticipantRequest $request
     *
     * @return Response
     */
    public function store(CreateParticipantRequest $request)
    {
        $input = $request->all();

        $participant = $this->participantRepository->model()::create($input);

        Flash::success('添加成功.');

        return redirect(route('participants.index'));
    }

    /**
     * Display the specified Participant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $participant = $this->participantRepository->model()::find($id);

        if (empty($participant)) {
            Flash::error('Participant not found');

            return redirect(route('participants.index'));
        }

        return view('participants.show')->with('participant', $participant);
    }

    /**
     * Show the form for editing the specified Participant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $participant = $this->participantRepository->model()::find($id);

        if (empty($participant)) {
            Flash::error('Participant not found');

            return redirect(route('participants.index'));
        }

        return view('participants.edit')->with('participant', $participant);
    }

    /**
     * Update the specified Participant in storage.
     *
     * @param  int              $id
     * @param UpdateParticipantRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateParticipantRequest $request)
    {
        $participant = $this->participantRepository->model()::find($id);

        if (empty($participant)) {
            Flash::error('Participant not found');

            return redirect(route('participants.index'));
        }

        $participant = $this->participantRepository->update($request->all(), $id);

        Flash::success('更新成功.');

        return redirect(route('participants.index'));
    }

    /**
     * Remove the specified Participant from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $participant = $this->participantRepository->model()::find($id);

        if (empty($participant)) {
            Flash::error('Participant not found');

            return redirect(route('participants.index'));
        }

        $this->participantRepository->delete($id);

        \App\Models\VoteLog::where('user_id',$id)->delete();

        Flash::success('删除成功.');

        return redirect(route('participants.index'));
    }
}
