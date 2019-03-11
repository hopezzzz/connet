    @include('includes.frontend.head')
    <script type="text/javascript">
         var url="<?php echo url('')?>";
         var APP_URL = {!! json_encode(url('/')) !!} //Ravinder Kaur 01-08-2018
         var Sitetoken = '{{ csrf_token() }}'; //Ravinder Kaur 01-08-2018
    </script>
    @if (Auth::check())
    <script type="text/javascript">
         url="<?php //echo url(request()->route()->getPrefix())?>";
    </script>
    <?php $url=url(request()->route()->getPrefix()); ?>
    @else
    <?php $url=url(''); ?>
    @endif
    @include('includes.frontend.header')
    @yield('css')
    @yield('content')
    @include('includes.frontend.footer')
    @yield('js')
    @include('includes.frontend.loader')
