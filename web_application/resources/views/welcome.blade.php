<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AMZKZ Search Engine</title>
</head>
<body>
    <div style="width: 600px; margin: 10px auto">
        <h1 style="text-align: center">Search the Urmia University's News with AMZKZ Search Engine</h1>
        <form action="{{ route('search') }}" method="GET">
            <input type="text" style="width: 100%; font-size: 16px;" name="q" value="" id="q" placeholder="Search the Urmia University's News">
            <div style="text-align: center"><input type="submit" style="font-size: 16px; margin-top: 10px;" name="search" value="Search"></div>
        </form>

    </div>
</body>
</html>
