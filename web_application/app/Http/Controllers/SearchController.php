<?php

namespace App\Http\Controllers;

use App\tf_idf;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->q;
        $ttl_seconds = 3600;
        $result = Cache::remember($q, $ttl_seconds, function () use ($q) {
            $command = escapeshellcmd('python3 /home/arash/learning/information_retrieval/search_engine/preprocess_query.py ' . $q);
            $output = shell_exec($command);
            $preprocessed_query_array = json_decode($output);

            $relevant_tokens_tf_idf = tf_idf::all()->whereIn('token', $preprocessed_query_array);

            $news_score = [];
            foreach ($relevant_tokens_tf_idf as $tf_idf) {
                $news_id = $tf_idf->news_id;
                if(array_key_exists($news_id, $news_score)) {
                    $news_score[$news_id] = $news_score[$news_id] + floatval($tf_idf->tf_idf);
                }
                else {
                    $news_score[$news_id] = floatval($tf_idf->tf_idf);
                }
            }

            arsort($news_score);
            $ten_most_relevant = array_slice($news_score, 0, 10, true);
            $ten_most_relvant_ids = array_keys($ten_most_relevant);
            $ten_most_relevant_news = News::all()->whereIn('id', $ten_most_relvant_ids);

            $result = [];
            foreach ($ten_most_relvant_ids as $relevant_id) {
                $relevant_news = $ten_most_relevant_news->where('id', $relevant_id)->first();
                $result[] = [
                    'title_text' => $relevant_news->title_text,
                    'description' => $relevant_news->description,
                    'url' => $relevant_news->url,
                    'date' => $relevant_news->date,
                ];
            }
            return $result;
        });

        return view('result', ['ten_most_relevant_news' => $result]);
    }

}
