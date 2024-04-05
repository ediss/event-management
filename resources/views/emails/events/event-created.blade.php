<x-mail::message>
# Introduction

New Event: {{ $event['name'] }}  has been added

<x-mail::button :url="$url">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
