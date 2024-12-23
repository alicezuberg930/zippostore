<?php

namespace App\Http\Controllers;

use App\Models\import_slip;
use App\Models\import_slip_details;
use App\Models\Product;
use App\Models\supplier;
use Illuminate\Http\Request;

class ImportSlipController extends Controller
{
    public function getAllImportSlips()
    {
        return import_slip::join('employees', 'employee_id', '=', 'employees.id')->join('suppliers', 'supplier_id', '=', 'suppliers.id')->get();
    }

    public function addImportSlip(Request $request)
    {
        $is = null;
        try {
            try {
                $quantity = $request->input('import_quantity');
                $price = $request->input('import_price');
                $is = import_slip::create([
                    'supplier_id' => $request->input('supplier_id'),
                    'employee_id' => session('Employee')['EmployeeID'],
                    'import_date' => date('Y-m-d h:i:s', strtotime($request->input('import_date'))),
                    'total_price' => $quantity * $price
                ]);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Thêm phiếu nhập thất bại', 'status' => 0]);
            }
            try {
                import_slip_details::create([
                    'import_slip_id' => $is->id,
                    'product_id' => $request->input('product_id'),
                    'import_quantity' => $request->input('import_quantity'),
                    'import_price' => $request->input('import_price')
                ]);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Thêm phiếu nhập thất bại', 'status' => 0]);
            }
            $product = Product::find($request->input('product_id'));
            $product->update(['amount' => $product->amount + $quantity]);
            return response()->json(['message' => 'Thêm phiếu nhập thành công', 'status' => 1, 'response' => $this->importSlipReload($request->input('page'))]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Thêm phiếu nhập thất bại', 'status' => 0]);
        }
    }

    public static function getImportSlip($current_page)
    {
        return import_slip::join('employees', 'employee_id', '=', 'employees.id')->join('suppliers', 'supplier_id', '=', 'suppliers.id')->take(10)->skip(($current_page - 1) * 10)->get(['*', 'import_slips.id as isid']);
    }

    public function manageImportSlipPage()
    {
        $authorize = AuthController::tokenCan("import_slips:manage");
        if (session()->has('search')) session()->forget("search");
        return view('admin.import_slips_manager', [
            'Products' => Product::all(['id', 'product_name']),
            'Suppliers' => supplier::all(),
            'Import_slips' => $this->getImportSlip(1),
            'total' => import_slip::all()->count(),
            'currentpage' => 1,
            'authorize' => $authorize
        ]);
    }

    public function importSlipDetailPage($id)
    {
        $ImportSlipDetails = import_slip_details::join('products', 'products.id', '=', 'import_slip_details.product_id')
            ->join('import_slips', 'import_slips.id', '=', 'import_slip_details.import_slip_id')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->where('import_slips.id', '=', $id)->first();
        return view('admin.import_slip_details', ['ImportSlipDetails' => $ImportSlipDetails]);
    }

    public function searchImportSlip(Request $request)
    {
        session()->put('search', $request->input('date'));
        session()->save();
        return $this->importSlipReload($request->input('page'));
    }

    public function importSlipReload($current_page)
    {
        $import_slips = null;
        $total = 0;
        if (session()->has('search') && session()->get('search') != '') {
            $query = import_slip::whereYear('import_date', date('Y', strtotime(session()->get('search'))))
                ->whereMonth('import_date', date('m', strtotime(session()->get('search'))))
                ->whereDay('import_date', date('d', strtotime(session()->get('search'))))
                ->join('employees', 'employee_id', '=', 'employees.id')->join('suppliers', 'supplier_id', '=', 'suppliers.id');
            $total = $query->count();
            $import_slips = $query->take(10)->skip(($current_page - 1) * 10)->get(['*', 'import_slips.id as isid']);
        } else {
            $import_slips = $this->getImportSlip($current_page);
            $total = import_slip::count();
        }
        return view('dynamic_layout.import_slip_reload', ['Import_slips' => $import_slips, 'total' => $total, 'currentpage' => $current_page])->render();
    }
}
