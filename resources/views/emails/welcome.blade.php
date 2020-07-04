@component('mail::message')
# Welcome to blogger

Website where you can read blogs made 
by people or create them yourself for others to read.
Hope you've fun blogging :)

@component('mail::button', ['url' => 'https://2020.blog/blogs'])
Visit Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
