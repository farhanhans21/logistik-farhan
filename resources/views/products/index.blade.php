@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Products</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Product List</h3>
                <div class="card-tools">
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form action="{{ route('products.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by name or code" 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="stock_status" class="form-control">
                                    <option value="">All Stock Status</option>
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>
                                        Low Stock (< 10)
                                    </option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>
                                        Out of Stock
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="sort" class="form-control">
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                                        Sort by Date
                                    </option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                                        Sort by Name
                                    </option>
                                    <option value="code" {{ request('sort') == 'code' ? 'selected' : '' }}>
                                        Sort by Code
                                    </option>
                                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>
                                        Sort by Price
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-dark">
                                        Code
                                        @if(request('sort') == 'code')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-dark">
                                        Name
                                        @if(request('sort') == 'name')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Description</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-dark">
                                        Price
                                        @if(request('sort') == 'price')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Current Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>Rp {{ number_format($product->price, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $product->current_stock < 10 ? 'danger' : 'success' }}">
                                        {{ $product->current_stock }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Basic Pagination -->
                <div>
                    <p>Showing {{ ($currentPage - 1) * 5 + 1 }} to {{ min($currentPage * 5, $total) }} of {{ $total }} entries</p>
                    
                    <div>
                        @if($currentPage > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">Previous</a>
                        @endif

                        @for($i = 1; $i <= $totalPages; $i++)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                               style="{{ $i == $currentPage ? 'font-weight: bold;' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor

                        @if($currentPage < $totalPages)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">Next</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 