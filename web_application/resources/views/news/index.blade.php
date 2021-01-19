<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AMZKZ - News Index</title>
    <style>
        table {
        border: 1px solid black;
        table-layout: fixed;
        width: 200px;
        }

        .description { width: 500px; }
        .id { width: 100px; }

        th,
        td {
        border: 1px solid black;
        width: 250px;
        overflow: hidden;
        }
    </style>
</head>
<body>
    <div style="width: 1200px; margin: 10px auto">
        <h1>News Index</h1>
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
        <a href="{{ route('home') }}">Back to home page</a>
        <hr>

        <table>
            <thead>
                <tr>
                    <th class="id">id</th>
                    <th>عنوان</th>
                    <th class="description">توضیحات</th>
                    <th>url</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($news as $n)
                    <tr>
                        <td class="id">
                            <a href="{{ route('news.show', ['news' => $n]) }}">{{ $n->id }}</a>
                        </td>
                        <td dir="auto">
                            {{ $n->title_text }}
                        </td>
                        <td dir="auto" class="description">
                            {{ $n->description }}
                        </td>
                        <td>
                            <a href="{{ $n->url }}">{{ $n->url }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('news.create') }}"> Create a new news</a>


    </div>
</body>
</html>
