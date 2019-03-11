<!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © directconnect.com</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete Modal -->
    <div id="confirm-unsub-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Unsubscribe</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to unsubscribe this plan ?</h6>
                    <div class="form-check">
                            <label>
                              <input type="radio" name="radio" value="0"> <span class="label-text">Cancel immediately</span>
                            </label>
                          </div>
                          <div class="form-check">
                            <label>
                              <input type="radio" name="radio" checked value="1"> <span class="label-text">Cancel at the end of the current period on <span id="uptoDate"></span> </span>
                            </label>
                          </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary confirmUnsubscribed">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Ends -->

    <!-- Delete Modal -->
    <div id="confirm-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to delete this record ?</h6>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary deleteRecordBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Ends -->

    <!-- Confirm Payment Modal -->
    <div id="confirm-payment-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Subscription Change</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h6>Your subscription amount is <span id="confirmAmount"></span>.<br>Are you sure you want to change your subscription plan ?</h6>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary confirmBtn">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirm Payment Ends -->

    <!-- Change Status Modal -->
  <div id="confirm-status-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Confirm Action</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body campStatus">
                  <h6>Are you sure you want to make this record <span class="status"></span>?</h6>
                  <div class="text-right">
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary statusRecordBtn">Save</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Change Status Modal Ends -->

      <script>var siteroot = '{{ URL('/') }}'</script>
    <!-- Bootstrap core JavaScript-->
    <!--script src="{{ asset('assets/customer/js/jquery.min.js')}}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script-->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/7.0.0/mark.min.js"></script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script src="{{ asset('assets/customer/js/jquery.highlight-within-textarea.js')}} "></script>
    <script type="text/javascript">
    function mentionInput() {
      $('.mention').highlightWithinTextarea({
        highlight: [
            {
                highlight: /{([^}]+)}/g,
                className: 'yellow'
            }
        ]
      });
    }
    </script>
    <!-- <script src="{{ asset('assets/customer/js/bootstrap-datepicker.js')}} "></script> -->
    <!-- <script src="{{ asset('assets/customer/js/marked.min.js')}} "></script> -->
    <script src="{{ asset('assets/customer/js/highlight.pack.js')}} "></script>
    <script src="{{ asset('assets/customer/js/bootstrap-suggest.js')}} "></script>

    <script src="{{ asset('assets/admin/js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('assets/customer/js/form-validate.js')}}"></script>
    <script src="{{ asset('assets/customer/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/customer/js/jquery.timepicker.js')}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/customer/js/jquery.easing.min.js')}}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{ asset('assets/customer/js/Chart.min.js')}}"></script>
    <script src="{{ asset('assets/customer/js/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('assets/customer/js/dataTables.bootstrap4.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src=" {{ asset('assets/customer/js/sb-admin.min.js')}} "></script>
    <!-- Custom scripts for this page-->
    <script src="{{ asset('assets/customer/js/sb-admin-datatables.min.js')}}"></script>
    <script src="{{ asset('assets/customer/js/sb-admin-charts.min.js')}}"></script>
    <script src="{{ asset('assets/customer/js/sb-admin-charts.js')}} "></script>
    <script src="{{ asset('assets/customer/js/customer.js')}} "></script>

    <script type="text/javascript">
    jQuery(document).on('click','#custom_paginate li a', function(e){
        e.preventDefault();
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        $.ajax({
            url                : siteroot + '/leads-api-ajax',
            type               : 'post',
            data               : { data: url,campId: id},
            dataType           : 'html',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
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


    function tost(message,type, delay = null) {
      $.toast().reset('all');
      return $.toast({
        heading             : type,
        text                : message,
        loader              : true,
        loaderBg            : '#fff',
        showHideTransition  : 'fade',
        icon                : type.toLowerCase(),
        hideAfter           : delay || 3000,
        position            : 'top-right'
      });
    }

    $('.loader_div').show();
    $(window).on('load', function () {
      setTimeout(function() { $('.loader_div').fadeOut('slow'); },1000)

    });

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
        },
      }).done(function(data){
        $(".recordsTable").html(data);
      });
    });
    </script>
  <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
});


</script>
@if(strpos(url()->current(), 'edit-campaign') )
<script type="text/javascript">
  $(document).ready(function() {
   var navListItems = $('div.setup-panel-3 div a.waves-effect'),
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
            $('.contact-list-media-active').removeClass('contact-list-media-active');
  					$item.parent().addClass('contact-list-media-active');
            $target.find('input:eq(0)').focus();
        }
    });
  })
</script>
@endif
<script>
$(function() {
              $(document).find('.mention').suggest('@', {
                data: function(q) {
                  if (q) {
                  return  $.ajax({
                        dataType: "json",
                        url: siteroot+'/gettags',
                        data:  { q: q , campId : $('input[name="campId"]').val() },
                        success: function(response){
                          $.toast().reset('all');
                          if (response.length === 0) {
                            // $.toast({
                            //     heading             : 'Info',
                            //     text                : 'Tag Not Found.<a href="javascript:void(0)" class="openModal" data-target="#add-new-tag">Click to add</a>',
                            //     loader              : true,
                            //     loaderBg            : '#fff',
                            //     showHideTransition  : 'fade',
                            //     icon                : 'info',
                            //   hideAfter : false,
                            //     position            : 'top-right'
                            // });
                          }
                          return response;
                        }
                      });
                     // var item = $.getJSON<?php if(isset($campData['step'] )  && $campData['step'] ==  4) {?>contact-list-media-active<?php } ?>(siteroot+'/gettags', { q: q });
                     // console.log(item);
                     // if (item.length === 0) {
                     // }
                     // return item;
                  }
                },

                map: function(user) {
                  return {
                    value: $.trim('{'+user.tagName+'}'),
                    text: $.trim(user.tagName)
                  }
                }
              })





  jQuery(document).on('click','.openModal',function(e) {e.preventDefault();var ref = $(this).attr('data-target'); if(ref == '#add-new-tag'){ $('#contact_form').find('input[name="name"]').val($(document).find('#customerSearch').val()) } $(ref).modal('show'); })
  // jQuery(document).on('click','.dropdown .dropdown-menu li a',function(e) {e.preventDefault();
  //   setTimeout(function () {mentionInput();},200);
  // })


 mentionInput()

  })
</script>
<script>
$( function() {
    $( ".datepicker" ).datepicker();
});
$(document).on('change', '#date1', function(){
    var date1 = $(this).val();
    $("#date2").datepicker("option","minDate",date1);
});
$(document).on('click','.calIcon',function(){
  $(this).parent().next().trigger('click').datepicker('show');
});
</script>
<?php
$data = Session()->has('error');
if($data){
  $response = json_decode(Session()->get('error'));
  ?>
  <script>
    tost("<?php echo $response->error_message; ?>","Info",5000);
  </script>
  <?php
}
Session()->forget('error');
?>
</div>
</body>

</html>
