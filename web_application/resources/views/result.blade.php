<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AMZKZ - Results</title>
</head>
<body>
    <div style="width: 600px; margin: 10px auto">
        <h1>Results</h1>
        <a href="{{ route('home') }}">Back to home page</a>
        <hr>
        @foreach ($ten_most_relevant_news as $relevant_news)
            <div dir="auto">
                <a href="{{ $relevant_news['url'] }}">{{ $relevant_news['title_text'] }}</a><br>
                {{ $relevant_news['description'] }} <br>
                {{ $relevant_news['date'] }}
            </div>
            <hr>
        @endforeach

        @if (empty($ten_most_relevant_news))
            <div>
                No Results Found.
            </div>
        @endif

    </div>
</body>
</html>
