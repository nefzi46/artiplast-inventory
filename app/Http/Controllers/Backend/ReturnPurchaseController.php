<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\ReturnPurchase;
use App\Models\ReturnPurchaseItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturnPurchaseController extends Controller
{
    public function AllReturnPurchase()
    {
        $allData = ReturnPurchase::OrderBy('id', 'DESC')->get();
        return view('admin.backend.return_purchase.all_return_purchase', compact('allData'));
    }
    //End Method

    public function AddReturnPurchase()
    {
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.return_purchase.add_return_purchase', compact('suppliers', 'warehouses'));
    }

    //End Method

    public function StoreReturnPurchase(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
            'supplier_id' => 'required',
        ]);

        try {

            DB::beginTransaction();
            $grandTotal = 0;

            $purchase = ReturnPurchase::create([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'discount' => $request->discount ?? 0,
                'shipping' => $request->shipping ?? 0,
                'status' => $request->status,
                'note' => $request->note,
                'grand_total' => 0
            ]);

            ///Store Purchase Item and Update Product Stock

            foreach ($request->products as $productData) {
                $product = Product::find($productData['id']);
                $netUnitCost = $productData['net_unit_cost'] ?? $product->price;

                if (($netUnitCost == null)) {
                    throw new \Exception("Net unit cost is missing for product ID: " . $productData['id']);
                }

                $subtotal = ($netUnitCost * $productData['quantity']) - ($productData['discount'] ?? 0);
                $grandTotal += $subtotal;

                ReturnPurchaseItem::create([
                    'return_purchase_id' => $purchase->id,
                    'product_id' => $productData['id'],
                    'quantity' => $productData['quantity'],
                    'net_unit_cost' => $netUnitCost,
                    'stock' => $product->product_qty - $productData['quantity'],
                    'quantity' => $productData['quantity'],
                    'discount' => $productData['discount'] ?? 0,
                    'subtotal' => $subtotal
                ]);
                // Update Product Stock
                $product->decrement('product_qty', $productData['quantity']);
            }

            // Update Grand Total in Purchase
            $purchase->update(['grand_total' => $grandTotal + ($request->shipping ?? 0) - ($request->discount ?? 0)]);
            DB::commit();
            $notification = array(
                'message' => 'Return Purchase Stored Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.return.purchase')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to store return purchase: ' . $e->getMessage()], 500);
        }
    }
    //End Method

    public function EditReturnPurchase($id)
    {
        $editData = ReturnPurchase::with('purchaseItems.product')->findOrFail($id);
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.return_purchase.edit_return_purchase', compact('editData', 'suppliers', 'warehouses'));
    }

    //End Method

    public function UpdateReturnPurchase(Request $request, $id)
    {

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $purchase = ReturnPurchase::findOrFail($id);

            $purchase->update([
                'date' => $request->date,
                'warehouse_id' => $request->warehouse_id,
                'supplier_id' => $request->supplier_id,
                'discount' => $request->discount ?? 0,
                'shipping' => $request->shipping ?? 0,
                'status' => $request->status,
                'note' => $request->note,
                'grand_total' => $request->grand_total,
            ]);

            /// Get Old ReturnPurchase Items 
            $oldPurchaseItems = ReturnPurchaseItem::where('return_purchase_id', $purchase->id)->get();

            /// Loop for old ReturnPurchase items and increment product qty
            foreach ($oldPurchaseItems as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    $product->increment('product_qty', $oldItem->quantity);
                    // increment old quantity
                }
            }

            /// Delete old ReturnPurchase Items 
            ReturnPurchaseItem::where('return_purchase_id', $purchase->id)->delete();

            // loop for new products and insert new ReturnPurchase items

            foreach ($request->products as $product_id => $productData) {
                ReturnPurchaseItem::create([
                    'return_purchase_id' => $purchase->id,
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
                'message' => 'Return Purchase Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.return.purchase')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // End Method 

    public function DeleteReturnPurchase($id)
    {
        try {
            DB::beginTransaction();
            $purchase = ReturnPurchase::findOrFail($id);
            $purchaseItems = ReturnPurchaseItem::where('return_purchase_id', $id)->get();

            // Increment product stock based on purchase items
            foreach ($purchaseItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('product_qty', $item->quantity);
                }
            }

            // Delete purchase items
            ReturnPurchaseItem::where('return_purchase_id', $id)->delete();
            // Delete purchase
            $purchase->delete();
            DB::commit();

            $notification = array(
                'message' => 'Return Purchase Deleted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete return purchase: ' . $e->getMessage()], 500);
        }
    }
    //End Method

    public function DetailsReturnPurchase($id)
    {
        $purchase = ReturnPurchase::with('purchaseItems.product')->findOrFail($id); //Here purchaseItems is the function name in the ReturnPurchase model, not the Model PurchaseItem
        return view('admin.backend.return_purchase.details_return_purchase', compact('purchase'));
    }
    //End Method

    public function InvoiceReturnPurchase($id)
    {
        $purchase = ReturnPurchase::with('purchaseItems.product')->findOrFail($id);

        $pdf = Pdf::loadView('admin.backend.return_purchase.invoice_return_purchase', compact('purchase'));
        return $pdf->download('return_purchase_invoice_' . $id . '.pdf');
    }
    //End Method

}
