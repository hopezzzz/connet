<footer>
<!-- Essential javascripts for application to work-->
<script>var siteroot = '{{ URL('/') }}'</script>
<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.form.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.toast.js') }}"></script>
<script type="text/javascript">
function toster(message,type,delayTime = null) {
  return $.toast({
    heading             : type,
    text                : message,
    loader              : true,
    loaderBg            : '#fff',
    showHideTransition  : 'fade',
    icon                : type.toLowerCase(),
    hideAfter           : (delayTime != null) ? delayTime  : 2000 ,
    position            : 'top-right'
  });
}
</script>
<script src="{{ asset('assets/admin/js/jquery.validate.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/form-validate.js') }}"></script>
<script src="{{ asset('assets/admin/js/additional-method.js') }}"></script>
<script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/main.js') }}"></script>
<script src="{{ asset('assets/admin/js/common.js') }}"></script>
<!-- The javascript plugin to display page loading on top-->
<!--script src="{{ asset('assets/admin/js/plugins/pace.min.js') }}"></script-->
<!-- Page specific javascripts-->
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/chart.js') }}"></script>
<!-- <script type="text/javascript">
   jQuery(document).on('click','.addModel',function () {
      jQuery('#addModal').modal('show');
    })

</script> -->

<!-- Delete Modal -->
<div id="confirm-unsub-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Unsubscription</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h6>Are you sure you want to unsubscribe this plan ?</h6>
                <div class="text-right">
                    <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary confirmUnsubscribed">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Ends -->
<?php
$data = Session()->has('error');
if($data)
{
    $response = json_decode(Session()->get('error'));
    if($response->success)
    {
    ?>
        <script>
            toster('<?php echo $response->success_message; ?>','Success',2000);
        </script>
    <?php
    }
    else
    {
    ?>
        <script>
            toster('<?php echo $response->error_message; ?>','Error',2000);
        </script>
    <?php
    }
}
Session()->forget('error');
?>
<script>
jQuery(document).on('click','#custom_paginate li a', function(e){
    e.preventDefault();
    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        url                : siteroot + '/leads-api-ajax',
        type               : 'post',
        data               : { data: url,campId: id},
        dataType           : 'html',
        beforeSend : function() {
            $(".loader_div").show();
        },
        complete   : function() {
            $(".loader_div").hide();
        },
        success    : function(response) {
            $('.recordsTable').html(response);
            $(".loader_div").hide();

        }
    });

});
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
CKEDITOR.replace( 'content' );
</script>
</footer>
