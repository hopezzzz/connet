$(document).ready(function ()
{
    var minPhoneLen = 10;
    var maxPhoneLen = 15;
    $.validator.addMethod("noSpace", function(value, element,param)
    {
//      	return value.indexOf(" ") >= 0 && value != "";
          return $.trim(value).length >= param;

    }, "No space please and don't leave it empty"),
    $.validator.addMethod('minStrict', function (value, el, param) {
        return value > param;
    },"Rate should be greater then 0.00");
    /*====================Start login form validation================= */
    var site_url = $('#site_url').val();
    
    jQuery(document).on('click','.next',function(){
                var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url']"),
                isValid = true;
                curStep.hide();
                curStep.next().show();
    });


    $(document.body).on('click', '.signUpNext', function()
    {
        $("#client-signup-form").trigger('submit');
    });
    var formm = $("#client-signup-form");
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
            firstName:
            {
                required: true,
                noSpace: true,
            },
            country:
            {
                required: true,
                noSpace: true,
            },
            email:
            {
                required: true,
                noSpace: true,
                email: true
            },
            phoneNo:
            {
                required: true,
                noSpace: true,
                 minlength: 10,
            },
            password:
            {
                required: true,
                noSpace: true,
                minlength: 6,
            },
            confirmPassword:
            {
                required: true,
                noSpace: true,
                minlength: 6,
                equalTo: '#password'
            },
        },
        messages:
        {
            confirmPassword:
            {
                'equalTo': "Confirm Password not matched."
            },
        },
        submitHandler: function (form)
        {
            var currentStep = 1 ;
      			if ($('#step-1').is(":visible"))
            {
      				current_fs  = $('#step-1');
      				next_fs     = $('#step-2');
              currentStep = 2;
              $('.formStep').val('step1');
      			}
            else if($('#step-2').is(":visible"))
            {
              var noplans   = $('#noplans').val();
              if(noplans != '' && noplans == 1 )
              {
                current_fs  =   $('#step-1');
                next_fs     =   $('#step-2');
                currentStep =   1;
                $('.formStep').val('step2');
              }
              else
              {
                var checkPlainId = $('.usedPlainId').val();
                if(checkPlainId == "")
                {
                  current_fs  =   $('#step-3');
                  next_fs     =   $('#step-2');
                  currentStep =   2;
                }
                else
                {
                  current_fs  =   $('#step-2');
          				next_fs     =   $('#step-3');
                  currentStep =   3;
                }


                $('.formStep').val('step2');
              }
			      }
            else if($('#step-3').is(":visible"))
            {
              current_fs  = $('#step-3');
              next_fs     = $('#step-4');
              currentStep = 4;
              $('.formStep').val('step3');
            }
            formSubmit(form);

       }
    });


    //Ravinder Kaur 01-08-2018
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
              // if(currentPlan){
              //   setTimeout(function () {

              //   jQuery('.usedPlainId').val(currentPlan)
              // },200);
              // }
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

    $("#check-form-email").validate({
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
      }
    },
    messages:
    {
      email: {
        required: "Email is required.",
        email: "Please enter valid email",
      }
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

});

function getPlanInfo(userID, planID, payId)
{
  if( userID != "" && planID != "" && payId !='' )
  {
    $.ajax({
        type: "POST",
        cache       : false,
        url: APP_URL+'/get-plain-info', // This is what I have updated
        data: { 'planid': planID , 'userID': userID , "_token": Sitetoken , 'payId' : payId },
        success: function (response) {
            var json_obj = $.parseJSON(response);
            $(".payment_main_div").html('');
            $(".payment_main_div").html(json_obj.html);
        }
      });
  }else{
    console.log('Invalid Plan Please try another');
  }
}

function formSubmit(form)
{
    $.ajax({
        url         : form.action,
        type        : form.method,
        //data        : $(form).serialize(),
        data        : new FormData(form),
        headers: {
         'X-CSRF-Token': $('meta[name="_token"]').attr('content')
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
        },
        success: function (response) {
            console.log(response);
            $(".loader_div").hide();
            //jQuery('#addAccounting').attr('action',"");
            $("input[type=submit]").removeAttr("disabled");
            $("button[type=submit]").removeAttr("disabled");
            $.toast().reset('all');
            var delayTime = 3000;
            if(response.delayTime)
                delayTime = response.delayTime;
            if (response.success)
            {

               if (typeof next_fs !== 'undefined'){next_fs.show();current_fs.hide();}
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
                        console.log("td[data-name='"+index+"']");
                        $('#tableRow_'+response.data.id).find("td[data-name='"+index+"']").html(value);
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
                if(response.stepType)
                {
                  if(response.stepType == 'firstStep')
                  {
                    $('.userIDCheck').val(response.userID);
                  }
                  if(response.stepType == 'secondStep')
                  {
                    var userID = response.userID;
                    var planID = response.planID;
                    var payId = response.payId;
                    getPlanInfo(userID,planID,payId);
                    $('.userIDCheck').val(userID);
                  }
                  if(response.stepType == 'thirdStep')
                  {
                    var userID      = response.userID;
                    var planAmount  = response.planAmount;
                    var planName    = response.planName;
                    $('.userIDCheck').val(userID);
                    // var Rurl        = APP_URL+'/redirect-paypal/'+planAmount+'/'+planName+'/'+userID;
                    // window.location.href = Rurl;
                  }
                  if(response.stepType == 'fourthStep')
                  {
                    var userID      = response.userID;
                    var planAmount  = response.planAmount;
                    var planName    = response.planName;
                    $('.userIDCheck').val(userID);

                    $('.signUpNext').replaceWith('<button class="btn btn-indigo btn-rounded next" type="button">Next</button>');
                    // var Rurl        = APP_URL+'/redirect-paypal/'+planAmount+'/'+planName+'/'+userID;
                    // window.location.href = Rurl;
                  }
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
            if(response.modelhide)
            {
              $('#add-Category-modal').modal('hide');
              $('#confirm-status-update-modal-chart').modal('hide');
              if( response.status == 1 )
  						{
  							$('#accounting_'+response.categoryRef).find('.statusTd').html('<span class="label label-success">Active</span>');
  							$('#accounting_'+response.categoryRef).find('.updateAccounting').html('Make Inactive').attr('data-status',response.status);
  						}
  						else
  						{
  							$('#accounting_'+response.categoryRef).find('.statusTd').html('<span class="label label-warning">Inactive</span>');
  							$('#accounting_'+response.categoryRef).find('.updateAccounting').html('Make Active').attr('data-status',response.status);
  						}
              var srNo       = jQuery('#add-Category-modal #selectedSrNo').val();
              jQuery('.trSrNo'+srNo).find('.searchExpense').val(response.title);
              jQuery('.trSrNo'+srNo).find('.serviceRef').val(response.subcatRef);
              jQuery('.trSrNo'+srNo).find('.ParentCategoryRef').val(response.ParentCategoryRef);

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


        },
        error:function(response){
            var delayTime = 3000;
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
