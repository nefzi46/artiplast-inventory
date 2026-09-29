@extends('admin.layouts.master')
@section('admin')

<div class="content">

    <!-- Start Content-->
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">All Transfers</h4>
            </div>

            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <a href="{{ route('add.transfer') }}" class="btn btn-secondary">Add Transfer</a>
                </ol>
            </div>
        </div>

        <!-- Datatables  -->
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">

                    </div><!-- end card header -->

                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Date</th>
                                    <th>From Warehouse</th>
                                    <th>To Warehouse</th>
                                    <th>Product</th>
                                    <th>Stock Transfer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allData as $key=> $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</td>
                                    <td>{{ $item['fromWarehouse']['name'] }}</td>
                                    <td>{{ $item['toWarehouse']['name'] }}</td>
                                    <td>
                                        @foreach ($item->transferItems as $transferItem)
                                        {{ $transferItem->product->name ?? 'N/A' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($item->transferItems as $transferItem)
                                        <h4> <span class="badge text-bg-info">{{ $transferItem->quantity ?? 'N/A' }}</span></h4><br>
                                        @endforeach
                                    </td>

                                    <td>
                                        <a title="Details" href="{{ route('details.transfer',$item->id) }}" class="btn btn-info btn-sm"><span class="mdi mdi-eye-circle mdi-18px"></span></a>
                                        @if (true)
                                        <a title="Edit" href="{{ route('edit.transfer',$item->id) }}" class="btn btn-success btn-sm"><span class="mdi mdi-book-edit mdi-18px"></span></a>
                                        @endif
                                        @if (true)
                                        <a title="Delete" href="{{ route('delete.transfer',$item->id) }}" class="btn btn-danger btn-sm" id="delete"><span class="mdi mdi-delete-circle mdi-18px"></span></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>




    </div> <!-- container-fluid -->

</div> <!-- content -->



@endsection