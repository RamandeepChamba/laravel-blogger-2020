<ul id={{isset($isReply) ? "replies-$parentComment->id" : "comments"}} 
    class="list-group ml-5 w-auto">
    @forelse ((isset($isReply) ? $parentComment->replies : $blog->comments) as $comment)
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
        {{-- Reply form on click --}}
        <reply-form-component
            :blog-id={{$blog->id}} 
            :parent-id={{$comment->id}}>
        </reply-form-component>
        {{-- Show replies on click --}}
        @if ($comment->replies->count())
        <replies-component
            :parent-id={{$comment->id}}
            :reply-count={{$comment->replies->count()}}>
        </replies-component>
        @endif
    </li>
    @empty
        <li class="list-group-item">
            Be the first one to comment
        </li>
    @endforelse
</ul>