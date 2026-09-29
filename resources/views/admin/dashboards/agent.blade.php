@extends('admin.layouts.master')

@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-12">
                    <h4 class="fw-bold">Tableau de bord — Agent ARTIPLAST</h4>
                    <p class="text-muted">Bonjour {{ auth()->user()->name }}, voici votre activité</p>
                </div>
            </div>

            {{-- KPI AGENT --}}
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Ventes du mois</h6>
                            <h3 class="mb-0">{{ number_format($mySalesMonth, 3, ',', ' ') }} TND</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Ventes aujourd'hui</h6>
                            <h3 class="mb-0">{{ number_format($todaySales, 3, ',', ' ') }} TND</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Factures (mois)</h6>
                            <h3 class="mb-0">{{ $mySalesCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-secondary text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase">Clients</h6>
                            <h3 class="mb-0">{{ $totalCustomers }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTIONS RAPIDES --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Actions rapides</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('add.sale') }}" class="btn btn-primary">
                                <i class="mdi mdi-cart-plus"></i> Nouvelle vente
                            </a>
                            <a href="{{ route('add.product') }}" class="btn btn-success">
                                <i class="mdi mdi-package-variant-plus"></i> Ajouter un produit
                            </a>
                            <a href="{{ route('add.customer') }}" class="btn btn-info">
                                <i class="mdi mdi-account-plus"></i> Ajouter un client
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALERTES STOCK --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-white">
                            <h5 class="card-title mb-0">⚠️ Produits en stock bas</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                <tr><th>Produit</th><th>Code</th><th class="text-end">Stock</th></tr>
                                </thead>
                                <tbody>
                                @forelse($lowStockProducts as $p)
                                    <tr>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->code ?? '—' }}</td>
                                        <td class="text-end text-danger fw-bold">{{ $p->product_qty }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-success">✅ Aucun produit en stock bas</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DERNIÈRES VENTES --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">Dernières ventes</h5>
                            <a href="{{ route('all.sale') }}" class="btn btn-sm btn-primary">Voir tout</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                <tr><th>Client</th><th>Date</th><th class="text-end">Montant</th><th class="text-center">Action</th></tr>
                                </thead>
                                <tbody>
                                @forelse($recentSales as $s)
                                    <tr>
                                        <td>{{ $s->customer->name ?? '—' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                                        <td class="text-end">{{ number_format($s->grand_total, 3, ',', ' ') }} TND</td>
                                        <td class="text-center">
                                            <a href="{{ route('details.sale', $s->id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Aucune vente</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
