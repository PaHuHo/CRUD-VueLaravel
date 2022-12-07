<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use MeiliSearch\Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    public function vn_str_filter($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }
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
        return View('product');
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
                $options['filter'] = '(price=' . $request->price . ')';
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
            'name.min' => 'Tên phải hơn 5 ký tự',
            'price.min' => 'Vui lòng nhập giá',
            'price.numeric' => 'Giá phải là số',
            'price.gt' => 'Giá phải lớn hơn 0',
        ];
        $this->validate($request, [
            'name' => 'required|min:5',
            'price' => 'required|numeric|gt:0',
        ], $messages);
        $count = Product::all()->count();
        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        if ($request->get('image')) {
            $first = strtoupper(substr($this->vn_str_filter($request->input('name')), 0, 1));
            $count = Product::where('name', 'LIKE', $first . '%')->count();
            $image = $request->get('image');
            $name = ($first . substr("000000000", strlen($count + 1)) . ($count + 1)).'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $resized_img = Image::make($image)->resize(512, 512)->stream();
            Storage::disk('public')->put('product-img/' . $name, $resized_img);
            $product->image = "product-img/" . $name;
        }
        $product->save();
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
            'name.min' => 'Tên phải hơn 5 ký tự',
            'price.min' => 'Vui lòng nhập giá',
            'price.numeric' => 'Giá phải là số',
            'price.gt' => 'Giá phải lớn hơn 0',
        ];
        $this->validate($request, [
            'name' => 'required|min:5',
            'price' => 'required|numeric|gt:0',
        ], $messages);

        $product = Product::find($id);

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        if ($request->get('image')) {
            if (file_exists(public_path(). '/' . $product->image)) {
                @unlink(public_path() . '/' . $product->image,);
            }
            
            $image = $request->get('image');
            $name = $product->id.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $resized_img = Image::make($image)->resize(512, 512)->stream();
            Storage::disk('public')->put("product-img/".$name, $resized_img);
            $product->image =  "product-img/".$name;
        }
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
        if($product->image){
            if (file_exists(public_path(). '/' . $product->image)) {
                @unlink(public_path() . '/' . $product->image,);
            }
        }
        $product->delete();
        return response([
            'message' => 'Xóa thành công'
        ], 200);
    }
}
