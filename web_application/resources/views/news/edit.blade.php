<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AMZKZ - Edit News</title>
</head>
<body>
    <div style="width: 1200px; margin: 10px auto">
        @if(isset($message))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>Edit News</h1>
        <a href="{{ route('news.show', ['news' => $news]) }}">Back to news</a>
        <hr>

        <form action="{{ route('news.update', ['news' => $news]) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="title">Title</label>
            <input dir="auto" type="text" style="width: 100%; font-size: 16px;" name="title" value="{{ $news->title_text }}" id="title"><br>
            <label for="title">Body</label>
            <textarea dir="auto" style="width: 100%; font-size: 16px;" name="body" id="" cols="30" rows="10" placeholder="If you don't want to change your body, keep this blank."></textarea><br>
            <label for="title">url</label>
            <input dir="auto" type="text" style="width: 100%; font-size: 16px;" name="url" value="{{ $news->url }}" id="url"><br>
            <div style="text-align: center"><input type="submit" style="font-size: 16px; margin-top: 10px;" name="edit" value="Edit"></div>
        </form>

    </div>
</body>
</html>
