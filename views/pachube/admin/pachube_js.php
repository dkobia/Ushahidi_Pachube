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
function createTrigger ( feed_id, stream_id ){
	$.post("<?php echo url::site() . 'admin/pachube/trigger' ?>", { 
		feed_id: feed_id,
		stream_id: stream_id,
		trigger: $("#").val(),
		trigger_type: $("#").val(),
		category_id:  $("#").val()
	},
	function(data){
		if (data.status == 'success'){
			$('#form_fields_' + form_id).html('');
			$('#form_fields_' + form_id).show(300);
			$('#form_fields_' + form_id).html(data.message);
			$('#form_field_' + form_id +' [name=field_name]').focus();
		}
  	}, "json");
}