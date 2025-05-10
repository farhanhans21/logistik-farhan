<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('inventory');

        // Filter berdasarkan nama atau kode
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan stok
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereHas('inventory', function($q) {
                        $q->whereRaw('(SELECT SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) FROM inventory WHERE product_id = products.id) < 10');
                    });
                    break;
                case 'out':
                    $query->whereHas('inventory', function($q) {
                        $q->whereRaw('(SELECT SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) FROM inventory WHERE product_id = products.id) <= 0');
                    });
                    break;
            }
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validasi field yang bisa di-sort
        $allowedSortFields = ['name', 'code', 'price', 'created_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = 5; // Jumlah item per halaman
        $currentPage = $request->get('page', 1);
        $total = $query->count();
        $products = $query->skip(($currentPage - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        // Hitung total halaman
        $totalPages = ceil($total / $perPage);

        return view('products.index', compact('products', 'currentPage', 'totalPages', 'total'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0'
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0'
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
} 