@component('mail::message')
# Hi {{ $user->name }} 
## Welcome to blogger

Website where you can read blogs made 
by people or create them yourself for others to read.
Hope you've fun blogging :)

Thanks,<br>
{{ config('app.name') }}
@endcomponent
