<?php

namespace App\Http\Controllers;

use App\Imports\Booksimport;
use App\Exports\BooksExport;
use App\Models\Book;
use App\Models\Bookshelf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function index():View{
        $data['books'] = Book::paginate(10);
        return view('books.index', $data);
    }
    public function create(){
        $data['bookshelves'] = Bookshelf::pluck('name', 'id');
        return view('books.create', $data);
    }
    public function store(Request $request){
        $validate = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|max:2077',
            'publisher' => 'required|max:255',
            'city' => 'required|max:50',
            'cover' => 'required',
            'bookshelf_id' => 'required|max:5'
        ]);
        if($request->hasFile('cover')){
            $path = $request->file('cover')->storeAs(
                'public/cover_buku',
                'cover_buku_'.time() . '.' . $request->file('cover')->extension()
            );
            $validate['cover'] = basename($path);
        }
        $book = Book::create($validate);
        if($book){
            $notification[] = array(
                'message' => 'data buku berhasil disimpan',
                'alert-type' => 'success'
            );
        }else{
            $notification[] = array(
                'message' => 'data buku gagal disimpan',
                'alert-type' => 'error'
            );
        }
        return redirect()->route('book')->with($notification);
    }
    public function edit(string $id){
        $data['book'] = Book::findOrFail($id);
        $data['bookshelves'] = Bookshelf::pluck('name', 'id');
        return view('books.edit', $data);
    }
    public function update(Request $request, string $id){
        $book = Book::findOrFail($id);
        $validate = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|max:2077',
            'publisher' => 'required|max:255',
            'city' => 'required|max:50',
            'cover' => 'required',
            'bookshelf_id' => 'required|max:5'
        ]);
        if($request->hasFile('cover')){
            if($book->cover != null){
                Storage::delete('public/cover_buku/'.$request->old_cover);
            }
            $path = $request->file('cover')->storeAs(
                'public/cover_buku',
                'cover_buku_'.time() . '.' . $request->file('cover')->extension()
            );
            $validate['cover'] = basename($path);
        }
        $book->update($validate);
        if($book){
            $notification = array(
                'message' => 'data buku berhasil disimpan',
                'alert-type' => 'success'
            );
        }else{
            $notification = array(
                'message' => 'data buku gagal disimpan',
                'alert-type' => 'error'
            );
        }
        return redirect()->route('book')->with($notification);
    }
    public function destroy(string $id){
        $book = Book::findOrFail($id);
        Storage::delete('public/cover_buku/'.$book->old_cover);
        $book->delete();
        $notification = array(
            'message' => 'data buku berhasil dihapus',
            'alert-type' => 'success'
        );
        return redirect()->route('book')->with($notification); 
    }

    public function print(){
        $data['books'] = Book::with('bookshelf')->get();
        $pdf = Pdf::loadView('books.print', $data);
        return $pdf->download('ListBuku.pdf');
    }

    public function export(){
        return Excel::download(new BooksExport, 'DataBuku.xlsx');
    }

    public function import(Request $request){
        $request->validate(['file' => 'required|mimes:xlsx, xls']);
        Excel::import(new BooksImport, $request->file('file'));
        $notification = array(
            'message' => 'data buku berhasil disimpan',
            'alert-type' => 'success'
        );
        return redirect()->route('book')->with($notification);
    }
}
