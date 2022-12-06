<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use MeiliSearch\Client;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client('http://localhost:7700', 'masterKey');
        $client->index('products')->updateSortableAttributes([
            'updated_at',
        ]);
        $client->index('products')->updateFilterableAttributes(['price']);
        $client->index('products')->updateSearchableAttributes([
            'name',
        ]);
        return View('welcome');
    }

    public function getList()
    {
        return Product::orderBy('updated_at', 'desc')->paginate(5);
    }

    public function search(Request $request)
    {
        // return Product::Where('name', 'LIKE', '%' . $request->name . '%')->orderBy('updated_at', 'desc')->paginate(5);
        // return Product::Where(function ($q) use ($request) {
        //     $q->when($request->filled('name'), function ($q) use ($request) {
        //         $q->where('name', 'LIKE', '%' . $request->name . '%');
        //     })->when($request->filled('price'), function ($q) use ($request) {
        //         $q->where('price', $request->price);
        //     });
        // })->orderBy('updated_at', 'desc')->paginate(5)->appends($request->except(['page', '_token']));

       return Product::search($request->name, function ($index, $query, $options) use ($request) {
            $options['sort'] = ['updated_at:desc'];
            if (isset($request->price)) {
                $options['filter'] = '(price=' . $request->price.')';
            }

            return $index->search($query, $options);
        })->paginate(5)->appends($request->except(['page', '_token']));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Vui lòng nhập tên',
            'name.min'=> 'Tên phải hơn 5 ký tự',
            'price.min' => 'Vui lòng nhập giá',
            'price.numeric' => 'Giá phải là số',
            'price.gt' => 'Giá phải lớn hơn 0',
        ];
        $this->validate($request, [
            'name' => 'required|min:5',
            'price' => 'required|numeric|gt:0',
        ],$messages);
        $product = Product::create([
            'name'     => $request->input('name'),
            'price'    => $request->input('price'),
        ]);
        return response([
            'product' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'Vui lòng nhập tên',
            'name.min'=> 'Tên phải hơn 5 ký tự',
            'price.min' => 'Vui lòng nhập giá',
            'price.numeric' => 'Giá phải là số',
            'price.gt' => 'Giá phải lớn hơn 0',
        ];
        $this->validate($request, [
            'name' => 'required|min:5',
            'price' => 'required|numeric|gt:0',
        ],$messages);

        $product = Product::find($id);

        $product->name = $request->input('name');
        $product->price = $request->input('price');

        $product->save();

        return response([
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return response([
            'message' => 'Xóa thành công'
        ], 200);
    }
}
