<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('agent')) {
            return $this->agentDashboard();
        }
        return $this->adminDashboard();
    }

    /* ============================================================
     *  DASHBOARD ADMIN
     * ============================================================ */
    protected function adminDashboard()
    {
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth   = Carbon::now()->endOfMonth();

        // --- KPI ---
        $revenueThisMonth = Sale::whereBetween('created_at', [$startMonth, $endMonth])
            ->sum('grand_total');

        $purchasesThisMonth = Purchase::whereBetween('created_at', [$startMonth, $endMonth])
            ->sum('grand_total');

        $stockValue = Product::sum(DB::raw('product_qty * price'));

        $totalProducts     = Product::count();
        $activeCustomers   = Customer::count();
        $activeSuppliers   = Supplier::count();

        // --- Produits en stock bas (product_qty <= stock_alert) ---
        $lowStockProducts = Product::whereColumn('product_qty', '<=', 'stock_alert')
            ->orderBy('product_qty')
            ->limit(10)
            ->get();

        // --- Ventes / Achats des 12 derniers mois ---
        $salesByMonth = Sale::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('SUM(grand_total) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $purchasesByMonth = Purchase::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('SUM(grand_total) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // --- Top 5 produits vendus ---
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // --- Top 5 clients ---
        $topCustomers = Sale::select('customer_id', DB::raw('SUM(grand_total) as total'))
            ->with('customer')
            ->groupBy('customer_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // --- Dernières ventes / achats ---
        $recentSales = Sale::with('customer')->latest()->limit(5)->get();
        $recentPurchases = Purchase::with('supplier')->latest()->limit(5)->get();

        return view('admin.dashboards.admin', compact(
            'revenueThisMonth',
            'purchasesThisMonth',
            'stockValue',
            'totalProducts',
            'activeCustomers',
            'activeSuppliers',
            'lowStockProducts',
            'salesByMonth',
            'purchasesByMonth',
            'topProducts',
            'topCustomers',
            'recentSales',
            'recentPurchases'
        ));
    }

    /* ============================================================
     *  DASHBOARD AGENT
     * ============================================================ */
    protected function agentDashboard()
    {
        $today      = Carbon::today();
        $startMonth = Carbon::now()->startOfMonth();

        // Ventes du mois (toutes, car pas de user_id pour l'instant)
        $mySalesMonth = Sale::where('created_at', '>=', $startMonth)
            ->sum('grand_total');

        $mySalesCount = Sale::where('created_at', '>=', $startMonth)
            ->count();

        $todaySales = Sale::whereDate('created_at', $today)
            ->sum('grand_total');

        $recentSales = Sale::with('customer')
            ->latest()
            ->limit(10)
            ->get();

        $lowStockProducts = Product::whereColumn('product_qty', '<=', 'stock_alert')
            ->orderBy('product_qty')
            ->limit(10)
            ->get();

        $totalCustomers = Customer::count();
        $totalProducts  = Product::count();

        return view('admin.dashboards.agent', compact(
            'mySalesMonth',
            'mySalesCount',
            'todaySales',
            'recentSales',
            'lowStockProducts',
            'totalCustomers',
            'totalProducts'
        ));
    }
}
