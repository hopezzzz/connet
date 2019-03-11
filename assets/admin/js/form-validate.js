$(document).ready(function ()
{
    var minPhoneLen = 10;
    var maxPhoneLen = 15;
    $.validator.addMethod("noSpace", function(value, element,param)
    {
//      	return value.indexOf(" ") >= 0 && value != "";
          return $.trim(value).length >= param;

    }, "No space please and don't leave it empty");
     $.validator.addMethod("valueNotEquals", function(value, element, arg){
          return arg !== value;
         }, "Value must not equal arg.");
         $.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
}, "This field is required");
jQuery.validator.addMethod("greaterThanZero", function(value, element) {
    return this.optional(element) || (parseFloat(value) > 0);
}, "* Amount must be greater than zero");
    /*$.validator.addMethod('minStrict', function (value, el, param) {
        return value > param;
    },"Rate should be greater then 0.00");*/
    /*====================Start login form validation================= */
    var site_url = $('#site_url').val();

    $("#login-form").validate({
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

    //forgot Password
    $("#forgotPassword-form").validate({
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
                },
        messages:
                {
                    email: {
                        required: "Email is required.",
                        email: "Please enter valid email",
                    },
                },
        submitHandler: function (form)
        {
            formSubmit(form);
        }
    });

    // register Email
    $("#emailVerification-form").validate({
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
                },
        messages:
                {
                    email: {
                        required: "Email is required.",
                        email: "Please enter valid email",
                    },
                },
        submitHandler: function (form)
        {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $(".loader_div").css("display", "block");
                },
                complete: function () {
                    $(".loader_div").css("display", "none");
                },
                success: function (response) {
                    if (response.success)
                    {
                        $("#successMsg").addClass("alert alert-success");
                        $("#successMsg").removeClass("alert-danger");
                        $("#successMsg").css("display", "block");
                        $("#step-2").css("display", "block");
                        $('#successMsg').html(response.success_message);
                        $("#step-1").css("display", "none");
                        $('#step1').removeClass("btn-success");
                        $('#step2').addClass("btn-success");
                        setTimeout(
                                function ()
                                {
                                    //  window.location = site_url + "register2/";
                                    $("#step-2").css("display", "block");
                                }, 3000);
                        setTimeout(
                                function ()
                                {
                                    //  window.location = site_url + "register2/";
                                   $("#successMsg").css("display", "none");
                                }, 5000);
                    }
                    else if (!response.success)
                    {
                        $("#step2").addClass("invalidMail");
                        $("#successMsg").addClass("alert alert-danger");
                        $("#successMsg").removeClass("alert-success");
                        $("#successMsg").css("display", "block");
                        //$("#successMsg").fadeOut();
                        setTimeout(
                        function ()
                        {
                            //  window.location = site_url + "register2/";
                           $("#successMsg").css("display", "none");
                        }, 4000);
                        $('#successMsg').html(response.error_message);
                    }
                }
            });
        }
    });


    //change Password-form
    $("#changePassword-form").validate({
        errorClass: "has-error",
        highlight: function (element, errorClass) {
            $(element).parents('.form-group').addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass(errorClass);
        },
        rules:
                {
                    oldPassword:
                            {
                                required: true,
                                noSpace: true
                            },
                    newPassword:
                            {
                                required: true,
                                noSpace: true,
                                minlength: 5,
                                // equalTo: '#confirmPassword'
                            },
                    confirmPassword:
                            {
                                required: true,
                                noSpace: true,
                                minlength: 5,
                                equalTo: '#newPassword'
                            },
                },
        messages:
                {
                    oldPassword: "Old Password is required.",
                    newPassword:
                            {
                                'required': "New Password is required.",
                                'minlength': "New Password must contain at least 5 characters.",
                                //'equalTo': "New Password and Confirm Password not mactched."
                            },
                    confirmPassword:
                            {
                                'required': "Confirm Password is required.",
                                'minlength': "Confirm Password must contain at least 5 characters.",
                                'equalTo': "New Password and Confirm Password not mactched."
                            },
                },
        submitHandler: function (form)
        {
            formSubmit(form);
        }
    });

    $(document.body).on('click', '.submitBtn', function()
    {
        $("#region-form").validate({
            errorClass   : "has-error",
            highlight    : function(element, errorClass) {
                $(element).parents('.form-group').addClass(errorClass);
            },
            unhighlight  : function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass(errorClass);
            },
            rules:
            {
                name:
                {
                    required: true,
                    noSpace: true,
                },
                currency:
                {
                    valueNotEquals: "0",
                    noSpace: true
                }
            },
            messages:
            {
                currency: { valueNotEquals: "Please select valid currency" }
            },
            submitHandler: function (form)
            {
                formSubmit(form);
            }
        });
    });

    //Submit Plan Form
    $(document.body).on('click', '.submitBtn', function()
    {

        $("#plan-form").validate({
            errorClass   : "has-error",
            highlight    : function(element, errorClass) {
                $(element).parents('.form-group').addClass(errorClass);
            },
            unhighlight  : function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass(errorClass);
            },
            rules:
            {
                name:
                {
                    required: true,
                    noSpace: true,
                },
                minutes_per_month:
                {
                    required: true,
                    noSpace: true,
                },
                billingType:
                {
                    required: true,
                    notEqual: 0,
                },
                no_of_contacts:
                {
                    required: true,
                    noSpace: true,
                },
                leads_per_month:
                {
                    required: true,
                    noSpace: true,
                },
                duration:
                {
                    required: true,
                    noSpace: true,
                },
                "price[]":
                {
                  required: true,
                  number: true,
                },
                "credit[]":
                {
                  required: true,
                  notEqual : "0",
                  noSpace: true,
                },
                "per_min_cost[]":
                {
                  required: true,
                  notEqual : "0",
                  noSpace: true,
                },
            },
            messages:{
            },
            submitHandler: function (form)
            {
                formSubmit(form);
            }
        });


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
                number: true,
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
                greaterThanZero : true,
      					messages: {
      							required: "This field is required",
                    greaterThanZero: "Credit amount should be greater than 0",
      					},
      			});
      	});
        $(".perMinCostForm").each(function() {
      			$(this).rules('remove');
      			$(this).rules('add', {
      					required: true,
      					noSpace: true,
      					number: true,
                greaterThanZero : true,
      					messages: {
      							required: "This field is required",
      							greaterThanZero: "Cost Per Minute should be greater than 0",
      					},
      			});
      	});

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

    $(document).on('click','input[type="submit"]',function(){
      $('textarea').each(function () {var $textarea = $(this);$textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());});

    // atemplate-form
    $("#template-form").validate({
      errorClass   : "has-error",
      highlight    : function(element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight  : function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        name:
        {
          required: true,
        }
      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }
    });

    })

});

