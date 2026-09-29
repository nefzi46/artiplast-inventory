@extends('admin.layouts.master')
@section('admin')

<div class="content">

    <!-- Start Content-->
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Product Details</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <a href="{{ route('all.product') }}" class="btn btn-dark">Back</a>
                </ol>
            </div>
        </div>

        <hr>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Product Images -->
                    <div class="col-md-5">
                        <h5 class="mb-3">Product Images</h5>
                        <div class="d-flex flex-wrap gap-3">
                            @forelse ($product->images as $img)
                            <div>
                                <img src="{{ asset($img->image) }}" alt="Product Image" class="me-2 mb-2" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            @empty
                            <p class="text-danger">No images available.</p>
                            @endforelse

                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-md-7">
                        <h5 class="mb-3">Product Details</h5>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Name:</strong> {{ $product->name }}</li>
                            <li class="list-group-item"><strong>Code:</strong> {{ $product->code }}</li>
                            <li class="list-group-item"><strong>Warehouse:</strong> {{ $product->warehouse->name }}</li>
                            <li class="list-group-item"><strong>Supplier:</strong> {{ $product->supplier->name }}</li>
                            <li class="list-group-item"><strong>Category:</strong> {{ $product->category->category_name }}</li>
                            <li class="list-group-item"><strong>Brand:</strong> {{ $product->brand->name }}</li>
                            <li class="list-group-item"><strong>Price:</strong> ${{ $product->price }}</li>
                            <li class="list-group-item"><strong>stock_alert:</strong> {{ $product->stock_alert }}</li>
                            <li class="list-group-item"><strong>Product Quantity:</strong> {{ $product->product_qty }}</li>
                            <li class="list-group-item"><strong>Status:</strong> {{ $product->status }}</li>
                            <li class="list-group-item"><strong>Note:</strong> {{ $product->note }}</li>
                            <li class="list-group-item"><strong>Created on:</strong> {{ \Carbon\Carbon::parse($product->created_at)->format('d M Y') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection