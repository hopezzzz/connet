<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/'.config("app.frontendtemplatename").'/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.form.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.toast.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.validate.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/'.config("app.frontendtemplatename").'/js/form-validate.js') }}"></script>
<script src="{{ asset('assets/'.config("app.frontendtemplatename").'/js/jquery.payment.js') }}"></script>
<script src="{{ asset('assets/admin/js/additional-method.js') }}"></script>



<script>
    $(document).ready(function()
    {
        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.next'),
            allPrevBtn = $('.prevBtn');
            allWells.hide();

            // navListItems.click(function(e) {
            // e.preventDefault();
            // var $target = $($(this).attr('href')),
            //     $item = $(this);

            // if (!$item.hasClass('disabled')) {
            //     navListItems.removeClass('btn-indigo').addClass('btn-default');
            //     $item.addClass('btn-indigo');
            //     allWells.hide();
            //     $target.show();
            //     $target.find('input:eq(0)').focus();
            //     }
            // });

        allPrevBtn.click(function() {
            console.log('asdfasdf');
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id")
                prevStepSteps = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");
                curStep.hide();
                curStep.prev().show();
        });

        allNextBtn.click(function() {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url']"),
                isValid = true;
                curStep.hide();
                curStep.next().show();
                
            $(".form-group").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid)
                nextStepWizard.removeAttr('disabled').trigger('click');
        });

        // $('div.setup-panel div a.btn-indigo').trigger('click');
        $('#step-1').show();
        $('#register_confirm').trigger('click');

    });

</script>


@if(strpos(url()->current(), 'success-payment') )
<script type="text/javascript">
  $(document).ready(function() {
   var navListItems = $('div.setup-panel-3 div a'),
    allWells = $('.setup-content-3');
    navListItems.click(function(e) {
        // console.log(';asdfasdfasdfas');
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-info').addClass('btn-pink');
            $item.addClass('btn-info');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

$('.setup-content').hide();
$('#step-4').show();
  })
</script>
@endif


</body>
</html>
