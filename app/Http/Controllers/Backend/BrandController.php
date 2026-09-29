<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BrandController extends Controller
{
    public function AllBrand()
    {
        $brand = Brand::latest()->get();
        return view('admin.backend.brand.all_brand', compact('brand'));
    }

    //End Method

    public function AddBrand()
    {
        return view('admin.backend.brand.add_brand');
    }
    //End Method 

    public function StoreBrand(Request $request)
    {
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(100, 90)->save(public_path('upload/brand/' . $name_gen));
            $save_url = 'upload/brand/' . $name_gen;

            Brand::create([
                'name' => $request->name,
                'image' => $save_url
            ]);

            $notification = array(
                'message' => 'Brand Inserted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.brand')->with($notification);
        }
    }
    //End Method

    public function EditBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.backend.brand.edit_brand', compact('brand'));
    }
    //End Method

    public function UpdateBrand(Request $request)
    {
        $brand = Brand::findOrFail($request->id);

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(100, 90)->save(public_path('upload/brand/' . $name_gen));
            $save_url = 'upload/brand/' . $name_gen;

            if (file_exists(public_path($brand->image))) {
                unlink(public_path($brand->image));
            }

            $brand::find($request->id)->update([
                'name' => $request->name,
                'image' => $save_url
            ]);
            
        } else {
            $brand::find($request->id)->update([
                'name' => $request->name
            ]);
        }

        $notification = array(
            'message' => 'Brand Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.brand')->with($notification);
    }
    //End Method

    public function DeleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        if (file_exists(public_path($brand->image))) {
            unlink(public_path($brand->image));
        }
        $brand->delete();

        $notification = array(
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
