<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator; //この2行を追加！
use Illuminate\Support\Facades\Auth;      //この2行を追加！

class BookController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $books = Book::where('user_id',Auth::user()->id)->orderBy('created_at', 'asc')->paginate(3);
    return view('books', [
        'books' => $books
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //バリデーション
    $validator = Validator::make($request->all(), [
      'item_name' => 'required|min:3|max:255',
      'item_number' => 'required | min:1 | max:3',
      'item_amount' => 'required | max:6',
      'published'   => 'required',
 ]);

 //バリデーション:エラー 
 if ($validator->fails()) {
     return redirect('/')
         ->withInput()
         ->withErrors($validator);
 }
 //以下に登録処理を記述（Eloquentモデル）

// Eloquentモデル
$books = new Book;
$books->user_id  = Auth::user()->id; //追加のコード
$books->item_name   = $request->item_name;
$books->item_number = $request->item_number;
$books->item_amount = $request->item_amount;
$books->published   = $request->published;
$books->save(); 
return redirect('/');
  }

  /**
   * Display the specified resource.
   */
  public function show(Book $book)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($book_id)
  {
    $books = Book::where('user_id',Auth::user()->id)->find($book_id);
    return view('booksedit', ['book' => $books]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Book $book)
  {
        //バリデーション
        $validator = Validator::make($request->all(), [
          'id' => 'required',
          'item_name' => 'required|min:3|max:255',
          'item_number' => 'required|min:1|max:3',
          'item_amount' => 'required|max:6',
          'published' => 'required',
     ]);
     //バリデーション:エラー
      if ($validator->fails()) {
          return redirect('/booksedit/'.$request->id)
              ->withInput()
              ->withErrors($validator);
     }
     
     //データ更新
     $books = Book::where('user_id',Auth::user()->id)->find($request->id);
     $books->item_name   = $request->item_name;
     $books->item_number = $request->item_number;
     $books->item_amount = $request->item_amount;
     $books->published   = $request->published;
     $books->save();
     return redirect('/');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Book $book)
  {
    $book->delete();       //追加
    return redirect('/');  //追加
  }
}

