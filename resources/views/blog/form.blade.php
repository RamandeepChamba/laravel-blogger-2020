{{-- Add Blog Form --}}
<form 
	action="/blogs{{ isset($blog->id) ? ('/' . $blog->id) : null }}"
	class="disableButtonForm"
	method="POST">
    @method($method)
    @csrf

    <div class="form-group">
      <label for="blog-title">Title</label>
      <input type="text" class="form-control" id="blog-title"
        value="{{ old('title') ?? $blog->title }}" autofocus
        min="5" name="title" required>
    </div>
    @error('title')
    <div class="alert alert-danger" role="alert">
        {{ $message }}
    </div>
    @enderror
    <div class="form-group">
      <label for="blog-content">Content</label>
      <textarea type="content" class="form-control" 
        id="blog-content" name="content" required>{{ old('content') ?? $blog->content }}</textarea>
    </div>
    @error('content')
    <div class="alert alert-danger" role="alert">
        {{ $message }}
    </div>
    @enderror
		<button type="submit" class="btn btn-primary">
      {{ isset($blog->title) ? 'Edit' : 'Add' }}
    </button>
</form>