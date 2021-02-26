{{-- Users list --}}
<ul class="list-group">
    @foreach ($users as $user)
        <li class="list-group-item mt-3" style="background: none; border: 0">
            <a href="/profiles/{{$user->id}}" 
                class="d-inline-flex align-items-stretch"
            >
                <img src="{{$user->profile->avatar}}" 
                    class="d-flex mr-1 img-fluid img-thumbnail follower-list-img" alt="avatar">
                <p class="follower-list-name">{{$user->name}}</p>
            </a>
        </li>
    @endforeach
</ul>