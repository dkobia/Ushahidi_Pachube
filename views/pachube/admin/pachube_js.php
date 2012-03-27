// Show Function
function showForm(id)
{
	if (id) {
		$('#' + id).toggle(400);
	}
}

// Form Submission
function feedAction ( action, confirmAction, id )
{
	var statusMessage;
	var answer = confirm('<?php echo Kohana::lang('ui_admin.are_you_sure_you_want_to'); ?> ' + confirmAction + '?')
	if (answer){
		// Set Category ID
		$("#feed_id").attr("value", id);
		// Set Submit Type
		$("#action").attr("value", action);		
		// Submit Form
		$("#pachubeFormMain").submit();			
	
	}
}

// Create Trigger
function createTrigger ( feed_id, stream_id, stream ){
	$("#trigger_btn_"+stream_id).attr('value','Loading...');
	$.post("<?php echo url::site() . 'admin/pachube/add_trigger' ?>", { 
		feed_id: feed_id,
		stream_id: stream_id,
		stream: stream,
		trigger: $("#trigger_value_"+stream_id).val(),
		trigger_type: $("#trigger_type_"+stream_id).val(),
		category_id: $("#trigger_category_"+stream_id).val()
	},
	function(data){
		if (data.status == 'success'){
			$("#feedTriggersDiv_"+feed_id).append('<div id="trigger_'+data.message.id+'">&middot;&nbsp;When value '+data.message.unit+' '+$("#trigger_type_"+stream_id+" option:selected").text()+' '+$("#trigger_value_"+stream_id).val()+' post a report to '+$("#trigger_category_"+stream_id+" option:selected").text()+' [<a href="javascript:deleteTrigger('+data.message.id+')">delete</a>]</div>');
		}
		else
		{
			alert('Failed - Please make sure your values are correct!');
		}

		$("#trigger_value_"+stream_id).val('');
		$("#trigger_btn_"+stream_id).attr('value','Add');
  	}, "json");
}

// Delete Trigger
function deleteTrigger(id){
	var answer = confirm('Delete this trigger?')
	if (answer){
		$.post("<?php echo url::site() . 'admin/pachube/del_trigger' ?>", { trigger_id: id },
			function(data){
				$("#trigger_"+id).remove();
			}, "json");
	}
}