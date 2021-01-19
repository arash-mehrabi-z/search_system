<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AMZKZ - Insert News</title>
</head>
<body>
    <div style="width: 600px; margin: 10px auto">
        @if(isset($message))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        <h1>Create a new news</h1>
        <a href="{{ route('home') }}">Back to home page</a>
        <hr>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('news.store') }}" method="POST">
            @csrf
            <label for="title">Title</label>
            <input dir="auto" type="text" style="width: 100%; font-size: 16px;" name="title" value="" id="title"><br>
            <label for="title">Body</label>
            <textarea dir="auto" style="width: 100%; font-size: 16px;" name="body" id="" cols="30" rows="10"></textarea><br>
            <label for="title">url</label>
            <input dir="auto" type="text" style="width: 100%; font-size: 16px;" name="url" value="" id="url"><br>
            <div style="text-align: center"><input type="submit" style="font-size: 16px; margin-top: 10px;" name="create" value="Create"></div>
        </form>
    </div>
</body>
</html>
