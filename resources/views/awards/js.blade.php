@section('scripts')
<script type="text/javascript">
	//点击按钮触发
	$('.form_btn').click(function(){
    	$(this).toggleClass('btn-primary');
    	var form_input_hidden = $(this).find('input');
    	if($.empty(form_input_hidden.val()))
    	{
    		form_input_hidden.val($(this).data('value'));
    	}
    	else{
    		form_input_hidden.val('');
    	}
  	});
</script>
@endsection