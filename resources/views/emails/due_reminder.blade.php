@component('mail::message')
# Hello {{ $member->full_name }},

This is a reminder from **POJ Music Club**.

Your current due is **{{ $due }}**.  

Member ID: {{ $member->member_id }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
