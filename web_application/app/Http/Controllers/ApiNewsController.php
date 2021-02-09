<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\News;
use Illuminate\Http\Request;

class ApiNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::orderBy('id', 'desc')->take(5)->get();

        return ApiResponse::success('', $news);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('news.create');
    }

    public function validate_posted_news($request)
    {
        $validatedData = $request->validate([
            'title' => 'required||max:255',
            'url' => 'required|max:255|url'

        ]);
        return $validatedData;
    }

    public function get_preprocessed_tokens($text)
    {
        $command = escapeshellcmd('python3 /home/arash/learning/information_retrieval/search_engine/preprocess_query.py ' . $text);
        $preprocessed_tokens = shell_exec($command);
        return $preprocessed_tokens;
    }

    public function get_last_five_news()
    {
        $last_five_news = News::orderBy('id', 'desc')->take(5)->get();
        return $last_five_news;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate_posted_news($request);

        $news = new News;
        $news = $this->insert_news_other_columns($news, $request);
        $news = $this->insert_news_body_related_columns($news, $request);
        $news->save();

        return ApiResponse::success('New news has been created.', $news);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        // return view('news.show', ['news' => $news]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        return view('news.edit', ['news' => $news]);
    }

    public function insert_news_body_related_columns($news, $request)
    {
        $body = $request->body;
        $preprocessed_body_json = $this->get_preprocessed_tokens($body);
        $news->body_tokens = $preprocessed_body_json;
        $news->description = mb_substr($body, 0, 255);
        return $news;
    }

    public function insert_news_other_columns($news, $request)
    {
        $title = $request->title;
        $preprocessed_title_json = $this->get_preprocessed_tokens($title);
        $news->title_text = $title;
        $news->title_tokens = $preprocessed_title_json;
        $news->url = $request->url;
        $news->date = jdate()->format('%A, %d %B %y');
        return $news;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        $this->validate_posted_news($request);

        if (!is_null($request->body)) {
            $news = $this->insert_news_body_related_columns($news, $request);
        }

        $news = $this->insert_news_other_columns($news, $request);
        $news->save();

        return ApiResponse::success('The news has been editted.', $news);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();
        return $this->return_to_news_index('The news has been deleted.');

    }

    public function return_to_news_index($message)
    {
        $last_five_news = $this->get_last_five_news();
        return ApiResponse::success($message, $last_five_news);
    }
}
