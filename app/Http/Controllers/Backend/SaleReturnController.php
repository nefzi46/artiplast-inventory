<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleReturnController extends Controller
{
    public function AllSaleReturn()
    {
        $allData = SaleReturn::orderBy('id', 'desc')->get();
        return view('admin.backend.return_sale.all_return_sale', compact('allData'));
    }
    // End Method 

    public function AddSaleReturn()
    {
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.return_sale.add_return_sale', compact('customers', 'warehouses'));
    }
    // End Method 


    public function StoreSaleReturn(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $grandTotal = 0;

            $sales = SaleReturn::create([
                'date' => $request->date,
                'warehouse_id' => $request->warehouse_id,
                'customer_id' => $request->customer_id,
                'discount' => $request->discount ?? 0,
                'shipping' => $request->shipping ?? 0,
                'status' => $request->status,
                'note' => $request->note,
                'grand_total' => 0,
                'paid_amount' => $request->paid_amount,
                'due_amount' => $request->due_amount,

            ]);

            /// Store Sales Return Items & Update Stock 
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['id']);
                $netUnitCost = $productData['net_unit_cost'] ?? $product->price;

                if ($netUnitCost === null) {
                    throw new \Exception("Net Unit cost is missing ofr the product id" . $productData['id']);
                }

                $subtotal = ($netUnitCost * $productData['quantity']) - ($productData['discount'] ?? 0);
                $grandTotal += $subtotal;

                SaleReturnItem::create([
                    'sale_return_id' => $sales->id,
                    'product_id' => $productData['id'],
                    'net_unit_cost' => $netUnitCost,
                    'stock' => $product->product_qty + $productData['quantity'],
                    'quantity' => $productData['quantity'],
                    'discount' => $productData['discount'] ?? 0,
                    'subtotal' => $subtotal,
                ]);

                $product->increment('product_qty', $productData['quantity']);
            }

            $sales->update(['grand_total' => $grandTotal + $request->shipping - $request->discount]);

            DB::commit();

            $notification = array(
                'message' => 'Sales Return Stored Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.sale.return')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // End Method 

    public function EditSaleReturn($id)
    {
        $editData = SaleReturn::with('saleReturnItems.product')->findOrFail($id);
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.return_sale.edit_return_sale', compact('editData', 'customers', 'warehouses'));
    }

    //End Method

    public function UpdateSaleReturn(Request $request, $id)
    {

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $sale = SaleReturn::findOrFail($id);

            $sale->update([
                'date' => $request->date,
                'warehouse_id' => $request->warehouse_id,
                'customer_id' => $request->customer_id,
                'discount' => $request->discount ?? 0,
                'shipping' => $request->shipping ?? 0,
                'status' => $request->status,
                'note' => $request->note,
                'grand_total' => $request->grand_total,
                'paid_amount' => $request->paid_amount,
                'due_amount' => $request->due_amount,
            ]);

            /// Get Old Sale Return Items 
            $oldSaleItems = SaleReturnItem::where('sale_return_id', $sale->id)->get();

            /// Loop for old sale return items and decrement product qty
            foreach ($oldSaleItems as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    $product->decrement('product_qty', $oldItem->quantity);
                    // Increment old quantity
                }
            }

            /// Delete old Sale Return Items 
            SaleReturnItem::where('sale_return_id', $sale->id)->delete();

            // loop for new products and insert new sale return items

            foreach ($request->products as $product_id => $productData) {
                SaleReturnItem::create([
                    'sale_return_id' => $sale->id,
                    'product_id' => $product_id,
                    'net_unit_cost' => $productData['net_unit_cost'],
                    'stock' => $productData['stock'],
                    'quantity' => $productData['quantity'],
                    'discount' => $productData['discount'] ?? 0,
                    'subtotal' => $productData['subtotal'],
                ]);

                /// Update product stock by incrementing new quantity
                $product = Product::find($product_id);
                if ($product) {
                    $product->increment('product_qty', $productData['quantity']);
                    // Increment new quantity
                }
            }

            DB::commit();

            $notification = array(
                'message' => 'Sale Return Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.sale.return')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // End Method

    public function DetailsSaleReturn($id)
    {
        $sale = SaleReturn::with('saleReturnItems.product')->findOrFail($id);
        return view('admin.backend.return_sale.details_return_sale', compact('sale'));
    }
    //End Method

    public function InvoiceSaleReturn($id)
    {
        $sale = SaleReturn::with('saleReturnItems.product')->findOrFail($id);

        $pdf = Pdf::loadView('admin.backend.return_sale.invoice_return_sale', compact('sale'));
        return $pdf->download('return_sale_invoice_' . $id . '.pdf');
    }
    //End Method

    public function DeleteSaleReturn($id)
    {
        try {
            DB::beginTransaction();
            $sale = SaleReturn::findOrFail($id);
            $saleItems = SaleReturnItem::where('sale_return_id', $id)->get();

            // Decrement product stock based on sale return items
            foreach ($saleItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('product_qty', $item->quantity);
                }
            }

            // Delete sale return items
            SaleReturnItem::where('sale_return_id', $id)->delete();
            // Delete sale return
            $sale->delete();
            DB::commit();

            $notification = array(
                'message' => 'Sale Return Deleted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete purchase: ' . $e->getMessage()], 500);
        }
    }
    //End Method
}
