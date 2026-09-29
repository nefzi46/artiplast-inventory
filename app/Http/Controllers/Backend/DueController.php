<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WareHouse;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DueController extends Controller
{
    public function DueSale(){
        $sales = Sale::with(['customer', 'warehouse'])
                ->select('id', 'customer_id', 'warehouse_id', 'due_amount')
                ->where('due_amount', '>', 0)
                ->get();
        return view('admin.backend.due.sale_due', compact('sales'));
    }

    public function DueSaleReturn(){
        $sales = SaleReturn::with(['customer', 'warehouse'])
                ->select('id', 'customer_id', 'warehouse_id', 'due_amount')
                ->where('due_amount', '>', 0)
                ->get();
        return view('admin.backend.due.sale_return_due', compact('sales'));
    }
}
