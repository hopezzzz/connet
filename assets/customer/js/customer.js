$(document).ready(function() {
    //Star Rating
    $(document).on('click','.rateLead',function(){
        var leadId = $(this).attr('data-lead-id');
        $('#rateLeadID').val(leadId);
    });
    $(document).on('change','.rating [type="radio"]',function(){
        var star    = $(this).attr('value');
        var leadID  = $('#rateLeadID').val();
        $.ajax({
            url                : siteroot + '/call-rating',
            type               : 'post',
            data               : { id:leadID,rate:star},
            headers        : {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            dataType     : "json",
            beforeSend : function() {
                $(".loader_div").show();
            },
            complete   : function() {
                $(".loader_div").hide();
            },
            success    : function(response) {
                if (response.success) {
                    tost(response.success_message, 'Success');
                } else {
                    tost(response.error_message, 'Info');
                }
                $('#leadRating').find('button[data-dismiss="modal"]').trigger('click');
                $(document).find('.modal-backdrop').remove();
                var i = 0;
                $(document).find('.recordsTable tr#'+response.data_id).children('.rateTD').find('i.stars').each(function(){
                    i = i+1;
                    $(this).removeClass();
                    if(i<=star){
                        $(this).addClass('stars rate-blue fa fa-star');
                    }else{
                        $(this).addClass('stars fa fa-star-o');
                    }
                });
                if(star==1){ var tdTitle='Poor'; }else if(star==2){ var tdTitle='Good'; }else if(star==3){ var tdTitle='Excellent'; }
                $(document).find('.recordsTable tr#'+response.data_id).children('.rateTD').attr('title',tdTitle);
            },
            error     : function(response) {
              $.toast({
                  heading                           : 'Error',
                  text                              : 'Connection error.',
                  loader                        : true,
                  loaderBg                      : '#fff',
                  showHideTransition    : 'fade',
                  icon                              : 'error',
                  hideAfter                     : delayTime,
                  position                      : 'top-right'
              });
            }
        });
    });
    //Ranjans Code //
    $(document).on('change','.annEmail', function(){
        if($(this).is(':checked')){
            if($(this).val()==1){
                $('.emailDiv').removeClass('d-none');
            }else{
                $('.emailDiv').addClass('d-none');
                // $('.emailDiv').find('input[type=text], textarea').val('');
            }
        }
    });
    $(document).on('change','.recDisp', function(){
        if($(this).is(':checked')){
            if($(this).val()==1){
                $('.callAnn').removeClass('d-none');
            }else{
                $('.callAnn').addClass('d-none');
                // $('.callAnn').find('input[type=text], textarea').val('');
            }
        }
    });
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
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
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
            $('button[data-dismiss="modal"]').trigger('click');
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
						// $('#confirm-delete-modal').modal('hide');
						$('#'+DeleteRecordId).closest("tr").remove();
						var tableTrCount = $('.recordsTable').find('table tbody tr').length;

            var a=1;
						$('.recordsTable').find('table tbody tr td:nth-child(1)').each(function(){
							$(this).html(a);
							a++;
						});
            var itSrNo = 0;
            if (tableTrCount == 0) {
              itSrNo = $('.recordsTable').find('table thead tr th').length
              $('.recordsTable').find('tbody').html('<tr><td colspan="'+itSrNo+'">No Contact Found</td></tr>')
            }

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
    //Change Status
    $(document).on('click', '.statusRecord', function(event)
    {
        var id = $(this).attr('id');
        var table  = $(this).attr('data-table');
        var status  = $(this).attr('data-status');
        if(status==1){
            var statusText = 'inactive';
        }else if(status==0){
            var statusText = 'active';
        }
        $('#confirm-status-modal').find('.campStatus').find('.status').text(statusText);
        localStorage.setItem('campStatus',status);
        localStorage.setItem('recordTable',table);
        localStorage.setItem('record_id',id);
    });
    $(document).on('click', '.statusRecordBtn', function(event)
    {
        var recordId      = localStorage.getItem('record_id');
        var recordTable   = localStorage.getItem('recordTable');
        var campStatus    = localStorage.getItem('campStatus');
        if( recordId == '' || recordTable == '' )
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
                url         : siteroot+'/change-status',
                type        : "post",
                data        : { 'id':recordId,'table':recordTable,'status':campStatus },
                dataType    : "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                beforeSend  : function ()
                {
                    $(".loader_div").show();
                },
                complete: function ()
                {
                    $(".loader_div").hide();
                    $('button[data-dismiss="modal"]').click();
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
                        if(campStatus==1){
                            $('.recordsTable .statusRecord[id="'+recordId+'"]').find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
                            $('.recordsTable').find('a.statusRecord[id="'+recordId+'"]').attr('data-status',0);
                        }else{
                            $('.recordsTable .statusRecord[id="'+recordId+'"]').find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
                            $('.recordsTable').find('a.statusRecord[id="'+recordId+'"]').attr('data-status',1);
                        }
                        localStorage.setItem('recordId','');
                        localStorage.setItem('recordTable','');

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

    var navListItems = $('div.setup-panel-3 div a'),
        allWells = $('.setup-content-3'),
        allNextBtn = $('.nextBtn-3'),
        allPrevBtn = $('.prevBtn-3');

    allWells.hide();

    allPrevBtn.click(function() {
        var curStep = $(this).closest(".setup-content-3"),
            curStepBtn = curStep.attr("id"),
            prevStepSteps = $('div.setup-panel-3 div a[href="#' + curStepBtn + '"]').parent().prev().children("a");
            curStep.hide();
						$('.contact-list-media-active').removeClass('contact-list-media-active');
						prevStepSteps.closest('.contact-list-media').addClass('contact-list-media-active')
						curStep.prev().closest('.contact-list-media').addClass('contact-list-media-active');
            curStep.prev().show();
    });

    allNextBtn.click(function() {
        var curStep = $(this).closest(".setup-content-3"),
            curStepBtn = curStep.attr("id"),
            nextStepSteps = $('div.setup-panel-3 div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid){
					nextStepSteps.removeAttr('disabled').trigger('click');
					// console.log(nextStepSteps);
					$('.contact-list-media-active').removeClass('contact-list-media-active');
					nextStepSteps.closest('.contact-list-media').addClass('contact-list-media-active');
				}
    });

    // $('div.setup-panel-3 div a.btn-info').trigger('click');
    $('#step-1').show();

// });
//
// $(document).ready(function() {
    $('.timepicker').timepicker({
        'timeFormat': 'H:i:s'
    });

    // Code For test mail

    // jQuery(document).on('click','.testMail',function(event) {
    //   event.preventDefault();
    //   var dataRef = jQuery(this).attr('data-ref');
    //   var dataUrl = jQuery(this).attr('data-url');
    //
    //     $.ajax({
    //         url         : dataUrl,
    //         type        : 'POST',
    //         data        : {email : dataRef},
    //         headers     : {
    //         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //         },
    //         dataType    : "json",
    //         beforeSend  : function () {
    //             $(".loader_div").show();
    //         },
    //         complete: function () {
    //             $(".loader_div").hide();
    //         },
    //         success: function (response) {
    //             $.toast().reset('all');
    //             var delayTime = 3000;
    //             if(response.delayTime)
    //                 delayTime = response.delayTime;
    //             if (response.success)
    //             {
    //                 tost(response.success_message,'Success',delayTime);
    //             }else{
    //               tost(response.error_message,'Success',delayTime);
    //             }
    //         },
    //         error:function(response){
    //             var delayTime = 3000;
    //             tost('Connection error.','Error',delayTime);
    //         }
    //     });
    //
    // })


    /*******************URL ACTIVATION/*******************/
    var urlCurrent = window.location.href;
		if (urlCurrent.split('/')[5].split('-').length == 1) {
		var ur = urlCurrent.split('/')[5].split('-')
		}else{
		var ur = urlCurrent.split('/')[5].split('-');
		}

    jQuery('.navbar-nav .nav-item').removeClass('active');
    jQuery('.nav-link').each(function() {
			var hrefurl =  $(this).attr('href');
			if (hrefurl)
			{
				for (var i = 0; i < ur.length; i++)
				{
					var toActive = hrefurl.indexOf(ur[i].split(/[?#]/)[0]);
					if (toActive > -1) {
							document.title = jQuery('.breadcrumb-item').find('a').text();jQuery(this).parent().addClass('active');break;
					};
				}
			}
     })
    /*******************URL ACTIVATION/*******************/


    jQuery('#availTimeFrom').timepicker({
        'timeFormat': 'H:i:s'
    });


    jQuery('#delayTimewwww').timepicker({
        'timeFormat': 'H:i:s',
        'minTime':'00:00:00',
        'step': '1',
        'maxTime': '02:00:00',
        'showDuration' : true
    });

    jQuery('#availTimeTo').timepicker({
        'timeFormat': 'H:i:s',
        'minTime': $('#availTimeFrom').val()
    });
    jQuery('#availTimeFrom').on('changeTime', function() {
        jQuery('#availTimeTo').timepicker('remove');
        jQuery('#availTimeTo').timepicker({
            'timeFormat': 'H:i:s',
            'minTime': $(this).val(),
            'maxTime': '23:30:00',
        });
    });


    jQuery(document).on('click', '.unsubscribePlan', function() {
        var subsId = jQuery(this).attr('data-ref');
        var dates = jQuery(this).closest('tr').find('.valid_up_to').attr('data-ref');
        if (subsId != '') {
            jQuery('#confirm-unsub-modal').modal('show');
            jQuery('#confirm-unsub-modal').find('#uptoDate').html(dates);
            localStorage.setItem('subsId', subsId);
        } else {
            toster('Invalid Request..', 'Error');
        }

    })


    jQuery(document).on('click', '.confirmUnsubscribed', function() {
        var subsId = localStorage.getItem('subsId');
        if (subsId != '') {
            $.ajax({
                type: "POST",
                dataType: "json",
                data: {
                    'subsId': subsId,
                    'period': $('input[type="radio"]:checked').val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: siteroot + '/unsubscribePlan',
                beforeSend: function() {
                    $(".loader_div").show();
                },
                complete: function() {
                    $(".loader_div").hide();
                    jQuery('#confirm-unsub-modal').modal('hide');
                },
                success: function(response) {
                    if (response.success) {
                        tost(response.success_message, 'Success');
                        if(response.canceled_at == 1){

                          $('#tr'+subsId).find('span.badge-secondary').html('Canceled');
                          $('#tr'+subsId).find( "a.unsubscribePlan" ).replaceWith( '<span class="badge badge-secondary text-uppercase">Canceled</span>');
                        }

                        if (response.canceled_at_message) {
                          $(document).find('#tr'+subsId).find('.green').attr('data-original-title',response.canceled_at_message);
                        }

                        location.reload();
                    } else {
                        tost(response.error_message, 'Error');
                    }

                },
                error: function(response) {
                    $.toast({
                        heading: 'Error',
                        text: 'Connection Error.',
                        loader: true,
                        loaderBg: '#fff',
                        showHideTransition: 'fade',
                        icon: 'error',
                        hideAfter: 2000,
                        position: 'top-right'
                    });
                }
            });
        } else {
            toster('Connection Error', 'Error');
        }

    });

    jQuery(document).on('change', '.checkAllBox', function() {
        jQuery('.weekday:checkbox').not(this).prop('checked', this.checked);
    })
    jQuery(document).on('change', '.weekday', function() {
        if (jQuery('.weekday:checked').length == jQuery('.weekday').length - 1) {
            jQuery('.checkAllBox').prop('checked', this.checked);
        }
    })



    jQuery('.modal').on('hidden.bs.modal', function(e) {
    	var id = jQuery(this).attr('id');
    	if (id == 'campaign-contacts') {
    		  $('#campaign-contacts').find('.modal-body').html('');
    		  $('#campaign-contacts').find('.customerCheckbox').prop( "checked", false );
    	}else{
		     jQuery('.form-control').removeClass('has-error');
        jQuery('.form-group.has-error').removeClass('has-error');
        jQuery('label.has-error').remove();jQuery('#'+id).find('input[type="text"]').val('');
        jQuery('#'+id).find('input[type="checkbox"]').prop('checked', false);
        jQuery('#'+id).find('input[type="hidden"]').val('');jQuery('#'+id).find('input[type="password"]').val('');
        jQuery('#'+id).find('input[type="checkbox"]').prop('checked', false);
        jQuery('#'+id).find('textarea').val('');
        jQuery('#'+id).find('input[type="button"]').removeAttr('disabled');
        jQuery('#'+id).find('input[type="submit"]').removeAttr('disabled');
        jQuery('#'+id).find('button[type="button"]').removeAttr('disabled');
        jQuery('#'+id).find('button[type="submit"]').removeAttr('disabled');
        jQuery('#'+id).find('select').val('');
        $('#campaign-contacts').find('.customerCheckbox').prop( "checked", false );
        // jQuery('.customerFollowUpToggle').trigger('change');
				}

    });


    jQuery(document).on('click', '.fetchEmail', function(event) {
        event.preventDefault();
        var obj 			= jQuery(this);
        var refrence 	= jQuery('.campId').val();
        if (refrence == '') {
            tost('Invalid Request..', 'Error');
            return false;
        }
        $.ajax({
          url 			 	 : siteroot + '/customer/fetch-campaign-email',
          type 			 	 : 'post',
          data 			 	 : { refrence: refrence},
          headers    	 : {
         'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            dataType	 : "json",
            beforeSend : function() {
                $(".loader_div").show();
                 $(".loader_div").find('.spinner').after('<p id="checkEmailLoader" style="margin: 100px auto;position: absolute;right: 0;bottom: 25%;left:39%;">Please wait we are processing your request</p>');

            },
            complete   : function() {
                $(".loader_div").hide();
                $(document).find('#checkEmailLoader').remove();
            },
            success    : function(response) {
                if (response.success) {
                    tost(response.success_message, 'Success');
                    if (response.mailContent) {
                        jQuery('.mail-recived').html(response.mailContent);
                        jQuery('.fetchEmail').parent().remove();
                        jQuery('.add-camp-button').show();
                    }


                } else {
                    $.toast({
                        heading							: 'Info',
                        text  							: response.error_message,
                        loader    					: true,
                        loaderBg  					: '#fff',
                        showHideTransition	: 'fade',
                        icon 								: 'info',
                        position 						: 'top-right'
                    });
                }
                $(".loader_div").hide();

            },
            error     : function(response) {
              var delayTime = 2000;
              $.toast({
                  heading							: 'Error',
                  text  							: 'Connection error.',
                  loader    					: true,
                  loaderBg  					: '#fff',
                  showHideTransition	: 'fade',
                  icon 								: 'error',
                  hideAfter 					: delayTime,
                  position 						: 'top-right'
              });
            }
        });

    })







    var quotearea = document.getElementsByClassName('testMail')
    var output      = $('#output');
    var elemid      = 'testMail';
    var testMail    = '.testMail';
    var campaignTag = '.campaignTag';


    //  function to create labels and tags
    function getText(elem) {
        if (elem.id === elemid) {
            return elem.value.substring(elem.selectionStart, elem.selectionEnd);
        }
        return null;
    }

    /**
     * set mouse up event to init create tag popup
     * Class Name .testMail
     */
    $(document).on('mouseup', testMail+':not('+campaignTag+')', function(event) {
        event.preventDefault();event.stopPropagation();
        var senderElement 	= event.target;
        var start = '';
        var end = '';
        if ($(event.target).is("p")) {
            // close campaignTag
            $(campaignTag).each(function() { $(this).popover('dispose'); });
            var index = $(testMail).index(this);
            $(testMail+' span').contents().unwrap();
            var sel = window.getSelection(),range;
            if (sel.getRangeAt) { range = sel.getRangeAt(0);}
            //  check user selected text
            if (range.endOffset > range.startOffset) {
                start = range.startOffset;
                end   = range.endOffset;
                selectHTML(quotearea);
                $(this).find('span').css({"background":"yellow","font-weight":"bold"});
                $(testMail+'').not($(this)).each(function() {$(this).popover('dispose');});
                $(this).popover({trigger : 'click',html: true,title: 'Create Tag',content : $('#add-new-uom').html()});
                // output.addClass('over');
                showPopover($(this));
                setTimeout(function() {
                    jQuery(document).find('.popover').find('.popover-body').find('.unitName').focus();
                    jQuery(document).find('.indexRow').val(index);
                    jQuery(document).find('.positionStart').val(start);
                    jQuery(document).find('.positionEnd').val(end);
                    getSelText();
                }, 200);
                return false;
            } else {
                $(testMail+'').popover('dispose');
                $(document).find('#tagName').removeClass('is-invalid');
                $(document).find('#tagName').attr('placeholder', 'Tag');
				        // remove qoute
                removeQoutes();
            }

            if (window.getSelection) {
                if (window.getSelection().empty) { // Chrome
                    window.getSelection().empty();
                } else if (window.getSelection().removeAllRanges) { // Firefox
                    window.getSelection().removeAllRanges();
                }
            } else if (document.selection) { // IE?
                document.selection.empty();
            }

        }


    });

    // function to select Selected text
    function selectHTML() {
        try {
            if (window.ActiveXObject) {
                var c = document.selection.createRange();
                return c.htmlText;
            }
            var nNd = document.createElement("span");
            var w = getSelection().getRangeAt(0);
            w.surroundContents(nNd);
            return nNd.innerHTML;
        } catch (e) {
            if (window.ActiveXObject) {
                return document.selection.createRange();
            } else {
                return getSelection();
            }
        }
    }

    /**
     * Event mouseover
     * Class Name .campaignTag
     * show exits tags in popover user able to delete or update.
     */
    $(document).on('mouseover', campaignTag, function(event) {
        event.preventDefault();
       // getting info of the exits tag
        getInfo($(this));
        var $this = $(this),
            id = $this.attr('data-id'),
            ariadescribedby = $this.attr('aria-describedby'),
            tagName = $this.attr('data-name');
        setTimeout(function() {
            jQuery('.popover').find('.popover-body').find('.tagName').val(tagName.replace("\"", ""));
            jQuery('.popover').find('.popover-body').find('.tagRef').val(id);
        }, 0)
    });
    // getting info of the exits tag
    function getInfo(click) {
        $(campaignTag).not(click).each(function() {
            $(this).popover('dispose');
        });
        // init popover
        $(click).popover({
            trigger: 'click',
            html: true,
            title: 'Update Tag',
            content: $('#update-tag').html(),
            delay: {
                show: "500",
                hide: "100"
            }
        });
        $(click).popover("show");
    }

    // function to  get selected text
    function getSelText() {
        var txt = '';
        if (window.getSelection) {
            txt = window.getSelection();
        }
        else if (document.getSelection){
            txt = document.getSelection();
        }
        else if (document.selection) {
            txt = document.selection.createRange().text;
        }
        else return;

        jQuery('.popover').find('.popover-body').find('#label').val(txt);
    }
    // function to remove "" from string
    function removeQoutes() {
        jQuery(testMail).each(function() {
            string = jQuery(this).html();
            string.replace("\"", "");
            string = string.replace(/\s+/g,' ').trim();
            $(this).html(string)
        })
        jQuery(campaignTag).each(function() {
            string = jQuery(this).html();
            string.replace("\"", "");
            string = string.replace(/\s+/g,' ').trim();
            $(this).html(string)
        })
    }
    // funtion to init popover
    function showPop() {
        $(testMail).popover('dispose');
        $('.unitName').removeAttr('autofocus');
        $('.unitName').focus();
        $(testMail).popover({
            trigger: 'click',
            title: 'Create Tag',
            content: $('#add-new-uom').html()
        })
    }
    // funtion close popover
    function closePOP() {
        $(testMail).popover('dispose');
        $(campaignTag).popover('dispose');
        $(document).find('#tagName').removeClass('is-invalid');
        $(document).find('#tagName').attr('placeholder', 'Tag');
    }


    jQuery(document).on('click','.steps-step-3 a',function() {
      closePOP();
    })
    $(document).on('click', '#removeTag', function() {
        closePOP();

        output.removeClass('over');
        var popId = $(this).parents('.popover').attr("id");
        $("#textDescription").find(testMail).each(function() {
            if ($(this).attr("aria-describedby") == popId) {
                $(this).find('span').css({
                    "background": "white",
                    "font-weight": "normal"
                });
            }
        })
        removeQoutes();
        jQuery(document).find('.indexRow').val('');
        jQuery(document).find('.positionStart').val('');
        jQuery(document).find('.positionEnd').val('');
    });

    jQuery(document).on('click', '#createTag', function() {
        var formData = new FormData();
        var flag = 0;
        $(document).find('label.is-invalid').remove();
        var pop = $(this).closest('.popover').attr('id');

        if (flag == 0) {
            var tagName = $('.popover.show').find('#tagName').val();
            var indexRow = $(document).find('.indexRow').val();
            var positionStart = $(document).find('.positionStart').val();
            var campId = $('input[name="campId"]').val();
            var positionEnd = $(document).find('.positionEnd').val();
            $.ajax({
                url: siteroot + '/add-new-tag',
                type: 'post',
                data: {
                    positionEnd: positionEnd,
                    tagName: tagName,
                    indexRow: indexRow,
                    positionStart: positionStart,
                    campId: campId
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                dataType: "json",
                // beforeSend: function() {
                //     $(".loader_div").show();
                // },
                // complete: function() {
                //     output.removeClass('over');
                // },
                success: function(response) {
                    if (response.success) {

                        output.removeClass('over');
                        tost(response.success_message, 'Success');
                        $("#textDescription").find(testMail).each(function() {
                            if ($(this).attr("aria-describedby") == pop) {
                                var $span = $(this).find("span");
                                $span.replaceWith(function() {
                                    return $('<mark>', {
                                        class      : 'campaignTag',
                                        html       : this.innerHTML,
                                        'data-id'  : (response.refId),
                                        'data-name': (response.newTag),
                                    });
                                });
                            }
                        })

                        closePOP();


                    } else {
                        if (response.error_message) {
                            tost(response.error_message, 'Error');
                        }
                    }

                    if (response.formErrors) {
                        $.each(response.errors, function(index, value) {
                            $("input[name='" + index + "']").parents('.form-group').removeClass('is-invalid');
                            $("input[name='" + index + "']").parents('.form-group').addClass('is-invalid');
                            $("input[name='" + index + "']").after('<label id="' + index + '-error" class="is-invalid" for="' + index + '">' + value + '</label>');

                        });
                    }
                    $(".loader_div").hide();

                },
                error: function(response) {
                    var delayTime = 2000;
                    $.toast({
                        heading: 'Error',
                        text: 'Connection error.',
                        loader: true,
                        loaderBg: '#fff',
                        showHideTransition: 'fade',
                        icon: 'error',
                        hideAfter: delayTime,
                        position: 'top-right'
                    });
                }
            });
        }


    })

    jQuery(document).on('click', '.editContact', function() {
        var ids = $(this).attr('id');
        var flag = 0;
        if (flag == 0) {
            if (ids !='') {
              $.ajax({
                  url: siteroot + '/customer/edit-contact/'+ids,
                  type: 'get',
                  headers: {
                      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                  },
                  dataType: "html",
                  beforeSend: function() {
                      $(".loader_div").show();
                  },
                  complete: function() {
                      $(".loader_div").hide();
                  },
                  success: function(response) {
                      $('#add-new-tag').find('form').html(response);
                      $('#add-new-tag').modal('show');
                      $(".loader_div").hide();
                  },
                  error: function(response) {
                      var delayTime = 2000;
                      $.toast({
                          heading: 'Error',
                          text: 'Connection error.',
                          loader: true,
                          loaderBg: '#fff',
                          showHideTransition: 'fade',
                          icon: 'error',
                          hideAfter: delayTime,
                          position: 'top-right'
                      });
                  }
              });
            }else {
              tost('Please Select Valid Contact','Error');
            }

        }


    })

    $("#customerSearch").autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            jQuery.ajax({
                url: siteroot + "/customer/get-campaignPhone",
                data: {
                    searchKey: request.term,
                    campId : $('input[name="campId"]').val()
                },
                dataType: "json",
                success: function(data) {

                  if(!data.length){
										$.toast({
										    heading             : 'Info',
										    text                : 'Contact Not Found.<a href="javascript:void(0)" class="openModal" data-target="#add-new-tag">Click to add</a>',
										    loader              : true,
										    loaderBg            : '#fff',
										    showHideTransition  : 'fade',
										    icon                : 'info',
										  	hideAfter						: 4000,
										    position            : 'top-right'
										});
                 }else{
                   response(data);
                 }
                }
            })
        },
        select: function(e, ui) {
            var countryId = ui.item.id;
            $("#custId").val(countryId);
            $("#customerSearch").val(ui.item.value);
        },
        focus: function(e, ui) {
            return false;
        }



    });


    $(document).on('click', '.removeContact', function() {
        var id = $(this).attr("id");
        var curContact = $(this);
        $.ajax({
            url: siteroot + '/customer/remove-campaign-contact',
            type: 'post',
            data: {
                id: id
            },
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            dataType: "json",
            beforeSend: function() {
                $(".loader_div").show();
            },
            complete: function() {
                $(".loader_div").hide();
            },
            success: function(response) {
                if (response.success) {
                    curContact.closest("tr").remove();
    				var ll = $(document).find('#tableRowTr tr').length;
    				if (ll == 0) { $('#tableRowTr').html('<tr><td colspan="3">No Contact Found</td></tr>');}
    				if(response.step)
		            {
						for (var i = 1; i <= 4; i++) {
    		                if (i <= response.step) {
    		                  $('i.green.valid'+i).show();
    		                  $('i.blue.invalid'+i).hide();
    		                }else{
    		                  $('i.green.valid'+i).hide();
    		                  $('i.blue.invalid'+i).show();
    		                }
		                }
		            }
                    if(response.showElement)
                    {
                        var showIDs = response.showElement.split(",");
                        $.each(showIDs, function(i, val){ $(val).removeClass('d-none'); });
                    }
                    if(response.hideElement)
                    {
                        var hideIDs = response.hideElement.split(",");
                        $.each(hideIDs, function(i, val){ $(val).addClass('d-none'); });
                    }
                    tost(response.success_message, 'Success');
                } else {
                    if (response.error_message) {
                        tost(response.error_message, 'Error');
                    }
                }
                $(".loader_div").hide();
            },
            error: function(response) {
                var delayTime = 2000;
                $.toast({
                    heading: 'Error',
                    text: 'Connection error.',
                    loader: true,
                    loaderBg: '#fff',
                    showHideTransition: 'fade',
                    icon: 'error',
                    hideAfter: delayTime,
                    position: 'top-right'
                });
            }
        });
    });

    // to update tag name
    $(document).on('click', '#updateTag', function() {
        var tagRef = $('.popover.show').find('.tagRef').val();
        var tagName = $('.popover.show').find('.tagName').val();
        var campId = $('input[name="campId"]').val();
        var curContact = $(this);
        $.ajax({
            url: siteroot + '/add-new-tag',
            type: 'post',
            data: {
                tagRef: tagRef,
                tagName: tagName,

                campId : campId
            },
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            dataType: "json",
            // beforeSend: function() {
            //     $(".loader_div").show();
            // },
            // complete: function() {
            //     $(".loader_div").hide();
            // },
            success: function(response) {
                if (response.success) {
                    jQuery('.campaignTemplate').val(response.template);
                    jQuery(campaignTag+'[data-id="' + response.refId.id + '"]').attr('data-name', response.newTag);
                    // $(".campaignTag").popover('dispose');
                    tost(response.success_message, 'Success');
                    closePOP();
                }else{
                  if (response.formErrors) {
                    tost(response.error_message, 'Error');
                  }

                }
                $(".loader_div").hide();
            },
            error: function(response) {
                var delayTime = 2000;
                tost('Connection error.', 'Error');
            }
        });
    });
    $(document).on('click', '#deleteTag', function() {
        var tagRef = $('.popover.show').find('.tagRef').val();
        var tagName = $('.popover.show').find('.tagName').val();
        var curContact = $(this);
        $.ajax({
            url: siteroot + '/delete-tag',
            type: 'post',
            data: {
                tagRef: tagRef
            },
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            dataType: "json",
            beforeSend: function() {
                $(".loader_div").show();
            },
            complete: function() {
                $(".loader_div").hide();
            },
            success: function(response) {

                if (response.success) {
                    jQuery('.campaignTemplate').html(response.template);
                    $(campaignTag).popover('dispose');
                    // console.log("$('.campaignTag[data-id="+response.refId+"]').contents().unwrap()");
                    $(campaignTag+'[data-id="'+response.refId+'"]').contents().unwrap();
                    tost(response.success_message, 'Success');
                    closePOP();
                    removeQoutes();

                }
                $(".loader_div").hide();
            },
            error: function(response) {
                var delayTime = 2000;
                tost('Connection error.', 'Error');
            }
        });
    });


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
					}, 300);

				}
			};
		}
        $(document).on('click', '.daySts', function(){
            var val = parseInt($(this).attr('data-val'));
            if(val==0){
                $(this).find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
                $(this).attr('data-val',1);
                $(this).next('.sts-input').val(1);
            }else if(val==1){
                $(this).find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
                $(this).attr('data-val',0);
                $(this).next('.sts-input').val(0);
            }
        });
        $(document).on('keypress', '.timepicker', function(e){
            e.preventDefault();
        });
        //reporting page
        $(document).on('click','.leadTabs', function(){
            var tab = $(this).attr('href');
            tab = tab.substr(1);
            $('#tab-input').val(tab);
        });
        $(document).on('click','.lead_filter', function(){
            var filter = $(this).val();
            $('#filter-input').val(filter);
            $('#lead_form').submit();
        });
        $(document).on('click','.input-group-text', function(){
            var dateId = $(this).parent().next('.hasDatepicker').click();
        });


				jQuery(document).on('click','.deleteRecord',function(event) {
					event.preventDefault();
					var cardId = $('input[name="card"]').val();
					localStorage.setItem('cardId',cardId);
					$('#confirm_delete').modal('show');

				})
				jQuery(document).on('click','.deleteCard',function(event) {
					event.preventDefault();
					var cardId = localStorage.getItem('cardId');
					$('#confirm_delete').modal('show');
					if (cardId !='') {
						$.ajax({
							url         : siteroot+'/customer/delete-card',
							type        : "post",
							data        : { 'id':cardId },
							dataType    : "json",
							headers: {
								'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
							},
							beforeSend  : function ()
							{
								$(".loader_div").show();
							},
							complete: function ()
							{
								$(".loader_div").hide();
								$('button[data-dismiss="modal"]').trigger('click');
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
									localStorage.setItem('cardId','');

									var temp = `<div class="col-md-4">
                    <div class="form-group">
                      <label for="cc-number" class="control-label">Card Holder Name </label>
                      <input type="text" class="input-lg form-control" name="newCardHolderName" placeholder="Card Holder Name" required="">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="cc-number" class="control-label">Card number </label>
                      <input id="cc-number" type="tel" class="input-lg form-control cc-number mastercard identified" name="newcardNumber" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required="">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="cc-exp" class="control-label">Card expiry</label>
                      <input id="cc-exp" type="tel" class="input-lg form-control cc-exp" name="newccExpiryMonth" autocomplete="cc-exp" placeholder="•• / ••" required="">
                    </div>

                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="cc-cvc" class="control-label">Card CVC</label>
                      <input id="cc-cvc" type="tel" class="input-lg form-control cc-cvc" name="newcvvNumber" autocomplete="off" placeholder="•••" required="">
                    </div>
                  </div>`;


									jQuery('.formArea').html(temp);

									$('.deleteRecord').hide();

									$.getScript(siteroot+'/assets/frontend/js/jquery.payment.js');
									setTimeout(function() {
										$(document).find('[data-numeric]').payment('restrictNumeric');
									  $(document).find('.cc-number').payment('formatCardNumber');
									  $(document).find('.cc-exp').payment('formatCardExpiry');
									  $(document).find('.cc-cvc').payment('formatCardCVC');
									  $.fn.toggleInputError = function(erred) {
									    this.parent('.form-group').toggleClass('has-error', erred);
									    return this;
									  };
									},200)


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
					}else{
						tost('Error','Invalid Request..');
					}
				})


        jQuery(document).on('keyup','.cc-number',function () {
        	$(document).find('[data-numeric]').payment('restrictNumeric');
        	$(document).find('.cc-number').payment('formatCardNumber');
        	$(document).find('.cc-exp').payment('formatCardExpiry');
        	$(document).find('.cc-cvc').payment('formatCardCVC');
        	$.fn.toggleInputError = function(erred) {
        		this.parent('.form-group').toggleClass('has-error', erred);
        		return this;
        	};
        })


      // check popovers already init or not and
      function showPopover(thiss){
          // removeQoutes();
        // if popover init then add class to over to id #output
          setTimeout(function(){if($(document).find('.popover').length > 0 ){
              output.addClass('over');
          }else{
            // else close popover to handle invalid dom req
              closePOP();
          }},100)


      }

      function closePOP() {
          $(testMail).popover('dispose');
          $(campaignTag).popover('dispose');
          $(testMail+' span').contents().unwrap();
          $(document).find('#tagName').removeClass('is-invalid');
          $(document).find('#tagName').attr('placeholder', 'Tag');
          jQuery(document).find('.indexRow').val('');
          jQuery(document).find('.positionStart').val('');
          jQuery(document).find('.positionEnd').val('');
      }

      $(document).on('click','.tagSpan', function(e){
          var tag         = $(this).attr('data-val');
          var txtarea     = $('.campaignTemplate');
          var cursorPos   = txtarea.prop("selectionStart");
          var text        = txtarea.val();
          var textBefore  = text.substring(0,  cursorPos);
          var textAfter   = text.substring(cursorPos, text.length);
          txtarea.val(textBefore + tag + textAfter);
          setTimeout(function(){   mentionInput();},100);
          e.preventDefault();
      });
    $(document).on('click','.getContacts', function(e){
			var ref_url = $(this).attr('data-url');

			$.ajax({
				type	 : "GET",
				dataType : "html",
				url		 : ref_url,
				data : {campId : $('input[name="campId"]').val()},
				beforeSend  : function () {
					$(".loader_div").show();
				},
				complete: function () {
					$(".loader_div").hide();
				},
				success: function(response)
				{
					$("#campaign-contacts").modal();
					$("#campaign-contacts").find('.modal-body').html(response);
				},
				error:function(response){
					console.log('error');
				}
			})

    });

    $(document).on('click','.checkPlainPrc',function(){
      var Obj         =   $(this);
      var parentCls   =   $(Obj).parents('div.plan-bx');
      var planid      =   $(Obj).attr('data-plainid');
      var payId       =   $(Obj).attr('data-payId');
      var modelData   =   $(Obj).attr('data-mod');
      if(planid != "" && planid > 0 )
      {
        $('.checkPlainPrc').text('Choose Plan');
        $('.checkPlainPrc').removeClass('activeCls');
        $('.plan-bx').removeClass('activeClsse');
        $(this).addClass('activeCls');
        $(this).text('Selected');
        $(parentCls).addClass('activeClsse');
        $('.usedPlainId').val(planid);
        $('.payId').val(payId);
        $('.modelToken').val($.trim(modelData));
      }
    });

    $(document).on('click','.sufflePlan',function(){
      var Obj         =   $(this);
      var parentCls   =   $(Obj).parents('div.plan-bx');
      var planid      =   $(Obj).attr('data-plainid');
      var payId      =   $(Obj).attr('data-payId');
      if(planid != "" && planid > 0 )
      {
        $('.checkPlainPrc').text('Choose Plan');
        $('.checkPlainPrc').removeClass('activeCls');
        $('.plan-bx').removeClass('activeClsse');
        $(this).addClass('activeCls');
        $(this).text('Selected');
        $(parentCls).addClass('activeClsse');
        $('.usedPlainId').val(planid);
        $('.payId').val(payId);
      }
    });
	$(document).on('click','.getLeadData', function(e){
         var ref_url = $(this).attr('data-url');
         var leadId = $(this).attr('data-url');
         $.ajax({
             type     : "GET",
             dataType : "html",
             url      : ref_url,
             // data : {lead_id:leadId},
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


    jQuery(document).on('click','.fa-clipboard',function(eve) {

        var copyText = document.getElementById("campTextId");
        if (copyText) {
          copyText = copyText.innerHTML;
          var fullLink = document.createElement('input');
          document.body.appendChild(fullLink);
          fullLink.value = copyText;
          fullLink.select();
          document.execCommand("copy", false);
          fullLink.remove();
          tost('Campaign email Copied','Success',1500);
        }
    });
    $(document).on('click','.subPlanData', function(){
        var price = $(document).find(".activeCls").closest('.col-md-3').find('.price-dollar').attr('id');
        $("#confirmAmount").text(price);
        $("#confirm-payment-modal").modal('show');
    });
    $(document).on('click','.confirmBtn', function(){
        $("#confirm-payment-modal").modal('hide');
        setTimeout(function(){
            var modelVar = parseInt($('.modelToken').val());
            if(modelVar==1){
                $('#planDegrade').modal('show');
            }else{
                $('#sufflePlan-form').submit();
            }
        },1000);
    });
    $(document).on('submit','#downgradeContacts', function(){
        var ref_url  = $(this).attr('action');
        var planid   = $('.checkPlainPrc').attr('data-plainid');
        var postData = $(this).serialize();
        $.ajax({
            type     : "POST",
            url      : ref_url,
            data     : postData,
            beforeSend  : function () {
                $(".loader_div").show();
            },
            // complete: function () {
            //     $(".loader_div").hide();
            // },
            success: function(response)
            {
                if(response.success)
                {
                    $('#sufflePlan-form').submit();
                }
                else
                {
                    tost(response.error_message,'Error',3000);
                     $(".loader_div").hide();
                }
            },
            error:function(response){
                console.log('error');
            }
        });
        return false;
    });

    // localStorage.setItem('sideClass','');
    $(document).on('click','#sidenavToggler',function () {
      $(document).find('body').hasClass('sidenav-toggled') ? localStorage.setItem('sideClass','sidenav-toggled') : localStorage.setItem('sideClass','');
    })
    var kk = localStorage.getItem('sideClass');
    kk == 'sidenav-toggled' ? $('body').addClass('sidenav-toggled') : '';


});
