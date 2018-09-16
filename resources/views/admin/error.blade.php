@if (count($error) > 0)
    @foreach ($error->all() as $error)
        <p style="color: red">{{ $error }}</p>
    @endforeach
@endif