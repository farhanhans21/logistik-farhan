<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total products
        $totalProducts = Product::count();
        
        // Get total stock value
        $totalStockValue = Product::with('inventory')->get()->sum(function($product) {
            return $product->price * $product->current_stock;
        });
        
        // Get recent stock movements
        $recentMovements = Inventory::with('product')
            ->latest()
            ->take(5)
            ->get();
            
        // Get low stock products (less than 10 items)
        $lowStockProducts = Product::with('inventory')
            ->get()
            ->filter(function($product) {
                return $product->current_stock < 10;
            });
            
        // Get monthly stock movements using Eloquent
        $monthlyMovements = Inventory::select(
                'created_at',
                'type',
                'quantity'
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->get()
            ->groupBy(function($movement) {
                return $movement->created_at->format('Y-m');
            })
            ->map(function($group) {
                return [
                    'month' => $group->first()->created_at->month,
                    'year' => $group->first()->created_at->year,
                    'total_in' => $group->where('type', 'in')->sum('quantity'),
                    'total_out' => $group->where('type', 'out')->sum('quantity')
                ];
            })
            ->values()
            ->sortByDesc(function($item) {
                return $item['year'] . $item['month'];
            })
            ->take(6);

        return view('dashboard', compact(
            'totalProducts',
            'totalStockValue',
            'recentMovements',
            'lowStockProducts',
            'monthlyMovements'
        ));
    }
} 