<script>
    @if(session()->has('flash_notification.message'))
        new PNotify({
        title: '{!! session('flash_notification.message') !!}',
        type: 'success'
    });
    @endif
</script>