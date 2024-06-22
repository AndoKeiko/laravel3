<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function result(Request $request)
    {
        $post_data = $request->all();
        $data = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($post_data["book_name"]);
        $json = file_get_contents($data);
        $json_decode = json_decode($json, true);

        // itemsが存在しない場合の処理
        if (!isset($json_decode['items'])) {
            $json_decode['items'] = [];
        }

        return view('books', compact("json_decode"));
    }
}