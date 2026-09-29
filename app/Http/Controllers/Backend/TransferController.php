<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Customer;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Transfer;
use App\Models\TransferItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransferController extends Controller
{
    public function AllTransfer()
    {
        $allData = Transfer::with(['transferItems.product', 'fromWarehouse', 'toWarehouse'])->orderBy('id', 'desc')->get();
        return view('admin.backend.transfer.all_transfer', compact('allData'));
    }
    // End Method 

    public function AddTransfer()
    {
        $warehouses = WareHouse::all();
        return view('admin.backend.transfer.add_transfer', compact('warehouses'));
    }
    // End Method 


    public function StoreTransfer(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $transfer = Transfer::create([
                'date' => $request->date,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'discount' => $request->discount ?? 0,
                'shipping' => $request->shipping ?? 0,
                'status' => $request->status,
                'note' => $request->note,
                'grand_total' => 0,

            ]);

            /// Store Transfer Items & Update Stock 
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['id']);
                $netUnitCost = $productData['net_unit_cost'] ?? $product->price;
                $subtotal = ($netUnitCost * $productData['quantity']) - ($productData['discount'] ?? 0);
                
                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $productData['id'],
                    'net_unit_cost' => $netUnitCost,
                    'stock' => $product->product_qty,
                    'quantity' => $productData['quantity'] ?? 0,
                    'discount' => $productData['discount'] ?? 0,
                    'subtotal' => $subtotal,
                ]);

                // Decrement stock from 'from_warehouse'
                Product::where('id', $productData['id'])
                        ->where('warehouse_id', $request->from_warehouse_id) // Ensure stock is decremented from the correct warehouse
                        ->decrement('product_qty', $productData['quantity'] ?? 0);

                // If the product exists in the 'to_warehouse', increment stock

                $existingProduct= Product::where('id', $productData['id'])
                        ->where('warehouse_id', $request->to_warehouse_id)
                        ->first();
                if ($existingProduct) {
                    $existingProduct->increment('product_qty', $productData['quantity'] ?? 0);
                } else {
                    // If the product doesn't exist in the 'to_warehouse', create a new record
                    $newProduct = Product::create([
                        'name' => $product->name,
                        'code' => $product->code,
                        'category_id' => $product->category_id,
                        'brand_id' => $product->brand_id,
                        'warehouse_id' => $request->to_warehouse_id,
                        'supplier_id' => $product->supplier_id,
                        'price' => $product->price,
                        'stock_alert' => $product->stock_alert,
                        'note' => $product->note,
                        'product_qty' => $productData['quantity'] ?? 0,
                        'status' => $product->status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Copy product images from original product
                    $productImages = ProductImage::where('product_id', $product->id)->get();
                    foreach ($productImages as $originalImage) {
                        // Create new image record for the new product
                        ProductImage::create([
                            'product_id' => $newProduct->id,
                            'image' => $originalImage->image // Reference the same image file
                        ]);
                    }
                }
            }

            DB::commit();

            $notification = array(
                'message' => 'Transfer Completed Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.transfer')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // End Method 

    public function EditTransfer($id)
    {
        $editData = Transfer::with(['transferItems.product','fromWarehouse','toWarehouse'])->findOrFail($id);
        $warehouses = WareHouse::all();
        return view('admin.backend.transfer.edit_transfer', compact('editData', 'warehouses'));
    }

    // //End Method

    public function UpdateTransfer(Request $request, $id)
    {

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $transfer = Transfer::findOrFail($id);

            $transfer->update([
                'date' => $request->date,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'discount' => $request->discount ?? 0,
                'shipping' => $request->shipping ?? 0,
                'status' => $request->status,
                'note' => $request->note,
                'grand_total' => $request->grand_total
            ]);

            /// Get Old Transfer Items 
            $oldTransferItems = TransferItem::where('transfer_id', $transfer->id)->get();

            foreach ($oldTransferItems as $oldItem) {
                Product::find($oldItem->product_id)
                        ->where('warehouse_id', $transfer->from_warehouse_id)
                        ->increment('product_qty', $oldItem->quantity);
                    // Increment from warehouse quantity

                Product::find($oldItem->product_id)
                        ->where('warehouse_id', $transfer->to_warehouse_id)
                        ->decrement('product_qty', $oldItem->quantity);
                    // decrement to warehouse quantity
                
            }

            /// Delete old Transfer Items 
            TransferItem::where('transfer_id', $transfer->id)->delete();

            // loop for new products and insert new transfer items
            foreach ($request->products as $product_id => $productData) {
                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $product_id,
                    'net_unit_cost' => $productData['net_unit_cost'],
                    'stock' => $productData['stock'],
                    'quantity' => $productData['quantity'],
                    'discount' => $productData['discount'] ?? 0,
                    'subtotal' => $productData['subtotal'],
                ]);

                Product::where('id', $product_id)
                        ->where('warehouse_id', $transfer->from_warehouse_id)
                        ->decrement('product_qty', $productData['quantity']);
                    // decrement 'from_warehouse' quantity

                Product::where('id', $product_id)
                        ->where('warehouse_id', $transfer->to_warehouse_id)
                        ->increment('product_qty', $productData['quantity']);
                    // increment 'to_warehouse' quantity
            }

            DB::commit();

            $notification = array(
                'message' => 'Transfer Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.transfer')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // End Method

    public function DetailsTransfer($id)
    {
        $transfer = Transfer::with(['transferItems.product','fromWarehouse','toWarehouse'])->findOrFail($id);
        return view('admin.backend.transfer.details_transfer', compact('transfer'));
    }
    // End Method

    public function DeleteTransfer($id)
    {
        try {
            DB::beginTransaction();
            $transfer = Transfer::findOrFail($id);
            $transferItems = TransferItem::where('transfer_id', $id)->get();

            /// Loop for old transfer items and increment product qty
            foreach ($transferItems as $item) {
                Product::where('id', $item->product_id)
                        ->where('warehouse_id', $transfer->from_warehouse_id)
                        ->increment('product_qty', $item->quantity);
                    // increment 'from_warehouse' quantity

                Product::where('id', $item->product_id)
                        ->where('warehouse_id', $transfer->to_warehouse_id)
                        ->decrement('product_qty', $item->quantity);
                    // increment 'to_warehouse' quantity
                
            }

            // Delete transfer items
            TransferItem::where('transfer_id', $id)->delete();
            // Delete transfer
            $transfer->delete();
            DB::commit();

            $notification = array(
                'message' => 'Transfer Deleted Successfully',
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
