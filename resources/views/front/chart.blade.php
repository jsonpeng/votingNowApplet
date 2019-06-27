@extends('front.partial.base')

@section('css')
<style type="text/css">
html{
		overflow-x: hidden;
	    overflow-y: hidden;
		height: 100%;
		background-size: cover;
		background: url('{{ asset('images/bg3.jpg') }}');
	}
	#VoteMain {
	    width: 100%;
	    height: 400px;
	    margin: 0 auto;
	    margin-top: 0px;
	    padding: 0px 20px 20px 20px;
	    display: flex;
	    justify-content: space-around;
	}
	.VoteItem {
		width: 300px;
		height: 100%;
		border: 1px solid #efefef;
		float: left;
		margin-left: 20px;
		/* flex: 1; */
		justify-content: space-around;
		background-color: #fff;
		padding-bottom: 60px;
	}
	.VoteValue {
		width: 200px;
		height: 100px;
		background-color: #f47920;
		margin: 0 auto;
		margin-bottom: 0px;
		border: 1px solid #fff;
	}
	.VoteItem .VoteSpan {
		display: block;
	}
	.VoteValue:hover {
		-webkit-box-shadow: 0px 0px 5px #808080;
		-moz-box-shadow: 0px 0px 5px #808080;
		box-shadow: 0px 0px 5px #808080;
	}
	.VoteSpan {
	    font-size: 30px;
    	font-weight: 900;
	    width: 20px;
	    height: 30px;
	    /* background-color: #000; */
	    float: left;
	    margin-top: -35px;
	    /* color: black; */
	    margin-left: 85px;
	    text-align: center;
	    line-height: 30px;
	    color: red;
	    text-align: center;
	    position: relative;
	    display: none;
	}
	.VoteImg {
    	opacity: 0;
		width: 60px;
		height: 60px;
		position: relative;
		-webkit-box-shadow: 0px 0px 5px #ccc;
		-moz-box-shadow: 0px 0px 5px #ccc;
		box-shadow: 0px 0px 5px #ccc;
		-webkit-border-radius: 50%;
		-moz-border-radius: 50%;
		border-radius: 50%;
		border: 2px solid #fff;
		margin: 0 auto;
		margin-left: 5px;
		margin-top: 10px;
		cursor: pointer;
		
	}
	.VoteImg:hover {
		border-color: #4e72b8;
		-webkit-box-shadow: 0px 0px 5px #444693;
		-moz-box-shadow: 0px 0px 5px #444693;
		box-shadow: 0px 0px 5px #444693;
	}
	.VoteText {
		font: 30px "微软雅黑", Arial, Helvetica, sans-serif;
		text-align: center;
		font-weight: 900;
		color: #333;
		/* line-height: 10px; */
		margin-top: 0px;
	}
	.VoteSpanTri {
		position: absolute;
		width: 10px;
		height: 6px;
		/*background-image: url(tri.png);*/
		margin-top: 30px;
		margin-left: -15px;
	}
</style>
@endsection

@section('seo')
	<title>{!! getSettingValueByKeyCache('name') !!}</title>
    <meta name="keywords" content="{!! getSettingValueByKeyCache('seo_keywords') !!}">
    <meta name="description" content="{!! getSettingValueByKeyCache('seo_des') !!}">
@endsection

@section('content')
<img src="{{ asset('images/qrcode_new.png') }}" style="    position: absolute;
    right: 10%;
    top: -5%;
    max-width: 200px;
    height: auto;" />
<div style="text-align: center;margin-top: 100px;">
<img src="{{ asset('images/bg2.png') }} "  style="max-width: 500px;
    height: auto;" />
</div>
@if(isset($error))
	
	      <h1 class="demos-title" style="text-align: center;">{!! $error !!}</h1>

