<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Giao diện trang đăng nhập & đăng ký
Route::get('/loginregister', function () {
    return view('login_register.index');
});
// Giao diện trang giỏ hàng
Route::get('/cart', function () {
    return view('cart.index', ['cities' => Http::get('https://api.mysupership.vn/v1/partner/areas/province')]);
});
//Giao diện trang quản lý
// Route::get('/admin', function () {
//     return view("admin.");
// });
// Giao diện trang quên mật khẩu
Route::get('/reset_password', function () {
    return view("forget_password.reset_password");
});
// Giao diện trang chủ
Route::get('/', [ProductController::class, 'indexPage'])->middleware('isLoggedIn');
//đăng nhập & đăng ký
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
//giỏ hàng
Route::get('/add_cart', [CartController::class, 'addCart'])->name('add_cart');
Route::get('/remove_cart', [CartController::class, 'removeCart'])->name('remove_cart');
Route::get('/increase_incart', [CartController::class, 'increaseIncart'])->name('increase_incart');
Route::get('/decrease_incart', [CartController::class, 'decreaseIncart'])->name('decrease_incart');
//chi tiết sản phẩm
Route::get('/product_detail/{id}', [ProductController::class, 'getProductDetails'])->name('product_details');
//CRUD sản phẩm
Route::get('/add_product', [ProductController::class, 'addProduct']);
Route::get('/edit_product/{id}', [ProductController::class, 'editProduct']);
Route::get('/delete_product/{id}', [ProductController::class, 'deleteProduct']);
//CRUD danh mục
Route::get('/add_category', [CategoryController::class, 'addCategory']);
Route::get('/edit_category/{id}', [CategoryController::class, 'editCategory']);
Route::get('/delete_category/{id}', [CategoryController::class, 'deleteCategory']);
//CRUD khuyến mãi
Route::get('/add_sale', [SalesController::class, 'addSale']);
Route::get('/edit_sale/{id}', [ProductController::class, 'editSale']);
Route::get('/delete_sale/{id}', [ProductController::class, 'deleteSale']);
//Thánh toán
Route::get('/vnpay/vnpay_return', [CheckoutController::class, 'paymentsResult']);
Route::post('/vnpay/vnpay_payment', [CheckoutController::class, 'vnpayPayment']);
Route::post('/direct_payment', [CheckoutController::class, 'directPayment']);
//Lấy thông tin api
Route::get('/cart/get_district', [CartController::class, 'getDistrict'])->name('getDistrict');
Route::get('/cart/get_ward', [CartController::class, 'getWard'])->name('getWard');
//Xác thực thông tin đăng ký
Route::get('/verification/{token}', [AuthController::class, 'verifyUser']);
//Lọc sản phẩm
Route::get('/filter/search', [ProductController::class, 'filterProducts']);
Route::get('/filter', [ProductController::class, 'filterPage']);
//Quản lý thống kê
Route::get('/admin/manage_statistic', [CategoryController::class, 'manageCategoryPage']);
Route::get('/admin/age_category/add', [CategoryController::class, 'addCategory']);
Route::get('/admin/age_category/edit', [CategoryController::class, 'editCategory']);
Route::get('/admin/age_category/delete', [CategoryController::class, 'deleteCategory']);
//Quản lý sản phẩm
Route::get('/admin/manage_products', [ProductController::class, 'manageProductPage']);
Route::get('/admin/manage_products/add', [ProductController::class, 'addProduct']);
Route::post('/admin/manage_products/upload_file', [ProductController::class, 'uploadFile']);
Route::get('/admin/manage_products/edit', [ProductController::class, 'editProduct']);
Route::get('/admin/manage_products/store', [ProductController::class, 'getProductDetails']);
Route::get('/admin/manage_products/delete', [ProductController::class, 'deleteProduct']);
Route::get('/admin/manage_products/search', [ProductController::class, 'searchProduct']);
Route::get('/admin/manage_products/paginate/{current_page}', [ProductController::class, 'productReload']);
//Quản lý đơn hàng
Route::get('/admin/manage_orders', [OrdersController::class, 'manageOrderPage']);
Route::get('/admin/manage_orders/update_order_status', [OrdersController::class, 'updateOrderStatus']);
Route::get('/admin/manage_orders/search', [OrdersController::class, 'searchOrder']);
Route::get('/admin/manage_orders/paginate/{current_page}/{type}', [OrdersController::class, 'orderReload']);
Route::get('/admin/manage_orders/status/{current_page}/{type}', [OrdersController::class, 'orderReload']);
Route::get('/admin/manage_orders/order_details', [OrdersController::class, 'getOrderDetails']);
//Quản lý khách hàng
Route::get('/admin/manage_accounts', [CategoryController::class, 'manageCategoryPage']);
Route::get('/admin/age_category/add', [CategoryController::class, 'addCategory']);
Route::get('/admin/age_category/edit', [CategoryController::class, 'editCategory']);
Route::get('/admin/age_category/delete', [CategoryController::class, 'deleteCategory']);
//Quản lý thể loại
Route::get('/admin/manage_category', [CategoryController::class, 'manageCategoryPage']);
Route::get('/admin/manage_category/add', [CategoryController::class, 'addCategory']);
Route::get('/admin/manage_category/edit', [CategoryController::class, 'editCategory']);
Route::get('/admin/manage_category/delete', [CategoryController::class, 'deleteCategory']);
Route::get('/admin/manage_category/search', [CategoryController::class, 'searchCategory']);
Route::get('/admin/manage_category/paginate/{current_page}', [CategoryController::class, 'categoryReload']);
//Quản lý giảm giá
Route::get('/admin/manage_sales', [CategoryController::class, 'manageCategoryPage']);
Route::get('/admin/age_category/add', [CategoryController::class, 'addCategory']);
Route::get('/admin/age_category/edit', [CategoryController::class, 'editCategory']);
Route::get('/admin/age_category/delete', [CategoryController::class, 'deleteCategory']);
//Lấy lại mật khẩu
Route::post('/reset_password_request', [PasswordResetController::class, 'resetPasswordRequest']);
Route::get('/create_new_password/{selector}/{token}', [PasswordResetController::class, 'createNewPasswordPage']);
Route::post('/reset_password_handler', [PasswordResetController::class, 'resetPasswordHandler']);
