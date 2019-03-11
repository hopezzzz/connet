$(document).ready(function ()
{
	//Ranjan's Code //
	$(document).on('click', '.deleteRecord', function(event)
	{
		var id = $(this).attr('id');
		var table  = $(this).attr('data-table');
		localStorage.setItem('record_id',id);
		localStorage.setItem('recordTable',table);
	});
	$(document).on('click', '.deleteRecordBtn', function(event)
	{
		var DeleteRecordId 		= localStorage.getItem('record_id');
		var DeleteRecordTable 	= localStorage.getItem('recordTable');
		if( DeleteRecordId == '' || DeleteRecordTable == '' )
		{
			$.toast({
				heading             : 'Error',
				text                : 'Something is missing. Please try again.',
				loader              : true,
				loaderBg            : '#fff',
				showHideTransition  : 'fade',
				icon                : 'error',
				hideAfter           : 2000,
				position            : 'top-right'
			});
		}
		else
		{
			$.ajax({
				url         : siteroot+'/delete-record',
				type        : "post",
				data        : { 'id':DeleteRecordId,'table':DeleteRecordTable },
				dataType    : "json",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend  : function ()
				{
					$(".loader_div").show();
				},
				complete: function ()
				{
					$(".loader_div").hide();
				},
				success: function (response)
				{
					$(".loader_div").hide();
					if (response.success)
					{
						$.toast({
							heading             : 'Success',
							text                : response.success_message,
							loader              : true,
							loaderBg            : '#fff',
							showHideTransition  : 'fade',
							icon                : 'success',
							hideAfter           : 2000,
							position            : 'top-right'
						});
						$('#confirm-delete-modal').modal('hide');
						$('#'+DeleteRecordId).closest("tr").remove();
						var tableTrCount = $('.recordsTable').find('table tbody tr').length;
						var a=1;
						$('.recordsTable').find('table tbody tr td:nth-child(1)').each(function(){
							$(this).html(a);
							a++;
						});
						if( tableTrCount <= 0 )
						{
							var page = $('.pagination').find('li.active').find('span').text();
							if( page == 1 )
								$('.pagination').find('li').eq(1).find('span').trigger('click');
							else if( page > 1 )
							{
								page = parseInt(page) - 1 ;
								$('.pagination').find('li').eq(page).find('span').trigger('click');
							}
						}
						localStorage.setItem('DeleteRecordId','');
						localStorage.setItem('DeleteRecordTable','');
					}
					else
					{
						$.toast({
							heading             : 'Error',
							text                : response.error_message,
							loader              : true,
							loaderBg            : '#fff',
							showHideTransition  : 'fade',
							icon                : 'error',
							hideAfter           : 2000,
							position            : 'top-right'
						});
						$('#confirm-delete-modal').modal('hide');
					}
					if(response.ajaxPageCallBack)
					{
						ajaxPageCallBack(response);
					}
					if(response.url)
					{
						if(response.delayTime)
							setTimeout(function() { window.location.href=response.url;}, response.delayTime);
						else
							window.location.href=response.url;
					}
				},
				error:function(response){
					$.toast({
						heading             : 'Error',
						text                : 'Connection error.',
						loader              : true,
						loaderBg            : '#fff',
						showHideTransition  : 'fade',
						icon                : 'error',
						hideAfter           : 2000,
						position            : 'top-right'
					});
				}
			});
		}
	});
	//Ranjan's Code Ends //


	// $(document).on('click', '.deleteRecord', function(event)
	// {
	// 	var name = $(this).attr('data-name');
	// 	var ref  = $(this).attr('data-ref');
	// 	var type = $(this).attr('data-type');
	// 	localStorage.setItem('DeleteRecordLabel',name);
	// 	localStorage.setItem('DeleteRecordRef',ref);
	// 	localStorage.setItem('DeleteRecordType',type);
	// 	$('#confirm-delete-modal').modal('show');
	// });

	// $(document).on('click', '.deleteRecordBtn', function(event)
	// {
	// 	var DeleteRecordLabel 	= localStorage.getItem('DeleteRecordLabel');
	// 	var DeleteRecordRef 	= localStorage.getItem('DeleteRecordRef');
	// 	var DeleteRecordType 	= localStorage.getItem('DeleteRecordType');

	// 	if( DeleteRecordRef == '' || DeleteRecordType == '' )
	// 	{
	// 		$.toast({
	// 			heading             : 'Error',
	// 			text                : 'Something is missing. Please try again.',
	// 			loader              : true,
	// 			loaderBg            : '#fff',
	// 			showHideTransition  : 'fade',
	// 			icon                : 'error',
	// 			hideAfter           : 2000,
	// 			position            : 'top-right'
	// 		});
	// 	}
	// 	else
	// 	{
	// 		$.ajax({
	// 			url         : site_url+'delete-record',
	// 			type        : "post",
	// 			data        : { 'type':DeleteRecordType,'ref':DeleteRecordRef },
	// 			dataType    : "json",
	// 			beforeSend  : function ()
	// 			{
	// 				$(".loader_div").show();
	// 			},
	// 			complete: function ()
	// 			{
	// 				$(".loader_div").hide();
	// 			},
	// 			success: function (response)
	// 			{
	// 				$(".loader_div").hide();
	// 				if (response.success)
	// 				{
	// 					$.toast({
	// 						heading             : 'Success',
	// 						text                : response.success_message,
	// 						loader              : true,
	// 						loaderBg            : '#fff',
	// 						showHideTransition  : 'fade',
	// 						icon                : 'success',
	// 						hideAfter           : 2000,
	// 						position            : 'top-right'
	// 					});
	// 					$('#'+DeleteRecordType+'_'+DeleteRecordRef).remove();
	// 					var tableTrCount = $('#tableData').find('table tbody tr').length;
	// 					if( tableTrCount <= 0 )
	// 					{
	// 						var page = $('#ajax_pagingsearc1').find('li.active').find('a').text();
	// 						if( page == 1 )
	// 							$('#ajax_pagingsearc1').find('li').eq(1).find('a').trigger('click');
	// 						else if( page > 1 )
	// 						{
	// 							page = parseInt(page) - 1 ;
	// 							$('#ajax_pagingsearc1').find('li').eq(page).find('a').trigger('click');
	// 						}
	// 					}
	// 					$('#confirm-delete-modal').modal('hide');
	// 					localStorage.setItem('DeleteRecordLabel','');
	// 					localStorage.setItem('DeleteRecordRef','');
	// 					localStorage.setItem('DeleteRecordType','');
	// 				}
	// 				else
	// 				{
	// 					$.toast({
	// 						heading             : 'Error',
	// 						text                : response.error_message,
	// 						loader              : true,
	// 						loaderBg            : '#fff',
	// 						showHideTransition  : 'fade',
	// 						icon                : 'error',
	// 						hideAfter           : 2000,
	// 						position            : 'top-right'
	// 					});
	// 					$('#confirm-delete-modal').modal('hide');
	// 				}
	// 				if(response.ajaxPageCallBack)
	// 				{
	// 					ajaxPageCallBack(response);
	// 				}
	// 				if(response.url)
	// 				{
	// 					if(response.delayTime)
	// 						setTimeout(function() { window.location.href=response.url;}, response.delayTime);
	// 					else
	// 						window.location.href=response.url;
	// 				}
	// 			},
	// 			error:function(response){
	// 				$.toast({
	// 					heading             : 'Error',
	// 					text                : 'Connection error.',
	// 					loader              : true,
	// 					loaderBg            : '#fff',
	// 					showHideTransition  : 'fade',
	// 					icon                : 'error',
	// 					hideAfter           : 2000,
	// 					position            : 'top-right'
	// 				});
	// 			}
	// 		});
	// 	}
	// });


	$(document).on('click', '.updateStatus', function(event)
	{
		var name 	= $(this).attr('data-name');
		var ref  	= $(this).attr('data-ref');
		var type 	= $(this).attr('data-type');
		var status 	= $(this).attr('data-status');

		localStorage.setItem('RecordLabel',name);
		localStorage.setItem('RecordStatus',status);
		localStorage.setItem('RecordRef',ref);
		localStorage.setItem('RecordType',type);

		$('#confirm-status-update-modal').modal('show');
		if( status == 1)
			var status = 'Inactive';
		else
			var status = 'Active';
		$('#confirm-status-update-modal').find('.modal-body').find('.statusLabel').html('<strong>'+name+'</strong> '+status);
	});

	$(document).on('click', '.updateRecordStatusBtn', function(event)
	{
		var name 	= localStorage.getItem('RecordLabel');
		var status 	= localStorage.getItem('RecordStatus');
		var ref  	= localStorage.getItem('RecordRef');
		var type 	= localStorage.getItem('RecordType');

		if( status == '' )
			status = 0;
		if( ref == '' || type == ''  )
		{
			$.toast({
				heading             : 'Error',
				text                : 'Something is missing. Please try again.',
				loader              : true,
				loaderBg            : '#fff',
				showHideTransition  : 'fade',
				icon                : 'error',
				hideAfter           : 2000,
				position            : 'top-right'
			});
		}
		else
		{
			$.ajax({
				url         : site_url+'update-status',
				type        : "post",
				data        : { 'type':type,'ref':ref, 'status':status },
				dataType    : "json",
				beforeSend  : function ()
				{
					$(".loader_div").show();
				},
				complete: function ()
				{
					$(".loader_div").hide();
				},
				success: function (response)
				{
					$(".loader_div").hide();
					if (response.success)
					{
						$.toast({
							heading             : 'Success',
							text                : response.success_message,
							loader              : true,
							loaderBg            : '#fff',
							showHideTransition  : 'fade',
							icon                : 'success',
							hideAfter           : 2000,
							position            : 'top-right'
						});
						if( response.status == 1 )
						{
							$('#'+type+'_'+ref).find('.statusTd').html('<span class="label label-success">Active</span>');
							$('#'+type+'_'+ref).find('.updateStatus').html('Make Inactive').attr('data-status',response.status);
						}
						else
						{
							$('#'+type+'_'+ref).find('.statusTd').html('<span class="label label-warning">Inactive</span>');
							$('#'+type+'_'+ref).find('.updateStatus').html('Make Active').attr('data-status',response.status);
						}
						$('#confirm-status-update-modal').modal('hide');
						localStorage.setItem('RecordLabel','');
						localStorage.setItem('RecordStatus','');
						localStorage.setItem('RecordRef','');
						localStorage.setItem('RecordType','');
					}
					else
					{
						$.toast({
							heading             : 'Error',
							text                : response.error_message,
							loader              : true,
							loaderBg            : '#fff',
							showHideTransition  : 'fade',
							icon                : 'error',
							hideAfter           : 2000,
							position            : 'top-right'
						});
						$('#confirm-status-update-modal').modal('hide');
					}
					if(response.ajaxPageCallBack)
					{
						ajaxPageCallBack(response);
					}
					if(response.url)
					{
						if(response.delayTime)
							setTimeout(function() { window.location.href=response.url;}, response.delayTime);
						else
							window.location.href=response.url;
					}
				},
				error:function(response){
					$.toast({
						heading             : 'Error',
						text                : 'Connection error.',
						loader              : true,
						loaderBg            : '#fff',
						showHideTransition  : 'fade',
						icon                : 'error',
						hideAfter           : 2000,
						position            : 'top-right'
					});
				}
			});
		}
	});

	$(document).on('click', '.addUpdateRecord', function(event)
	{
		var recordID = $(this).attr('data-id');
		var url 	 = $(this).attr('data-url');
		var textt = $.trim(jQuery(this).text());
		if( url != '' )
		{
			$.ajax({
				type	 : "POST",
				dataType : "json",
				data	 : {'recordID':recordID},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url		 : url,
				beforeSend  : function () {
					$(".loader_div").show();
				},
				complete: function () {
					$(".loader_div").hide();
				},
				success: function(response)
				{
					$('#addModal').modal('show');
					$("#addModal").find('.modal-body').html(response.html);
					if (textt == "Add Region") {
						$("#addModal").find('#titleModal').text('Add');
					} else {
						$("#addModal").find('#titleModal').text('Edit');
					}
				},
				error:function(response){
					$.toast({
						heading             : 'Error',
						text                : 'Connection Error.',
						loader              : true,
						loaderBg            : '#fff',
						showHideTransition  : 'fade',
						icon                : 'error',
						hideAfter           : 2000,
						position            : 'top-right'
					});
				}
			});
		}
	});
jQuery(document).on('click','.unsubscribePlan',function () {
	var subsId = jQuery(this).attr('data-ref');
	if( subsId != '' )
	{
		jQuery('#confirm-unsub-modal').modal('show');
		localStorage.setItem('subsId',subsId);
	}else{
		toster('Invalid Request..','Error');
	}

})


jQuery(document).on('click','.confirmUnsubscribed',function () {
var subsId = localStorage.getItem('subsId');
	if (subsId !='' ) {
		$.ajax({
			type	 : "POST",
			dataType : "json",
			data	 : {'subsId':subsId},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url		 : siteroot+'/unsubscribePlan',
			beforeSend  : function () {
				$(".loader_div").show();
			},
			complete: function () {
				$(".loader_div").hide();
				jQuery('#confirm-unsub-modal').modal('hide');
			},
			success: function(response)
			{
				if (response.success) {
						toster(response.success_message,'Success');
						$('#tr'+subsId).find('.unsubscribePlan').html('Cancel')
				}else {
						toster(response.error_message,'Error');
				}

			},
			error:function(response){
				$.toast({
					heading             : 'Error',
					text                : 'Connection Error.',
					loader              : true,
					loaderBg            : '#fff',
					showHideTransition  : 'fade',
					icon                : 'error',
					hideAfter           : 2000,
					position            : 'top-right'
				});
			}
		});
	}else{
				toster('Connection Error','Error');
	}


})
jQuery(document).on('click','.tagEdit',function (e) {
	e.preventDefault();
	var tagRef = $(this).attr('data-ref');
	var tagName = $.trim( $(this).closest('tr').find('.tagName').text() ) ;

	if (tagRef =='') {
		toster('Invalid Request','Error');
		return false
	}
	$('#add-new-tag').modal('show');
	$('#add-new-tag-form').find('.tagRef').val(tagRef);
	$('#add-new-tag-form').find('input[name="tagName"]').val(tagName);


})

jQuery('.modal').on('hidden.bs.modal', function (e) {
		jQuery('.form-control').removeClass('has-error');
		jQuery('.form-group.has-error').removeClass('has-error');
		jQuery('label.has-error').remove();
		var  id  = jQuery(this).attr('id');
		jQuery('#'+id).find('input[type="text"]').val('');
		jQuery('#'+id).find('input[type="checkbox"]').prop('checked',false);
		jQuery('#'+id).find('input[type="hidden"]').val('');
		jQuery('#'+id).find('input[type="password"]').val('');
		jQuery('#'+id).find('input[type="checkbox"]').prop('checked', false);
		jQuery('#'+id).find('textarea').val('');
		jQuery('#'+id).find('input[type="button"]').removeAttr('disabled');
		jQuery('#'+id).find('input[type="submit"]').removeAttr('disabled');
		jQuery('#'+id).find('button[type="button"]').removeAttr('disabled');
		jQuery('#'+id).find('button[type="submit"]').removeAttr('disabled');
		jQuery('#'+id).find('select').val('');
		// jQuery('.customerFollowUpToggle').trigger('change');
});

});