@else
<div id="Demo" style="text-align:center;">
{{-- 	<div style="font-size: 30px;
    font-weight: 700;
    margin-top: 20px;">{!! getSettingValueByKey('main_theme') !!}</div> --}}
{{-- 	<h1 style="    /* margin-top: 200px; */
    /* text-align: left; */
    /* margin-left: 135px; */
    color: white;
    position: absolute;
    left: 8%;">{!! $award->name !!}投票统计</h1> --}}
    <h2  style="color: rgb(255,192,0);font-size: 36px;font-weight: 500;margin-top: 10px;@if(!getSettingValueByKey('sign_word_show_status')) display: none; @endif">{!! $award->name !!}投票统计<br />倒计时 <strong id="sign_flash" style="font-weight: 900;"> </strong></h2>
	<div id="VoteMain"> </div>
</div>
@endif
@endsection


@section('js')
	
	<script type="text/javascript" src="{{ asset('js/timer.js') }}"></script>

	<script type="text/javascript">
	var showStatus = 0;
	function getStatus(){
		$.zcjyRequest('/ajax/get_showstatus',function(res){
			if(res != showStatus)
			{
				showStatus = res;
				showStatus == 1 ? $('h2').show() : $('h2').hide();
			}
		})
	}
	$(document).ready(function(e) {
		$.zcjyRequest('/ajax/get_time',function(time){
			startShowCountDown(time,$('#sign_flash'),'sign_flash');
		});
		getChart();
		setInterval(function(){
			changeChart();
			getStatus();
		},2000);
	});
	var voteJson = [];
	var award_id = 0;

	@if(!isset($error))
	award_id = '{!! $award->id !!}';
	@endif

	var Vote={
		voteJson:voteJson,
		Init:function(){
			console.log(voteJson);

			for(var i=0;i<voteJson.length;i++){
				var mName=voteJson[i]['Name'];
				var mImg=voteJson[i].Img;
				var mValue=voteJson[i].Value;
			
				var VoteItem=$("<div></div>");
				VoteItem.attr("class","VoteItem");
				$("#VoteMain").append(VoteItem);
				
				var VoteImg=$("<img  src=\""+mImg+"\" />");
				VoteImg.attr("class","VoteImg");
				VoteImg.click(function(){
					$(this).next().css("height",$(this).next().height()+1.6+"px");
					$(this).next().data('value',parseInt($(this).next().data('value'))+1);
					$(this).next().css("margin-top",330-20-$(this).next().height()+"px");
					$(this).next().find(".VoteSpan").html($(this).next().data('value'));
					var VoteSpanTri=$("<span></span>");
				    VoteSpanTri.attr("class","VoteSpanTri");
				    $(this).next().find(".VoteSpan").append(VoteSpanTri);
				});
				//VoteImg.click();
				VoteItem.append(VoteImg);

				var VoteValue=$("<div data-value="+mValue+"></div>");
				VoteValue.attr("class","VoteValue");
				// mValue = mValue * 2;
				VoteValue.css("margin-top",330-20-mValue*1.6+"px");
				VoteValue.animate({height:mValue*1.6+"px"},500);
				VoteItem.append(VoteValue);
				// mValue = mValue / 2;
				var VoteSpan=$("<div>"+mValue+"</div>");
				VoteSpan.attr("class","VoteSpan");
				VoteValue.append(VoteSpan);
				
				var VoteSpanTri=$("<span></span>");
				VoteSpanTri.attr("class","VoteSpanTri");
				VoteSpan.append(VoteSpanTri);
				
				
				var VoteText=$("<p></p>");
				VoteText.html(mName);
				VoteText.attr("class","VoteText");
				VoteItem.append(VoteText);
			}
		}
	}

	function getChart()
	{
		voteJson = [];
		$("#VoteMain").html('');
		$.zcjyRequest('/ajax/chart',function(res){
			if(res){
				var award = res['award'];
				$('h1').text(award['name']+'投票统计');
				var candidates = res['candidates'];
				// console.log(candidates);
				for (var i = 0; i < candidates.length; i++) {
						// console.log(candidates[i]);
						voteJson.push(
							{Name:candidates[i]['name'],Img:'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1550725253&di=a9d67f3cf5419bdb11d6aa179e3bd315&src=http://pic43.photophoto.cn/20170506/0470102348231008_b.jpg',Value:candidates[i]['num']}
						);
				}
				// console.log(voteJson);
				Vote.Init();
			}
			else{
				location.reload();
			}
		});
	}

	function changeChart()
	{
		// voteJson = [];
		// $("#VoteMain").html('');
		$.zcjyRequest('/ajax/chart',function(res){
			if(res){
				var award = res['award'];
				if(award['id'] != award_id)
				{
					location.reload();
				}
				$('h1').text(award['name']+'投票统计');

				var candidates = res['candidates'];
				// console.log(candidates);
				for (var i = 0; i < candidates.length; i++) 
				{
					// console.log(parseInt(candidates[i]['num']));
					// console.log(parseInt(voteJson[i]['num']));
					
					console.log(parseInt(candidates[i]['num'])-parseInt(voteJson[i]['Value']));
					manyClick(i,parseInt(candidates[i]['num'])-parseInt(voteJson[i]['Value']));
					voteJson[i]['Value'] = candidates[i]['num'];
				}
				// console.log(voteJson);
				// Vote.Init();
			}
			else{
				location.reload();
			}
		});
	}

	function manyClick(sort = 0,change = 1)
	{
		 // console.log(change);
		if(change > 0)
		{
			sort = sort +1;
			for (var i = 0; i < change; i++) {
				$('img:eq('+sort+')').click();
			}
		}
	}

	</script>

@endsection
