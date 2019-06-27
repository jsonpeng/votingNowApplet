<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAwardRequest;
use App\Http\Requests\UpdateAwardRequest;
use App\Repositories\AwardRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AwardController extends AppBaseController
{
    /** @var  AwardRepository */
    private $awardRepository;

    public function __construct(AwardRepository $awardRepo)
    {
        $this->awardRepository = $awardRepo;
    }

    /**
     * Display a listing of the Award.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->awardRepository->pushCriteria(new RequestCriteria($request));
        $awards = $this->awardRepository->all();

        return view('awards.index')
            ->with('awards', $awards);
    }


    private function varifyCandidateAdded()
    {
        $candidates = app('common')->CandidateRepo()->all();
        return $candidates;
    }

    //更新奖项投票状态
    public function updateAwardStatus($id,Request $request)
    {
        $this->awardRepository->updateStatus($id);
        Flash::success('操作成功.');
        return redirect(route('awards.index'));
    }

    //更新奖项显示状态
    public function updateAwardShowStatus($id,Request $request)
    {
        $this->awardRepository->updateShowStatus($id);
        Flash::success('操作成功.');
        return redirect(route('awards.index'));
    }

    //更新投票数量
    public function updateNumAction($award_id,$candidate_id,Request $request)
    {
        // dd($request->all());
       app('common')->AwardCandidateRepo()->updateNum($award_id,$candidate_id,$request->all());
       Flash::success('操作成功.');
       return redirect(route('awards.index'));
    }

    /**
     * Show the form for creating a new Award.
     *
     * @return Response
     */
    public function create()
    {
        $candidates = $this->varifyCandidateAdded();

        if(count($candidates) == 0)
        {
            Flash::error('请先添加候选人');
            return redirect(route('awards.index'))
                ->withErrors('请先添加候选人');
        }
        $id_arr = [];
        return view('awards.create',compact('candidates','id_arr'));
    }

    /**
     * Store a newly created Award in storage.
     *
     * @param CreateAwardRequest $request
     *
     * @return Response
     */
    public function store(CreateAwardRequest $request)
    {
        $input = $request->all();

        $varify = app('common')->AwardCandidateRepo()->varifyInputIdArr($input);

        if(!$varify)
        {
               return redirect(route('awards.create'))
                ->withErrors('请至少选择一个候选人')
                ->withInput($input);
        }

        $award = $this->awardRepository->create($input);

        $action = app('common')->AwardCandidateRepo()->syncSave($award->id,$input);

        Flash::success('添加成功.');

        return redirect(route('awards.index'));
    }

    /**
     * Display the specified Award.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $award = $this->awardRepository->findWithoutFail($id);

        if (empty($award)) {
            Flash::error('Award not found');

            return redirect(route('awards.index'));
        }

        return view('awards.show')->with('award', $award);
    }

    /**
     * Show the form for editing the specified Award.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $award = $this->awardRepository->findWithoutFail($id);

        if (empty($award)) {
            Flash::error('Award not found');

            return redirect(route('awards.index'));
        }

        $candidates = $this->varifyCandidateAdded();
        
        if(count($candidates) == 0)
        {
            Flash::error('请先添加候选人');
            return redirect(route('awards.index'))
                ->withErrors('请先添加候选人');
        }
        $id_arr = app('common')->AwardCandidateRepo()->getCandidates($id,true);
        return view('awards.edit')
        ->with('award', $award)
        ->with('candidates',$candidates)
        ->with('id_arr',$id_arr);
    }

    /**
     * Update the specified Award in storage.
     *
     * @param  int              $id
     * @param UpdateAwardRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAwardRequest $request)
    {
        $award = $this->awardRepository->findWithoutFail($id);

        if (empty($award)) {
            Flash::error('Award not found');

            return redirect(route('awards.index'));
        }

        $input = $request->all();

        $varify = app('common')->AwardCandidateRepo()->varifyInputIdArr($input);

        if(!$varify)
        {
               return redirect(route('awards.edit',$id))
                ->withErrors('请至少选择一个候选人')
                ->withInput($input);
        }

        $award->update($input);

        $action = app('common')->AwardCandidateRepo()->syncSave($award->id,$input,'update');

        Flash::success('更新成功.');

        return redirect(route('awards.index'));
    }

    /**
     * Remove the specified Award from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $award = $this->awardRepository->findWithoutFail($id);

        if (empty($award)) {
            Flash::error('Award not found');

            return redirect(route('awards.index'));
        }

        app('common')->AwardCandidateRepo()->clearAward($id);
        
        $this->awardRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('awards.index'));
    }
}
