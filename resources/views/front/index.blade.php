@extends('front.partial.base')

@section('css')
<style type="text/css">
	html{
		height: 100%;
		background-size: contain;
		background: url('{{ asset('images/bg_front.jpg') }}') no-repeat;
	}

	.weui-btn_disabled.weui-btn_primary,.weui-btn_primary,button.hover,.weui-btn_primary:active,.weui-btn_primary:not(.weui-btn_disabled):active{
    	background-color: #FFD700;
	}
</style>
@endsection

@section('seo')
	<title>{!! getSettingValueByKeyCache('name') !!}</title>
    <meta name="keywords" content="{!! getSettingValueByKeyCache('seo_keywords') !!}">
    <meta name="description" content="{!! getSettingValueByKeyCache('seo_des') !!}">
@endsection

@section('content')
	
	<div class="error" style="@if(isset($error)) display:block; @else display:none; @endif">
		<header class="demos-header" style="padding: 35px 0;margin-top: 56%;">
		      <h1 class="demos-title" style="text-align: center;
		    font-size: 26px;
		    color: white;
		    font-weight: 400;
		    margin: 0 15%;">{!! $error !!}</h1>
		</header>
	</div>

	<div class="wenan" style="@if(!isset($error)) display:block; @else display:none; @endif">
		<header class="demos-header" style="    padding: 5px 0;margin-top: 36%;">
					      <h1 class="demos-title" style="text-align: center;font-size: 26px;color: white;font-weight: 400;margin: 0 15%;">请完善投票信息</h1>
		</header>
		<div class="weui-cells weui-cells_form" style="margin-left: 10%;margin-right: 10%;border-radius: 18px;">

		 	<form>
				{{-- <div class="weui-cells__title">请输入您的姓名</div> --}}
				<div class="weui-cells">
				  <div class="weui-cell">
				    <div class="weui-cell__bd">
				      <input class="weui-input" type="text" name="name" placeholder="请输入您的姓名">
				    </div>
				  </div>
				</div>
			</form>

			<?php $award = optional($award);?>

			<form style="display: none;">
				<div class="weui-cells__title">请选择:</div>
				<div class="demos-content-padded" style="padding: 15px;">
				  @foreach($candidates as $candidate)
			      	<a href="javascript:;"  class="weui-btn weui-btn_default base_item" data-id="{!! $candidate->candidate->id !!}">{!! $candidate->candidate->name !!}</a>
			 	  @endforeach
			 		<input type="hidden" name="candidate_id" />
			 		<input type="hidden" name="user_id" />
			 		<input type="hidden" name="award_id" value="{!! $award->id !!}" />
			    </div>
			</form>


			<div class="weui-btn-area">
			      <button class="weui-btn weui-btn_primary weui-btn_disabled" href="javascript:" disabled="disabled" id="nextTip">确认登录</button>
			 </div>

			 <p style="text-align: center;font-size: 16px;color:red;padding: 0px 10px;">温馨提示： 输入本人姓名，进行登陆；账号如有问题，请联系现场工作人员。</p>
			
		</div>
	</div>

@endsection


@section('js')
	<script>
		if($(window).width() >=800)
		{
			$('html').hide();
			alert('请使用手机端查看');
		}

		var STEP = 1;
		var award_id = 0;

		@if(isset($award))
		award_id = '{!! $award->id !!}';
		@endif

		function changeChart()
		{
			$.zcjyRequest('/ajax/chart',function(res){
				if(res){
					console.log(res);
					if(res == '投票未开始'){
						location.reload();
					}
					var award = res['award'];
					if(award['id'] != award_id)
					{
						location.reload();
					}
				}
				else{
					location.reload();
				}
			});
		}

		if(localStorage.user_id && localStorage.login == 1)
		{

			$.zcjyRequest('/ajax/get_login_status/'+localStorage.user_id,function(res){
					if(res)
					{
						STEP2();
					}
					else{
						localStorage.login = 0;

					}
			});

			setInterval(function(){
					changeChart();
			},8000);
			console.log('登录过了');
		}
		else{
			console.log('重新登录');
			$('.error').hide();
			$('.wenan').show();
		}

	

		$('input[name=name]').keyup(function(){
			console.log($(this).val());
			if(!$.empty($(this).val()))
			{
				$('#nextTip').removeClass('weui-btn_disabled')
						.removeAttr('disabled');
			}
		});

		//点击下一步
		$('#nextTip').click(function(){
			if(STEP == 1){
				// if(window.confirm('请输入使用自己的名字进行投票!您当前确认后将无法更换,如输入错误请联系现场工作人员!')){
					$.zcjyRequest('/ajax/name_varify',function(res){
						if(res)
						{
							STEP = 2;
							localStorage.login = 1;
							localStorage.user_id = res;
							localStorage.user_name = $('input[name=name]').val();
							changeChart();
							setInterval(function(){
								changeChart();
							},3000);
							STEP2();


						}
					},$('form:eq(0)').serialize());
				// }
			}
			else if(STEP == 2)
			{
				var confirm = window.confirm('每个人每轮只有一次投票机会,投票后将无法继续投其他人确定投票吗');
				if(confirm)
				{
					dealSelected();
					$.zcjyRequest('/ajax/public_sign',function(res){
					if(res)
					{
						$.alert(res);
					}
				},$('form:eq(1)').serialize());
				}
			}
		});

		//投票
		$('.base_item').click(function(){
			$('.base_item').removeClass('weui-btn_primary').addClass('weui-btn_default');
			$(this).addClass('weui-btn_primary').removeClass('weui-btn_default');
			$('#nextTip').removeClass('weui-btn_disabled')
						.removeAttr('disabled')
		});

		function dealSelected()
		{
			$('.base_item').each(function(){
				if($(this).hasClass('weui-btn_primary'))
				{
					$('input[name=candidate_id]').val($(this).data('id'));
				}
			});
		}

		function STEP2(){
			$('form:eq(0)').hide();
			$('form:eq(1)').show();
			$('p').hide();
			$('button').text('确认投票');
			STEP = 2;
			var h1_word = '{!! $award->name !!}候选人';
			@if(isset($error))
			var h1_word = '{!! $error !!}';
			@endif
			$('h1').text(h1_word);

			$('input[name=user_id]').val(localStorage.user_id);
		}

	</script>
@endsection
