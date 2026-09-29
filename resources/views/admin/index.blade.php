@extends('admin.layouts.master')
@section('admin')

<div class="content">

    <!-- Start Content-->
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Dashboard Overview</h4>
            </div>
            <div class="text-end">
                <span class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</span>
            </div>
        </div>

        <!-- start row -->
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="row g-3">

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1 text-muted">Total Products</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-black">{{ \App\Models\Product::count() }}</div>
                                    <div class="me-auto">
                                        <span class="badge bg-primary-subtle text-primary">
                                            <i data-feather="package" class="me-1" style="height: 14px; width: 14px;"></i>
                                            Items
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Active inventory items</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1 text-muted">Total Sales</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-black">${{ number_format(\App\Models\Sale::sum('grand_total'), 2) }}</div>
                                    <div class="me-auto">
                                        <span class="text-success d-inline-flex align-items-center">
                                            <i data-feather="trending-up" class="ms-1" style="height: 18px; width: 18px;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Total revenue generated</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1 text-muted">Total Purchases</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-black">${{ number_format(\App\Models\Purchase::sum('grand_total'), 2) }}</div>
                                    <div class="me-auto">
                                        <span class="badge bg-info-subtle text-info">
                                            <i data-feather="shopping-cart" class="me-1" style="height: 14px; width: 14px;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Total procurement cost</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1 text-muted">Low Stock Alert</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-danger">{{ \App\Models\Product::whereRaw('product_qty < stock_alert')->count() }}</div>
                                    <div class="me-auto">
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i data-feather="alert-triangle" class="me-1" style="height: 14px; width: 14px;"></i>
                                            Critical
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Items below threshold</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card border-start border-primary border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded">
                                        <i data-feather="users" style="height: 24px; width: 24px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Customers</p>
                                <h5 class="mb-0">{{ \App\Models\Customer::count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-success border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-success-subtle text-success rounded">
                                        <i data-feather="truck" style="height: 24px; width: 24px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Suppliers</p>
                                <h5 class="mb-0">{{ \App\Models\Supplier::count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-warning border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded">
                                        <i data-feather="home" style="height: 24px; width: 24px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Warehouses</p>
                                <h5 class="mb-0">{{ \App\Models\WareHouse::count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-info border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-info-subtle text-info rounded">
                                        <i data-feather="tag" style="height: 24px; width: 24px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Brands</p>
                                <h5 class="mb-0">{{ \App\Models\Brand::count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="trending-up" class="widgets-icons"></i>
                            </div>
                            <h5 class="card-title mb-0">Sales vs Purchase Overview</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="sales-purchase-chart" class="apex-charts"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="pie-chart" class="widgets-icons"></i>
                            </div>
                            <h5 class="card-title mb-0">Stock Status</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="stock-status-chart" class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="activity" class="widgets-icons"></i>
                            </div>
                            <h5 class="card-title mb-0">Recent Sales</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Sale::with('customer')->latest()->take(5)->get() as $sale)
                                    <tr>
                                        <td><small class="text-muted">{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</small></td>
                                        <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                                        <td class="fw-semibold">${{ number_format($sale->grand_total, 2) }}</td>
                                        <td><span class="badge bg-success-subtle text-success">{{ $sale->status }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="alert-circle" class="widgets-icons"></i>
                            </div>
                            <h5 class="card-title mb-0">Low Stock Products</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Product::whereRaw('product_qty < stock_alert')->take(5)->get() as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td><span class="badge bg-danger">{{ $product->product_qty }}</span></td>
                                        <td><span class="badge bg-warning-subtle text-warning">Low Stock</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- External API Integration Section -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                    <i data-feather="dollar-sign" class="widgets-icons"></i>
                                </div>
                                <h5 class="card-title mb-0">Currency Exchange Rates</h5>
                            </div>
                            <button id="fetchRates" class="btn btn-sm btn-primary">
                                <i data-feather="refresh-cw" style="height: 14px; width: 14px;"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ratesResult">
                            <div class="text-center text-muted py-3">
                                <i data-feather="info" class="mb-2"></i>
                                <p>Click the refresh button to fetch live exchange rates</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if ApexCharts is loaded
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts is not loaded');
        return;
    }

    // Sales vs Purchase Chart
    var salesPurchaseOptions = {
        series: [{
            name: 'Sales',
            data: [
                {{ DB::table('sales')->whereRaw('MONTH(date) = 1')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 2')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 3')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 4')->sum('grand_total') ?? 0 }},
                {{ DB::table('sales')->whereRaw('MONTH(date) = 5')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 6')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 7')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 8')->sum('grand_total') ?? 0 }},
                {{ DB::table('sales')->whereRaw('MONTH(date) = 9')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 10')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 11')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('sales')->whereRaw('MONTH(date) = 12')->sum('grand_total') ?? 0 }}
            ]
        }, {
            name: 'Purchases',
            data: [
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 1')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 2')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 3')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 4')->sum('grand_total') ?? 0 }},
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 5')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 6')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 7')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 8')->sum('grand_total') ?? 0 }},
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 9')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 10')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 11')->sum('grand_total') ?? 0 }}, 
                {{ DB::table('purchases')->whereRaw('MONTH(date) = 12')->sum('grand_total') ?? 0 }}
            ]
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false }
        },
        colors: ['#537AEF', '#e68434'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1
            }
        }
    };
    
    var salesPurchaseChart = new ApexCharts(document.querySelector("#sales-purchase-chart"), salesPurchaseOptions);
    salesPurchaseChart.render();

    // Stock Status Chart
    var stockStatusOptions = {
        series: [
            {{ \App\Models\Product::whereRaw('product_qty >= stock_alert')->count() ?? 0 }}, 
            {{ \App\Models\Product::whereRaw('product_qty < stock_alert')->count() ?? 0 }}
        ],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['In Stock', 'Low Stock'],
        colors: ['#28a745', '#dc3545'],
        legend: {
            position: 'bottom'
        }
    };
    
    var stockStatusChart = new ApexCharts(document.querySelector("#stock-status-chart"), stockStatusOptions);
    stockStatusChart.render();

    // API Integration - Exchange Rates
    const fetchRatesBtn = document.getElementById('fetchRates');
    const ratesResult = document.getElementById('ratesResult');
    
    if (fetchRatesBtn) {
        fetchRatesBtn.addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';
            
            fetch('/api/exchange-rates')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const rates = data.rates;
                        let html = `
                            <div class="p-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted small">Base: ${data.base_currency}</span>
                                    <span class="text-muted small">${data.date}</span>
                                </div>
                                <div class="row g-2">
                        `;
                        
                        Object.entries(rates).forEach(([currency, rate]) => {
                            if (rate) {
                                html += `
                                    <div class="col-6">
                                        <div class="border rounded p-2 text-center">
                                            <div class="fw-bold text-primary">${currency}</div>
                                            <div class="small">${rate.toFixed(4)}</div>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                        
                        html += `
                                </div>
                            </div>
                        `;
                        ratesResult.innerHTML = html;
                    } else {
                        ratesResult.innerHTML = `
                            <div class="alert alert-danger mb-0">
                                <i data-feather="alert-circle"></i> ${data.message}
                            </div>
                        `;
                    }
                    feather.replace();
                })
                .catch(error => {
                    ratesResult.innerHTML = `
                        <div class="alert alert-danger mb-0">
                            <i data-feather="alert-circle"></i> Error fetching exchange rates
                        </div>
                    `;
                    feather.replace();
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    feather.replace();
                });
        });
    }
});
</script>

@endsection