(function () {
	"use strict";

	var treeviewMenu = $('.app-menu');

	// Toggle Sidebar
	$('[data-toggle="sidebar"]').click(function(event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});

	// Activate sidebar treeview toggle
	$("[data-toggle='treeview']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

	// Set initial active toggle
	$("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

	//Activate bootstrip tooltips
	$("[data-toggle='tooltip']").tooltip();

})();
function priceReIndex(){
	var i=0;
	jQuery(".priceForm").each(function(){ $(this).attr("name","price["+i+"]"); i++;});
	var i=0;
	jQuery(".creditForm").each(function(){ $(this).attr("name","credit["+i+"]"); i++;});
	var k=0;
	jQuery(".perMinCostForm").each(function(){ $(this).attr("name","per_min_cost["+k+"]"); k++;});
	var j=0;
	jQuery(".currency_id").each(function(){ $(this).attr("name","currency_id["+j+"]"); j++;});
}
//Ranjan's Code
$(document).ready(function(){
	$(document).on('click','.add-more-price',function(){
		$.ajax({
			type	 : "POST",
			dataType : "html",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url		 : siteroot+'/admin/add-price',
			beforeSend  : function () {
				$(".loader_div").show();
			},
			complete: function () {
				$(".loader_div").hide();
			},
			success: function(response){

				$('.priceTable').append(response);tabelRowsCount();priceReIndex();// callToEnhanceValidate();
				$('.billingType').trigger('click');
			}
		});
	});

	//Delete product item row
	var deleteItems = [];
	jQuery(document).on('click', '.delete-price', function(){
		var rows = jQuery(".priceTable tbody tr").length;
		jQuery(this).parents("tr").remove();
		var priceId = jQuery(this).attr('id');
		priceId = (priceId == '' || priceId == null ? undefined : priceId);
		if(priceId !=undefined){ deleteItems.push(priceId);}
		jQuery(".delPrice").val(deleteItems);tabelRowsCount();priceReIndex();
	});
	//hide delete button
	function tabelRowsCount(){
		var rows = jQuery(".priceTable tbody tr").length;
		if(rows > 1){
	  		jQuery(".delete-price").show();
	  	}else{
	  		jQuery(".delete-price").hide();
	  	}
	}
	tabelRowsCount();
});


var callToEnhanceValidate=function()
{
	$(".currency_id").each(function() {
			$(this).rules('remove');
			$(this).rules('add', {
					required: true,
					noSpace: true,
					notEqual : "0",
					messages: {
							required: "This field is required"
					},
			});
	});
	$(".priceForm").each(function() {
			$(this).rules('remove');
			$(this).rules('add', {
					required: true,
					noSpace: true,
					messages: {
							required: "This field is required"
					},
			});
	});
	$(".creditForm").each(function() {
			$(this).rules('remove');
			$(this).rules('add', {
					required: true,
					noSpace: true,
					number: true,
					messages: {
							required: "This field is required"
					},
			});
	});
	$(".perMinCostForm").each(function() {
			$(this).rules('remove');
			$(this).rules('add', {
					required: true,
					noSpace: true,
					notEqual: 0,
					messages: {
						required: "This field is required"
					},
			});
	});
}


jQuery(document).on('click change','.billingType',function(e) {
	e.preventDefault();
	var thistext = $.trim($(this).find('option:selected').text());
	var thisvalue = $(this).val();
	$(document).find('.creditfield').hide();
	// $(document).find('.no_of_contacts').hide();
	// $('.plans').addClass('col-md-6').removeClass('col-md-4');
	if (parseInt(thisvalue) == 2) {
		$(document).find('.creditfield').show();
		// $(document).find('.no_of_contacts').show();
		// $('.plans').addClass('col-md-4').removeClass('col-md-6');
	}
})

$('.billingType').trigger('click');

//Pagination
$(document).on('click','.pagination li a',function(e){
	e.preventDefault();
	var link = $(this).attr("href");
	$.ajax({
		url:link,
		beforeSend  : function () {
			$(".loader_div").show();
		},
		complete: function () {
			$(".loader_div").hide();
			jQuery('#confirm-unsub-modal').modal('hide');
		},

	}).done(function(data){
		$(".recordsTable").html(data);
	});
});


jQuery(document).on('keyup','.parseInt',function() {
		var value = 0;
		if (jQuery(this).val().length == 0 ) {
			 jQuery(this).val(value);
		}
		value = parseInt(jQuery(this).val());
		jQuery(this).val(value);
	})

var textInput 			 = document.getElementById('searchKey');
var timeout 				 = null;
if (textInput !=null) {
	textInput.onkeyup    = function (e) {
		var dataContainer  = $('#pageType').attr('data-container');
		var url		  	  	 = $(this).attr('data-url');
		if( dataContainer != '' && dataContainer != undefined )
		var searchKey 	   = $(dataContainer).find("#searchKey").val();
		else
		var searchKey 	   = $('#searchKey').val();
		var statusBox 	   = $(document).find(".filterCheckBox").is(':checked');
		if (statusBox)
		statusBox 		 = $(document).find(".filterCheckBox:checked").val();
		else
		statusBox 		 = '';

		var fromDate   		 = $('#fromDate').val();
		var toDate 	   		 = $('#toDate').val();

		searchKey 	  = $.trim(searchKey);fromDate= $.trim(fromDate);toDate= $.trim(toDate);statusBox= $.trim(statusBox);
		if( url != '' && url != undefined)
		{
			clearTimeout(timeout);
			// Make a new timeout set to go off in 800ms
			timeout = setTimeout(function () {
				$.ajax({
					type	 : "GET",
					dataType : "html",
					data	 : {'searchKey':searchKey,'page':1,'fromDate':fromDate,'toDate':toDate,'statusBox':statusBox},
					url		 : url,
					beforeSend  : function () {
						$(".loader_div").show();
					},
					complete: function () {
						$(".loader_div").hide();
					},
					success: function(response)
					{
						if( dataContainer != '' && dataContainer != undefined )
						$(dataContainer).find("#tableData").html(response);
						else
						$(".recordsTable").html(response);
					},
					error:function(response){
						console.log('error');
					}
				})
			}, 800);

		}
	};
}

$(document).on('click','.getLeadData', function(e){
    var ref_url = $(this).attr('data-url');
    var leadId = $(this).attr('data-url');
    $.ajax({
        type     : "GET",
        url      : ref_url,
        data : {lead_id:leadId},
        beforeSend  : function () {
            $(".loader_div").show();
        },
        complete: function () {
            $(".loader_div").hide();
        },
        success: function(response)
        {
            $("#leadDetails").find('.modal-body').html(response);
        },
        error:function(response){
            console.log('error');
        }
    });
});
$(document).on('click','.close',function(){
	$(this).parents().find('.modal').modal('hide');
});

// localStorage.setItem('sideClass','');
$(document).on('click','.app-sidebar__toggle',function () {
	$(document).find('body').hasClass('sidenav-toggled') ? localStorage.setItem('sideClass','sidenav-toggled') : localStorage.setItem('sideClass','');
})
var kk = localStorage.getItem('sideClass');
kk == 'sidenav-toggled' ? $('body').addClass('sidenav-toggled') : '';
