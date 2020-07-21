{{-- Add Comment form --}}
<form action="/comments" method="POST">
    @csrf
    <div class="form-group">
        <textarea class="form-control" 
            aria-describedby="commentHelp" 
            placeholder="Write your opinion on this"
            name='comment'
            cols="70"
            maxlength="300" minlength="5" required
        >{{old('comment')}}</textarea>

        <input type="hidden" name="blog_id" value={{$blog->id}}>

        @error('comment')
        <p class="text text-danger">{{$message}}</p>
        @enderror

        <small id="commentHelp" class="form-text text-muted">
            Can only use upto 300 characters
        </small>
    </div>
    <button type="submit" class="btn btn-primary">Comment</button>
</form>