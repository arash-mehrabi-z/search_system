<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AMZKZ - Show News</title>
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

        <h1>Show News</h1>
        <a href="{{ route('news.index') }}">Back to news index</a>
        <hr>

        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>عنوان</th>
                    <th>توضیحات</th>
                    <th>url</th>
                    <th>تاریخ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $news->id }}
                    </td>
                    <td dir="auto">
                        {{ $news->title_text }}
                    </td>
                    <td dir="auto">
                        {{ $news->description }}
                    </td>
                    <td>
                        <a href="{{ $news->url }}">{{ $news->url }}</a>
                    </td>
                    <td>
                        {{ $news->date }}
                    </td>
                </tr>
            </tbody>
        </table>

        <a href="{{ route('news.edit', ['news' => $news]) }}"> Edit this news</a> <br>
        <form action="{{ route('news.destroy', ['news' => $news]) }}" method="POST">
            @csrf
            @method('DELETE')
            <div><input type="submit" style="font-size: 16px; margin-top: 10px;" name="delete" value="Delete this news"></div>
        </form>


    </div>
</body>
</html>
