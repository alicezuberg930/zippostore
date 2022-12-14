<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\product;
use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        try {
            product::create($request->all());
            return response()->json(['message' => 'Thêm sản phẩm thành công', 'status' => 1, 'response' => $this->productReload($request->input('page'))]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Thêm sản phẩm thất bại', 'status' => 0]);
        }
    }

    public function editProduct(Request $request)
    {
        try {
            product::findOrfail($request->input('id'))->update($request->all());
            return response()->json(['message' => 'Cập nhật sản phẩm thành công', 'status' => 1, 'response' => $this->productReload($request->input('page'))]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Cập nhật sản phẩm thất bại', 'status' => 0]);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            product::find($request->input('id'))->delete();
            return response()->json(['response' => $this->productReload($request->input('page')), 'message' => 'Xóa sản phẩm thành công', 'status' => 1]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Xóa sản phẩm thất bại', 'status' => 0]);
        }
    }

    public function getHomePageProducts()
    {
        $Products = product::leftjoin('sales', 'products.discount', '=', 'sales.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->orderBy('products.created_at', 'desc')
            ->get(['*', 'products.id as ProductsID', 'sales.id as SaleID']);
        return $Products;
    }

    public function filterProducts(Request $request)
    {
        $category = $request->input('categories');
        $country = $request->input('countries');
        $material = $request->input('materials');
        $firstprice = $request->input('firstprice');
        $lastprice = $request->input('lastprice');
        $current_page = $request->input('page') == null ? 1 : $request->input('page');
        $sort = $request->input('sort') == null ? "ASC" : $request->input('sort');
        $search = $request->input('search') == null ? "" : $request->input("search");
        $query = DB::table('products')
            ->leftjoin('sales', 'products.discount', '=', 'sales.id')
            ->whereIn('category', $category)
            ->whereIn('origin', $country)
            ->whereIn('material', $material)
            ->whereBetween('price', [$firstprice, $lastprice])
            ->where('product_name', 'like', '%' . $search . '%')
            ->orderBy('price', $sort);
        $total = $query->count();
        $products = $query->take(9)->skip(($current_page - 1) * 9)->get(['*', 'products.id as ProductsID', 'sales.id as SaleID']);
        return view('dynamic_layout.filter_reload', compact('products', 'total', 'current_page', 'sort'));
    }

    public function uploadFile(Request $request)
    {
        $generatedImageName = 'image_' . time() . '.' . $request->image->extension();
        $request->image->move(public_path('/image'), $generatedImageName);
        return url('/image') . '/' . $generatedImageName;
    }

    public static function getProducts($current_page)
    {
        return product::take(6)->skip(($current_page - 1) * 6)->get();
    }

    public function searchProduct(Request $request)
    {
        session()->put('search', $request->input('name'));
        session()->save();
        return $this->productReload($request->input('page'));
    }

    public function productReload($current_page)
    {
        $Products = null;
        $total = 0;
        if (session()->has('search') && session()->get('search') != '') {
            $query = product::where('product_name', 'like', '%' . session()->get('search') . '%');
            $total = $query->count();
            $Products = $query->take(5)->skip(($current_page - 1) * 5)->get();
        } else {
            $Products = $this->getProducts($current_page);
            $total = product::all()->count();
        }
        return view('dynamic_layout.product_reload', ["Products" => $Products, "total" => $total, "currentpage" => $current_page])->render();
    }

    public function manageProductPage()
    {
        $authorize = AuthController::tokenCan("products:manage");
        if (session()->has('search')) session()->forget("search");
        return view('admin.products_manager', [
            'Categories' => category::all(),
            'Sales' => sales::all(),
            'Products' => $this->getProducts(1),
            'Materials' => product::select('material')->distinct()->get(),
            'total' => product::all()->count(),
            'currentpage' => 1,
            'authorize' => $authorize
        ]);
    }

    public function getProductDetails($id)
    {
        return product::leftjoin('sales', 'products.discount', '=', 'sales.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->where('products.id', '=', $id)
            ->get(['*', 'products.id as ProductsID', 'categories.id as categoryID', 'sales.id as salesID'])[0];
    }

    public function ProductDetailsPage($id)
    {
        return view("product.product_details", ['product' => $this->getProductDetails($id)]);
    }

    public function indexPage()
    {
        if (session()->has('orders') && session()->has('cart')) {
            session()->forget('orders');
            session()->forget('cart');
        }
        return view("index", ['Products' => $this->getHomePageProducts()]);
    }

    public function filterPage(Request $request)
    {
        $Array = [
            'Caterogies' => category::all(),
            'Materials' => product::select('material')->distinct()->get(),
            'Countries' => product::select('origin')->distinct()->get()
        ];
        $query = '';
        if ($request->input('search_name') != null) {
            $query = product::where('product_name', 'like', '%' . $request->input('search_name') . '%');
            $Array['total'] = $query->count();
            $Array['products'] = $query->get(['*', 'id as ProductsID']);
            $Array['current_page'] = 1;
        }
        return view("product.filter", $Array);
    }
}
