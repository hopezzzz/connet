<?php

//Daily Payment
function daily(){

  $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
  $merchant_email = 'pardeep1wayit.com_api1.gmail.com';
  $cancel_return = "http://localhost/pay/index.php";
  $success_return = "http://localhost/pay/success.php";

  ?>
  <form name = "myform" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
  <input type="hidden" name="cmd" value="_xclick-subscriptions">
  <input type = "hidden" name = "business" value = "anujsetia8-facilitator@gmail.com">
  <!--<input type="hidden" name="lc" value="IN">-->
  <input type = "hidden" name = "item_name" value = "Test Payment">
  <input type="hidden" name="no_note" value="1">
  <input type="hidden" name="src" value="1">
  <?php if (!empty($total_cycle)) { ?>
  <input type="hidden" name="srt" value="1">
  <?php } ?>
  <input type="hidden" name="a3" value="5">
  <input type="hidden" name="p3" value="1">
  <input type="hidden" name="t3" value="D">
  <input type="hidden" name="currency_code" value="USD">
  <input type = "hidden" name = "cancel_return" value = "<?php echo $cancel_return ?>">
  <input type = "hidden" name = "return" value = "<?php echo $success_return; ?>">
  <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
  </form>
  <script type="text/javascript">
  document.myform.submit();
  </script>
  <?php
}


//weekly Payment
function weekly(){

  $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
  $merchant_email = 'pardeep1wayit.com_api1.gmail.com';
  $cancel_return = "http://localhost/pay/index.php";
  $success_return = "http://localhost/pay/success.php";

  ?>
  <form name = "myform" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
  <input type="hidden" name="cmd" value="_xclick-subscriptions">
  <input type = "hidden" name = "business" value = "anujsetia8-facilitator@gmail.com">
  <!--<input type="hidden" name="lc" value="IN">-->
  <input type = "hidden" name = "item_name" value = "Test Payment">
  <input type="hidden" name="no_note" value="1">
  <input type="hidden" name="src" value="1">
  <?php if (!empty($total_cycle)) { ?>
  <input type="hidden" name="srt" value="1">
  <?php } ?>
  <input type="hidden" name="a3" value="30">
  <input type="hidden" name="p3" value="1">
  <input type="hidden" name="t3" value="W">
  <input type="hidden" name="currency_code" value="USD">
  <input type = "hidden" name = "cancel_return" value = "<?php echo $cancel_return ?>">
  <input type = "hidden" name = "return" value = "<?php echo $success_return; ?>">
  <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
  </form>
  <script type="text/javascript">
  document.myform.submit();
  </script>
  <?php
}

//Monthly Payment
function monthly(){

  $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
  $merchant_email = 'pardeep1wayit.com_api1.gmail.com';
  $cancel_return = "http://localhost/pay/index.php";
  $success_return = "http://localhost/pay/success.php";

  ?>
  <form name = "myform" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
  <input type="hidden" name="cmd" value="_xclick-subscriptions">
  <input type = "hidden" name = "business" value = "anujsetia8-facilitator@gmail.com">
  <!--<input type="hidden" name="lc" value="IN">-->
  <input type = "hidden" name = "item_name" value = "Test ">
  <input type="hidden" name="no_note" value="1">
  <input type="hidden" name="src" value="1">
  <?php if (!empty($total_cycle)) { ?>
  <input type="hidden" name="srt" value="1">
  <?php } ?>
  <input type="hidden" name="a3" value="100">
  <input type="hidden" name="p3" value="1">
  <input type="hidden" name="t3" value="M">
  <input type="hidden" name="currency_code" value="USD">
  <input type = "hidden" name = "cancel_return" value = "<?php echo $cancel_return ?>">
  <input type = "hidden" name = "return" value = "<?php echo $success_return; ?>">
  <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
  </form>
  <script type="text/javascript">
  document.myform.submit();
  </script>
  <?php
}


//yearly Payment
function yearly(){

  $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
  $merchant_email = 'pardeep1wayit.com_api1.gmail.com';
  $cancel_return = "http://localhost/pay/index.php";
  $success_return = "http://localhost/pay/success.php";

  ?>
  <form name = "myform" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
  <input type="hidden" name="cmd" value="_xclick-subscriptions">
  <input type = "hidden" name = "business" value = "anujsetia8-facilitator@gmail.com">
  <!--<input type="hidden" name="lc" value="IN">-->
  <input type = "hidden" name = "item_name" value = "Test ">
  <input type="hidden" name="no_note" value="1">
  <input type="hidden" name="src" value="1">
  <?php if (!empty($total_cycle)) { ?>
  <input type="hidden" name="srt" value="1">
  <?php } ?>
  <input type="hidden" name="a3" value="1000">
  <input type="hidden" name="p3" value="1">
  <input type="hidden" name="t3" value="Y">
  <input type="hidden" name="currency_code" value="USD">
  <input type = "hidden" name = "cancel_return" value = "<?php echo $cancel_return ?>">
  <input type = "hidden" name = "return" value = "<?php echo $success_return; ?>">
  <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
  </form>
  <script type="text/javascript">
  document.myform.submit();
  </script>
  <?php
}

yearly();

?>
