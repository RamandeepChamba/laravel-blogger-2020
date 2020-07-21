<ul id="comments"  class="list-group ml-5">
    @forelse ($blog->comments as $comment)
    <li class="list-group-item">
        <a href="#">{{$comment->user->name}}</a>
        <p>{{$comment->comment}}</p>

        @auth
        @if(auth()->user()->id === $comment->user_id)
        <form action="/comments/{{ $comment->id }}/edit" method="GET">
            @csrf
            <button type="submit" class="btn btn-secondary">Edit</button>
        </form>
        <form action="/comments/{{ $comment->id }}" method="POST">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        @endif
        @endauth
        <form action="/like" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Like</button>
        </form>
    </li>
    @empty
        <li class="list-group-item">
            <div class="alert alert-primary" role="alert">
                No comments here
            </div>
        </li>
    @endforelse
</ul>