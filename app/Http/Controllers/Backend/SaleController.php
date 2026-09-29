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
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function AllSale()
    {
        $allData = Sale::orderBy('id', 'desc')->get();
        return view('admin.backend.sale.all_sale', compact('allData'));
    }
    // End Method 

    public function AddSale()
    {
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.sale.add_sale', compact('customers', 'warehouses'));
    }
    // End Method 


    public function StoreSale(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $grandTotal = 0;

            $sales = Sale::create([
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

            /// Store Sales Items & Update Stock 
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['id']);
                $netUnitCost = $productData['net_unit_cost'] ?? $product->price;

                if ($netUnitCost === null) {
                    throw new \Exception("Net Unit cost is missing ofr the product id" . $productData['id']);
                }

                $subtotal = ($netUnitCost * $productData['quantity']) - ($productData['discount'] ?? 0);
                $grandTotal += $subtotal;

                SaleItem::create([
                    'sale_id' => $sales->id,
                    'product_id' => $productData['id'],
                    'net_unit_cost' => $netUnitCost,
                    'stock' => $product->product_qty - $productData['quantity'],
                    'quantity' => $productData['quantity'],
                    'discount' => $productData['discount'] ?? 0,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('product_qty', $productData['quantity']);
            }

            $sales->update(['grand_total' => $grandTotal + $request->shipping - $request->discount]);

            DB::commit();

            $notification = array(
                'message' => 'Sales Stored Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.sale')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // End Method 

    public function EditSale($id)
    {
        $editData = Sale::with('saleItems.product')->findOrFail($id);
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.sale.edit_sale', compact('editData', 'customers', 'warehouses'));
    }

    //End Method

    public function UpdateSale(Request $request, $id)
    {

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $sale = Sale::findOrFail($id);

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

            /// Get Old Sale Items 
            $oldSaleItems = SaleItem::where('sale_id', $sale->id)->get();

            /// Loop for old sale items and increment product qty
            foreach ($oldSaleItems as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    $product->increment('product_qty', $oldItem->quantity);
                    // Increment old quantity
                }
            }

            /// Delete old Sale Items 
            SaleItem::where('sale_id', $sale->id)->delete();

            // loop for new products and insert new sale items

            foreach ($request->products as $product_id => $productData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product_id,
                    'net_unit_cost' => $productData['net_unit_cost'],
                    'stock' => $productData['stock'],
                    'quantity' => $productData['quantity'],
                    'discount' => $productData['discount'] ?? 0,
                    'subtotal' => $productData['subtotal'],
                ]);

                /// Update product stock by decrementing new quantity 
                $product = Product::find($product_id);
                if ($product) {
                    $product->decrement('product_qty', $productData['quantity']);
                    // Decrement new quantity
                }
            }

            DB::commit();

            $notification = array(
                'message' => 'Sale Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.sale')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // End Method

    public function DetailsSale($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        return view('admin.backend.sale.details_sale', compact('sale'));
    }
    //End Method

    public function InvoiceSale($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);

        $pdf = Pdf::loadView('admin.backend.sale.invoice_sale', compact('sale'));
        return $pdf->download('sale_invoice_' . $id . '.pdf');
    }
    //End Method
    
    public function DeleteSale($id)
    {
        try {
            DB::beginTransaction();
            $sale = Sale::findOrFail($id);
            $saleItems = SaleItem::where('sale_id', $id)->get();

            // Increment product stock based on sale items
            foreach ($saleItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('product_qty', $item->quantity);
                }
            }

            // Delete sale items
            SaleItem::where('sale_id', $id)->delete();
            // Delete sale
            $sale->delete();
            DB::commit();

            $notification = array(
                'message' => 'Sale Deleted Successfully',
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
