@extends('admin.layouts.master')

@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-12">
                    <h4 class="fw-bold">Tableau de bord — ARTIPLAST</h4>
                    <p class="text-muted">Vue d'ensemble de la gestion de stock et des ventes</p>
                </div>
            </div>

            {{-- KPI CARDS --}}
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Chiffre d'affaires (mois)</h6>
                            <h3 class="mb-0">{{ number_format($revenueThisMonth, 3, ',', ' ') }} TND</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Achats (mois)</h6>
                            <h3 class="mb-0">{{ number_format($purchasesThisMonth, 3, ',', ' ') }} TND</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Valeur du stock</h6>
                            <h3 class="mb-0">{{ number_format($stockValue, 3, ',', ' ') }} TND</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Clients actifs</h6>
                            <h3 class="mb-0">{{ $activeCustomers }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRAPHIQUES --}}
            <div class="row">
                <div class="col-xl-8 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title">Évolution Ventes / Achats (12 mois)</h5>
                        </div>
                        <div class="card-body">
                            <div id="sales-chart" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title">Top 5 produits</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                <tr><th>Produit</th><th class="text-end">Qté</th></tr>
                                </thead>
                                <tbody>
                                @forelse($topProducts as $p)
                                    <tr>
                                        <td>{{ $p->name }}</td>
                                        <td class="text-end">{{ number_format($p->total_sold, 0, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">Aucune vente</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOP CLIENTS --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Top 5 clients</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                <tr><th>Client</th><th class="text-end">CA (TND)</th></tr>
                                </thead>
                                <tbody>
                                @forelse($topCustomers as $c)
                                    <tr>
                                        <td>{{ $c->customer->name ?? '—' }}</td>
                                        <td class="text-end">{{ number_format($c->total, 3, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">Aucun client</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALERTES STOCK BAS --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">⚠️ Alertes stock bas</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Code</th>
                                    <th class="text-end">Stock</th>
                                    <th class="text-end">Seuil</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($lowStockProducts as $p)
                                    <tr>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->code ?? '—' }}</td>
                                        <td class="text-end text-danger fw-bold">{{ $p->product_qty }}</td>
                                        <td class="text-end">{{ $p->stock_alert }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-success">✅ Aucun produit en stock bas</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DERNIÈRES VENTES / ACHATS --}}
            <div class="row">
                <div class="col-xl-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">Dernières ventes</h5>
                            <a href="{{ route('all.sale') }}" class="btn btn-sm btn-primary">Voir tout</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                <tr><th>Client</th><th>Date</th><th class="text-end">Montant</th></tr>
                                </thead>
                                <tbody>
                                @forelse($recentSales as $s)
                                    <tr>
                                        <td>{{ $s->customer->name ?? '—' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                                        <td class="text-end">{{ number_format($s->grand_total, 3, ',', ' ') }} TND</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Aucune vente</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">Derniers achats</h5>
                            <a href="{{ route('all.purchase') }}" class="btn btn-sm btn-success">Voir tout</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                <tr><th>Fournisseur</th><th>Date</th><th class="text-end">Montant</th></tr>
                                </thead>
                                <tbody>
                                @forelse($recentPurchases as $p)
                                    <tr>
                                        <td>{{ $p->supplier->name ?? '—' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}</td>
                                        <td class="text-end">{{ number_format($p->grand_total, 3, ',', ' ') }} TND</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Aucun achat</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Scripts graphiques --}}
    <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const salesData     = @json($salesByMonth);
            const purchasesData = @json($purchasesByMonth);
            const months        = salesData.map(d => d.month);

            new ApexCharts(document.querySelector("#sales-chart"), {
                chart: { type: 'area', height: 320, toolbar: { show: false } },
                series: [
                    { name: 'Ventes', data: salesData.map(d => d.total) },
                    { name: 'Achats', data: purchasesData.map(d => d.total) }
                ],
                xaxis: { categories: months },
                colors: ['#0d6efd', '#198754'],
                stroke: { curve: 'smooth', width: 2 },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: v => v.toLocaleString('fr-FR') + ' TND' } }
            }).render();
        });
    </script>
@endsection
