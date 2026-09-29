<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class ProductController extends Controller
{
    public function AllCategory()
    {
        $category = ProductCategory::latest()->get();
        return view('admin.backend.category.all_category', compact('category'));
    }

    //End Method

    public function StoreCategory(Request $request)
    {
        ProductCategory::create([
           'category_name' => $request->category_name,
           'category_slug' => strtolower(str_replace(' ', '-', $request->category_name))
        ]);

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    //End Method

    public function EditCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        return response()->json($category);
    }

    //End Method

    public function UpdateCategory(Request $request)
    {
        ProductCategory::findOrFail($request->cat_id)->update([
           'category_name' => $request->category_name,
           'category_slug' => strtolower(str_replace(' ', '-', $request->category_name))
        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    //End Method

    public function DeleteCategory($id)
    {
        ProductCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    //End Method

    /////Add Product all Methods/////

    public function AllProduct()
    {
        $allData = Product::orderBy('id', 'DESC')->get();
        return view('admin.backend.product.product_list', compact('allData'));
    }
    //End Method

    public function AddProduct()
    {
        $categories = ProductCategory::all();
        $brands = Brand::all();
        $warehouses = WareHouse::all();
        $suppliers = Supplier::all();
        return view('admin.backend.product.add_product', compact('categories', 'brands', 'warehouses', 'suppliers'));
    }
    //End Method

    public function StoreProduct(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'price' => $request->price,
            'stock_alert' => $request->stock_alert,
            'note' => $request->note,
            'product_qty' => $request->product_qty,
            'status' => $request->status,
            'created_at' => now(),
        ]);

        $product_id = $product->id;

        //Multiple Image Upload From Here 
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                 $manager = new ImageManager(new Driver());
                 $name_gen = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
                 $imgs = $manager->read($img);
                 $imgs->resize(150, 150)->save(public_path('upload/productimg/' . $name_gen));
                 $save_url = 'upload/productimg/' . $name_gen;

                 ProductImage::create([
                     'product_id' => $product_id,
                     'image' => $save_url
                 ]);
            }
        }

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.product')->with($notification);
     
    }
    //End Method
    public function EditProduct($id)
    {
        $editData = Product::findOrFail($id);
        $categories = ProductCategory::all();
        $brands = Brand::all();
        $warehouses = WareHouse::all();
        $suppliers = Supplier::all();
        $multiimg = ProductImage::where('product_id', $id)->get();
        return view('admin.backend.product.edit_product', compact('editData', 'categories', 'brands', 'warehouses', 'suppliers', 'multiimg'));
    }
    //End Method
    public function UpdateProduct(Request $request)
    {
        $product_id = $request->id;
        $product = Product::findOrFail($product_id);

        $product->update([
            'name' => $request->name,
            'code' => $request->code,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'price' => $request->price,
            'stock_alert' => $request->stock_alert,
            'note' => $request->note,
            'product_qty' => $request->product_qty,
            'status' => $request->status,
        ]);

            if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                 $manager = new ImageManager(new Driver());
                 $name_gen = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
                 $imgs = $manager->read($img);
                 $imgs->resize(150, 150)->save(public_path('upload/productimg/' . $name_gen));
                 $save_url = 'upload/productimg/' . $name_gen;

                $product->images()->create([
                     'product_id' => $product_id,
                     'image' => $save_url
                 ]);
            }
        }

            if($request->has('remove_image')){
                foreach($request->input('remove_image') as $removeImageId){
                    $image = ProductImage::find($removeImageId);
                    if($image){
                        // Delete the image file from the server
                        if(file_exists(public_path($image->image))){
                            unlink(public_path($image->image));
                        }
                        // Delete the image record from the database
                        $image->delete();
                    }
                }
            }

        
        $notification = array(
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.product')->with($notification);
     
    }

    public function DeleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $images = ProductImage::where('product_id', $id)->get();

        // Delete associated images from the server and database
        foreach ($images as $image) {
            if (file_exists(public_path($image->image))) {
                unlink(public_path($image->image));
            }
            $image->delete();
        }
        //Delete image from ProductImage table
        ProductImage::where('product_id', $id)->delete();
        //Delete product from product table
        $product->delete();

        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    //End Method

    public function DetailsProduct($id)
    {
        $product = Product::findOrFail($id);
        
        return view('admin.backend.product.details_product', compact('product'));
    }
    //End Method
}
