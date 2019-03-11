$(document).ready(function ()
{

    var minPhoneLen = 10;
    var maxPhoneLen = 15;
    $.validator.addMethod("noSpace", function(value, element,param)
    {
//      	return value.indexOf(" ") >= 0 && value != "";
          return $.trim(value).length >= param;

    }, "No space please and don't leave it empty");
    $.validator.addMethod("valueNotEquals", function(value, element, param) {
return this.optional(element) || value != param;
}, "This field is required"),
    $.validator.addMethod('minStrict', function (value, el, param) {
        return value > param;
    },"Rate should be greater then 0.00"),
    $.validator.addMethod('notStartZero', function (value, el, param) {
        return value.search(/^0/) == -1;
    },"Number should not starts with 0.");
    $.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
}, "This field is required");
    /*====================Start login form validation================= */
    var site_url = $('#site_url').val();

    $(document.body).on('click', '.add-campaign', function(){
        $(".campaignTag").popover('dispose');$(".testMail").popover('dispose');$("#add-campaign-steps").trigger('submit');
    });
    var formm = $("#add-campaign-steps");
    formm.validate({
        errorClass   : "has-error",
        highlight    : function(element, errorClass) {
            $(element).parents('.form-group').addClass(errorClass);
        },
        unhighlight  : function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass(errorClass);
        },
        rules:
        {
          campaignsTitle:
          {
              required: true,
              noSpace: true,
          },
          campaignPhone:
          {
              required: true,
              noSpace: true,
              number: true,
          },
          campaignTemplate:
          {
              required: true,
              noSpace: true,
          },
          campaignCountry:
          {
              required: true,
              noSpace: true,
          },
          availDays:
          {
              required: true,
              noSpace: true,
          },
          availHoursFrom:
          {
              required: true,
              noSpace: true,
          },
          availHoursTo:
          {
              required: true,
              noSpace: true,
          },
          delayTime:
          {
              required: true,
              noSpace: true,
          },
          // breakHoursFrom:
          // {
          //     required: true,
          //     noSpace: true,
          // },
          // breakHoursTo:
          // {
          //     required : function () {
          //       var availFrom = $('#availTimeFrom').val();
          //       var availTo   = $('#availTimeTo').val();
          //
          //       var breakFrom =  $('#breakTimeFrom').val();
          //       var breakTo   =  $('#breakTimeTo').val();
          //       console.log('availTo' +availFrom , availTo  );
          //       console.log('breakTo' +breakFrom , breakTo  );
          //
          //       if ( ( availTo == breakTo) && (breakFrom == availFrom) ) {
          //         return true;
          //       }else{
          //         return true;
          //       }
          //     },
          // },
        },
        submitHandler: function (form)
        {
            var currentStep = 1 ;
            currentStepDiv = '#step-1';
      			if ($('#step-1').is(":visible"))
            {
              if ($('.to_display').is(":visible")) {
                current_fs  = $('#step-1');
        				next_fs     = $('#step-2');
                currentStepDiv = '#step-2';
                $('.campaignStep').val('step1');
              }

      			}
            else if($('#step-2').is(":visible"))
            {
              var noplans   = $('#noplans').val();
              if(noplans != '' && noplans == 1 )
              {
                current_fs  =   $('#step-1');
                next_fs     =   $('#step-2');
                currentStepDiv = '#step-2';
                $('.campaignStep').val('step2');
              }
              else
              {
                var checkPlainId = $('.usedPlainId').val();
                if(checkPlainId == "")
                {
                  current_fs  =   $('#step-3');
                  next_fs     =   $('#step-2');
                  currentStep =   2;
                  currentStepDiv = '#step-2';
                }
                else
                {
                  current_fs  =   $('#step-2');
          				next_fs     =   $('#step-3');
                  currentStep =   3;
                  currentStepDiv = '#step-3';
                }
                $('.campaignStep').val('step2');
              }
			      }
            else if($('#step-3').is(":visible"))
            {
              current_fs  = $('#step-3');
              next_fs     = $('#step-4');
              currentStep = 3;
              currentStepDiv = '#step-4';
              $('.campaignStep').val('step3');
            }
            else if($('#step-4').is(":visible"))
            {
              current_fs  = $('#step-4');
              next_fs     = $('#step-5');
              currentStepDiv = '#step-5';
              currentStep = 3;
              $('.campaignStep').val('step4');
            }
            formSubmit(form);


       }
    });


    //Ravinder Kaur 01-08-2018next_fs
    $(document).on('change','.countryRegions',function(){
      var Obj            = $(this);
      var CurrentVal     = $(Obj).find("option:selected").val();
      if(CurrentVal != '' && CurrentVal > 0 )
      {
        $.ajax({
            type: "POST",
            cache       : false,
            url: APP_URL+'/get-region-plans', // This is what I have updated
            data: { 'regionId': CurrentVal , "_token": Sitetoken },
            success: function (response) {
              var json_obj = $.parseJSON(response);
  						$(".plains_main_div").html('');
  						$(".plains_main_div").html(json_obj.html);
              if(currentPlan){
                setTimeout(function () {
                jQuery('.usedPlainId').val(currentPlan)
              },200);
              }
            }
          })
          .done(function(data) {
          //  alert('done');
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
          //  alert('No response from server');
        });
      }
      else
      {
        $(".plains_main_div").html('');
        $(".plains_main_div").html("<p>Choose country for plans..</p>");
      }
    });

    $(document).on('blur','.checkEmail',function(){
      var Obj       =   $(this);
      var Email     =   $(Obj).val();
      var userID    =   $('.userIDCheck').val();
      if(Email != '' && userID == '')
      {
        $.ajax({
            type: "POST",
            cache       : false,
            url: APP_URL+'/check-user-email', // This is what I have updated
            data: { 'Email': Email , "_token": Sitetoken },
            success: function (response) {
            var json_obj = $.parseJSON(response);
              if(json_obj.html == 1)
              {
                var html = '<label id="emailchkerror" class="has-error" for="email">The email has already been taken.</label>';
                $('.checkEmail').after(html);
                setTimeout(function(){
                  $('#emailchkerror').remove();
                  $('.checkEmail').val('');
                }, 2000);
              }
            }
          });
      }
    });

    $(document).on('click','.checkPlainPrc',function(){

      var Obj         =   $(this);
      var parentCls   =   $(Obj).parents('div.plan-bx');
      var planid      =   $(Obj).attr('data-plainid');
      if(planid != "" && planid > 0 )
      {
        $('.checkPlainPrc').removeClass('activeCls');
        $('.plan-bx').removeClass('activeClsse');
        $(this).addClass('activeCls');
        $(parentCls).addClass('activeClsse');
        $('.usedPlainId').val(planid);
      }
    });

    $(document).on('click','.proceedPaypal',function(){
      var planAmount  =   $('.planAmount').val();
      var planName    =   $('.planName').val();
    });


    $("#login-customer").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        email:
        {
          required: true,
          noSpace: true,
          email: true
        },
        password:
        {
          required: true,
          noSpace: true,
          minlength: 5,
        }
      },
      messages:
      {
        email: {
          required: "Email is required.",
          email: "Please enter valid email",
        },
        password: {
          required: "Password is required.",
          minlength: "Password must contain at least 5 characters.",
        },
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });

    //Call settings
    $("#call_setting_form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:{
        rechargeAmt:
        {

        },
      },
      messages:{

      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });

    //Call settings
    $("#recharge_form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:{

        rechargeAmt:
        {
           valueNotEquals: "0",
        }
      },
      messages:{
        rechargeAmt: { valueNotEquals: "Please select a valid amount" }
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });

    // add-new-tag-form
    $("#add-new-tag-form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        tagName:
        {
          required: true,
          noSpace: true,
        }
      },
      messages:{
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });
    // save-card
    $("#save-card").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        cardHolderName:
        {
          required: true,
          noSpace: true,
        },
        newCardHolderName:
        {
          required: true,
          noSpace: true,
        }
      },
      messages:{
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });
    $("#sufflePlan-form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        choosePlanId:
        {
          required: true,
        }
      },
      messages:{
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });

    // add-contact form
    $("#contact_form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        name:{
          required: true,
          noSpace: true,

        },
        code:{
          required: true,
          noSpace: true,
        },
        number:{
          required: true,
          noSpace: true,
          number: true,
          minlength : 6,
          notStartZero: 0
        },
        email:{
          required: true,
          email: true,
          noSpace: true,
        }
      },
      messages:{
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });


    //update-profile
    $("#profile_form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        fname:{
          required: true,
          noSpace: true,

        },
        phone:{
          required: true,
          noSpace: true,
          number: true,
          minlength : 6
        }
      },
      messages:{
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });

});