function formSubmit(form)
{
    $.ajax({
        url         : form.action,
        type        : form.method,
        //data        : $(form).serialize(),
        data        : new FormData(form),
        headers     : {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType : false,
        cache       : false,
        processData : false,
        dataType    : "json",
        beforeSend  : function () {
            $("input[type=submit]").prop("disabled", true);
            $("button[type=submit]").prop("disabled", true);
            $(".loader_div").show();
        },
        complete: function () {
            $(".loader_div").hide();
            $("input[type=submit]").prop("disabled", false);
            $("button[type=submit]").prop("disabled", false);
        },
        success: function (response) {
            $(".loader_div").hide();
            //jQuery('#addAccounting').attr('action',"");
            $("input[type=submit]").removeAttr("disabled");
            $.toast().reset('all');
            var delayTime = 3000;
            if(response.delayTime)
                delayTime = response.delayTime;
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


                if (response.modelhide) {
                  if (response.delay)
                      setTimeout(function (){ $(response.modelhide).modal('hide') },response.delay);
                  else
                      $(response.modelhide).modal('hide')
                }
            }
            else
            {
                if( response.formErrors)
                {
                    var i = 0;
                    $.each(response.errors, function( index, value )
                    {
                        if (i == 0) {
                         $("input[name='"+index+"']").focus();
                        }
                        $("input[name='"+index+"']").parents('.form-group').addClass('has-error');
                        $("input[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');

                        $("select[name='"+index+"']").parents('.form-group').addClass('has-error');
                        $("select[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');
                        i++;
                    });
                    $("input[type=submit]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
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
            }
            if(response.url)
            {
                if(response.delayTime)
                    setTimeout(function() { window.location.href=response.url;}, response.delayTime);
                else
                    window.location.href=response.url;
            }
            if (response.reload) {
              console.log('sadf');
                // setTimeout(function(){  location.reload(); }, response.delayTime)
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
