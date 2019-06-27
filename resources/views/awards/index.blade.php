@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">奖项管理</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('awards.create') !!}">添加</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="row">
            <form id="setting_form">
                    <div class="form-group col-sm-4">
                                <label for="name" class="col-sm-4 control-label">投屏文字显示状态</label>
                                <div class="col-sm-4">
                                    <select name="sign_word_show_status" class="form-control select_action">
                                        <option value="1" @if(getSettingValueByKey('sign_word_show_status')) selected="selected" @endif>显示</option>
                                        <option value="0" @if(!getSettingValueByKey('sign_word_show_status')) selected="selected" @endif>不显示</option>
                                    </select>
                                </div>
                            </div>
            </form>

        </div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('awards.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('.status_btn').click(function(){
        $(this).parent().find('form').submit();
    });
    function editStatus(obj){
        console.log(1);
        $(obj).parent().parent().find('.sign_num').show();
    }
    $('.select_action').change(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/zcjy/settings/setting",
                type:"POST",
                data:$("#setting_form").serialize(),
                success: function(data) {
                  if (data.code == 0) {
                    layer.msg(data.message, {icon: 1});
                  }else{
                    layer.msg(data.message, {icon: 5});
                  }
                },
                error: function(data) {
                  //提示失败消息

                },
            }); 
    });
</script>
@endsection

