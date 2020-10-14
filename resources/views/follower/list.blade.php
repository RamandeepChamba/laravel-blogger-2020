{{-- Users list --}}
<ul class="list-group">
    @foreach ($users as $user)
        <li class="list-group-item mt-3" style="background: none; border: 0">
            <a href="/profiles/{{$user->id}}" 
                class="d-inline-flex align-items-stretch"
            >
                <img src="{{$user->profile->avatar}}" 
                    style="{vertical-align: middle;height: 100px;
                        width: 100px;border-radius: 50%;}"
                    class="d-flex mr-1 img-fluid img-thumbnail" alt="avatar">
                <p class="d-flex align-self-center m-0 ml-2 h3">{{$user->name}}</p>
            </a>
        </li>
    @endforeach
</ul>