function getPlanInfo(userID, planID)
{
  if( userID != "" && planID != "" )
  {
    $.ajax({
        type: "POST",
        cache       : false,
        url: APP_URL+'/get-plain-info', // This is what I have updated
        data: { 'planid': planID , 'userID': userID , "_token": Sitetoken },
        success: function (response) {
            var json_obj = $.parseJSON(response);
            $(".payment_main_div").html('');
            $(".payment_main_div").html(json_obj.html);
        }
      });
  }
}


// change email address
    $("#change_password").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        old_pass:{
          required: true,
          noSpace: true,
        },
        new_pass:{
          required: true,
          noSpace: true,
        },
        new_pass2:{
          required: true,
          noSpace: true,
        }
      },
      messages:{ },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });
    $("#camp-contact-form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {

      },
      messages:{ },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });


function formSubmit(form)
{
    $.ajax({
        url         : form.action,
        type        : form.method,
        //data        : $(form).serialize(),
        data        : new FormData(form),
        headers: {
         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
       },
        contentType : false,
        cache       : false,
        processData : false,
        dataType    : "json",
        beforeSend  : function () {
            $("input[type=submit]").attr("disabled", "disabled");
            $(".loader_div").show();
        },
        complete: function () {
            $(".loader_div").hide();
            $("input[type=submit]").removeAttr("disabled");
            $("button[type=submit]").removeAttr("disabled");
        },
        success: function (response) {
            $(".loader_div").hide();
            $("input[type=submit]").removeAttr("disabled");
            $.toast().reset('all');
            var delayTime = 2000;
            if(response.delayTime)
                delayTime = response.delayTime;


            if(response.step){for (var i = 1; i <= 4; i++) {if (i <= response.step) { $('i.green.valid'+i).show();$('i.blue.invalid'+i).hide();}else{$('i.green.valid'+i).hide();$('i.blue.invalid'+i).show();} } }

            if (response.success)
            {
                $.toast({
                    heading             : 'Success',
                    text                : response.success_message,
                    loader              : true,
                    loaderBg            : '#fff',
                    showHideTransition  : 'fade',
                    icon                : 'success',
                    hideAfter           : delayTime,
                    position            : 'top-right'
                });
                if( response.updateRecord)
                {
                  $.each(response.data, function( index, value )
                  {
                      $(document).find('#tableRow_'+response.data.id).find("td[data-name='"+index+"']").html(value);
                  });
                }
                if( response.addRecord)
                {
                  $.each(response.data, function( index, value )
                  {
                      $("input[name='"+index+"']").parents('.form-group').addClass('has-error');
                      $("input[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');

                      $("select[name='"+index+"']").parents('.form-group').addClass('has-error');
                      $("select[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');
                  });
                }

                if(response.campId){
                  $('.campId').val(response.campId);
                }

                if (response.responseData) {
                  $(document).find('#data').html(response.responseData);
                }

                if (response.modelhide) {
                  if (response.delay)
                      setTimeout(function (){ $(response.modelhide).modal('hide') },response.delay);
                  else
                      $(response.modelhide).modal('hide')
                }

                if (response.tagRequest) {
                    let rep = $('.campaignTemplate').val().split('@');
                    $('.campaignTemplate').val($('.campaignTemplate').val().replace('@'+rep[1], '{'+response.tagRequest+'}'));
                    setTimeout(function () {mentionInput();},200);
                }

                if(response.template){
                  $(document).find('.campaignTemplate').val(response.template);
                  setTimeout(function () {mentionInput();},200);
                }


                if(response.campaign){
                    if (typeof next_fs !== 'undefined'){
                      $('.contact-list-media-active').removeClass('contact-list-media-active');
                      $('a[href="'+currentStepDiv+'"]').parent().addClass('contact-list-media-active')
                      next_fs.show();current_fs.hide();
                    }

                }

                if (response.cardId) {
                  jQuery('.formArea').html(response.formInput);
                  jQuery('.formArea').append('<input type="hidden" name="card" value="'+response.cardId+'" >');
                  $('.deleteRecord').show();
                }

                if (response.campContact) {
                  if (response.tableRow != "") {
                      $(".contact-table tbody").append(response.tableRow);
                      $("#custId").val('');
                      $("#customerSearch").val('');
                      var searchString = 'No Contact Found';
                      $(".contact-table tbody tr td:contains('" + searchString + "')").each(function() {
                        if ($(this).text() == searchString) {
                            $(this).parent().remove();
                        }
                    });
                  }

                 if (!response.updateContact) {
                   $('#camp-contact-form').find('.close').trigger('click');
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

            }
            else
            {
                if( response.formErrors)
                {
                    $.each(response.errors, function( index, value )
                    {

                        $("input[name='"+index+"']").parents('.form-group').addClass('has-error');
                        $("input[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');

                        $("select[name='"+index+"']").parents('.form-group').addClass('has-error');
                        $("select[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');
                    });
                    if( response.tabForm )
                    {
                        $(".setup-content").each(function()
                        {
                            if( $(this).find('.form-group').hasClass('has-error') )
                            {
                                var id = $(this).attr('id');
                                $('.setup-content').hide();
                                $('div[id$="'+id+'"]').show();
                                //$(this).find('.form-group').find('.has-error').siblings('input').focus();
                            }
                        });
                    }
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
                        hideAfter           : delayTime,
                        position            : 'top-right'
                    });
                }
            }

            if(response.ajaxPageCallBack)
            {
                response.formid = form.id;
                ajaxPageCallBack(response);
            }

            if(response.resetform)
            {
                $('#'+form.id).resetForm();
            }
            if(response.submitDisabled)
            {
                  $("input[type=submit]").attr("disabled", "disabled");
                  $("button[type=submit]").attr("disabled", "disabled");
                  $('.subPlanData').attr("disabled", "disabled");
            }
            if(response.url)
            {
                if(response.delayTime)
                    setTimeout(function() { window.location.href=response.url;}, response.delayTime);

                else
                    window.location.href=response.url;
            }
            if (response.reload) {
              if(response.delayTime)
                setTimeout(function(){  location.reload(); }, response.delayTime)
              else
                  location.reload();
            }

            if (response.elementShow) {
                jQuery(response.elementShow).fadeIn();
            }
            if (response.campEmail) {
              jQuery('#campTextId').html(response.campEmail);
              jQuery('.toHideElement').remove();

            }

            if (response.custRef) {
                if (!response.updateContact) {
                  $(document).find('.customerCheckbox').prop( "checked", true );
                  $(document).find('.customerCheckbox').val(response.custRef.custRef);
                  setTimeout(function() { $(document).find('#camp-contact').trigger('click')},200);
                }
            }

            if(response.toCampTags){
              $(document).find('.mb-3').remove();
              $(document).find('.suggest').after(response.toCampTags);
            }
            if(response.sub_id){
              $(document).find('#sub_id').val(response.sub_id);
            }

        },
        error:function(response){
            var delayTime = 2000;
            $.toast({
                heading             : 'Error',
                text                : 'Connection error.',
                loader              : true,
                loaderBg            : '#fff',
                showHideTransition  : 'fade',
                icon                : 'error',
                hideAfter           : delayTime,
                position            : 'top-right'
            });
        }
    });
}
