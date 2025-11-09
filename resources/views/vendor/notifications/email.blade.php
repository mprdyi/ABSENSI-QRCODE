<x-mail::message>
{{-- Header / Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# Ups!
@else
# RESSET PASSWORD
@endif
@endif

{{-- Intro Lines --}}
Kami menerima permintaan reset password untuk akun Anda.

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
Reset Password
</x-mail::button>
@endisset

{{-- Outro Lines --}}
Link reset ini akan kadaluarsa dalam 60 menit.

Jika Anda tidak meminta reset password, tidak perlu melakukan tindakan apapun.

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Terima kasih,<br>
SMAN 9 CIREBON
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Jika Anda kesulitan menekan tombol "Reset Password", salin dan tempel URL berikut ke browser Anda:
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
