<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function AllCustomer()
    {
        $customer = Customer::latest()->get();
        return view('admin.backend.customer.all_customer', compact('customer'));
    }

    //End Method

    public function AddCustomer()
    {
        return view('admin.backend.customer.add_customer');
    }
    //End Method 

    public function StoreCustomer(Request $request)
    {
        Customer::create([
           'name' => $request->name,
           'email' => $request->email,
           'phone' => $request->phone,
           'address' => $request->address,
        ]);

        $notification = array(
            'message' => 'Customer Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.customer')->with($notification);
    }
    //End Method

    public function EditCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.backend.customer.edit_customer', compact('customer'));
    }

    //End Method
    public function UpdateCustomer(Request $request)
    {
        $customer_id = $request->id;

        Customer::find($customer_id)->update([
           'name' => $request->name,
           'email' => $request->email,
           'phone' => $request->phone,
           'address' => $request->address,
        ]);

        $notification = array(
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.customer')->with($notification);
    }
    //End Method

    public function DeleteCustomer($id)
    {
        Customer::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    //End Method
}
