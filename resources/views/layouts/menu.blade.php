<li class="">
    <a href="/" target="_blank"><i class="fa fa-home"></i><span>网站首页</span></a>
</li>

<li class="header">网站设置</li>
    <li class="{{ Request::is('zcjy/settings/setting*') || Request::is('zcjy') ? 'active' : '' }}">
      <a href="{!! route('settings.setting') !!}"><i class="fa fa-cog"></i><span>系统设置</span></a>
    </li>


<li class="header">人员管理</li>
<li class="{{ Request::is('zcjy/participants*') ? 'active' : '' }}">
    <a href="{!! route('participants.index') !!}"><i class="fa fa-edit"></i><span>晚宴参与人员管理</span></a>
</li>
<li class="{{ Request::is('zcjy/candidates*') ? 'active' : '' }}">
    <a href="{!! route('candidates.index') !!}"><i class="fa fa-edit"></i><span>候选人管理</span></a>
</li>
<li class="header">奖项/投票管理</li>
<li class="{{ Request::is('zcjy/awards*') ? 'active' : '' }}">
    <a href="{!! route('awards.index') !!}"><i class="fa fa-edit"></i><span>奖项管理</span></a>
</li>

{{-- <li class="{{ Request::is('zcjy/awardCandidates*') ? 'active' : '' }}">
    <a href="{!! route('awardCandidates.index') !!}"><i class="fa fa-edit"></i><span>Award Candidates</span></a>
</li> --}}

<li class="{{ Request::is('zcjy/voteLogs*') ? 'active' : '' }}">
    <a href="{!! route('voteLogs.index') !!}"><i class="fa fa-edit"></i><span>投票记录管理</span></a>
</li>

<li class="">
    <a href="javascript:;" id="refresh"><i class="fa fa-refresh"></i><span>刷新缓存</span></a>
</li>

