 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plan Usage</title>
</head>

<body>
<div style="margin:0 auto;width:600px;padding:30px;border: 1px solid #ddd;">
 <table style="width:100%;">
   <tr>
   	  <td><img style="width:140px;" src="{{ asset('assets/frontend/img/logo-r.png') }}" alt="logo"></td>
   </tr>
     <tr>
   	 <td colspan="2">&nbsp;</td>
   </tr>
   <tr>
   	 <td colspan="2" style="color: #0069d9;line-height: 37px;font-size: 24px;"><b>Dear {{ $name }}, </b></td>
   </tr>
     <tr>
     <td colspan="2"></td>
   </tr>
 </table>
 {!!$content!!}
</div>

</body>
</html>
