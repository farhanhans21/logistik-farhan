@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Inventory Movements</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Inventory</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Inventory Movement List</h3>
                <div class="card-tools">
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Record New Movement
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form action="{{ route('inventory.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by reference or notes" 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="product_id" class="form-control">
                                    <option value="">All Products</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" name="date_from" class="form-control" 
                                       placeholder="From Date" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" name="date_to" class="form-control" 
                                       placeholder="To Date" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-dark">
                                        Date
                                        @if(request('sort') == 'created_at')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Product</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'type', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-dark">
                                        Type
                                        @if(request('sort') == 'type')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'quantity', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-dark">
                                        Quantity
                                        @if(request('sort') == 'quantity')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Reference</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventory as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $movement->product->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $movement->type === 'in' ? 'success' : 'danger' }}">
                                        {{ strtoupper($movement->type) }}
                                    </span>
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->reference_number }}</td>
                                <td>{{ $movement->notes }}</td>
                                <td>
                                    <a href="{{ route('inventory.edit', $movement) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('inventory.destroy', $movement) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to delete this movement?')">
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