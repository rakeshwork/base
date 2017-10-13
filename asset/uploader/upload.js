
$(document).ready(function () {


	$("#uploader").fineUploader({
		request: {
			endpoint: "http://localhost/johnson/lsg/uploader/receive",
			params:{
				"uploadify_session_token":$('#uploadify_session_token').val(),
				"uploadify_user_acc_no":$('#uploadify_user_acc_no').val(),
				"upload_scenario":$('#upload_scenario').val()
			}
		},
		validation: {
			itemLimit: $('#file_num_limit').val()
		},
		deleteFile: {
			enabled: true,
			forceConfirm: true,
			endpoint: "http://localhost/johnson/lsg/uploader/delete_file"
		},
		resume: {
			enabled: true
		},
		retry: {
			enableAuto: true,
			showButton: true
		},
		callbacks: {
			onComplete: function(id, name, response) {

				if(response.success = "true") {
					//$('#auxillary_form_upload').append('<input type="text" name="uploaded_items[]" value="'+ response.queue_id +'"/>');
					$('#target_form_id', parent.document).append('<input type="hidden" name="uploaded_items[]" value="'+ response.queue_id +'"/>');
				}
			},

			onSubmitDelete: function(id) {

        this.setDeleteFileParams(
					{
						upload_scenario: $('#upload_scenario').val()
					},
					id);
    	}
		}
	});

	//

});
