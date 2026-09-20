@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Customer Account Portal</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">My Orders</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Customer Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white d-flex align-items-center justify-content-center fs-4 font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="fw-bold text-slate-900 mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-slate-400 text-xs">{{ Auth::user()->email }}</small>
                    </div>
                </div>

                <ul class="nav nav-pills flex-column space-y-1">
                    <li class="nav-item">
                        <a class="nav-link active bg-blue-600 text-white rounded-xl text-xs font-semibold py-2.5 px-3 d-flex align-items-center gap-2" href="{{ route('customer.orders') }}">
                            <i class="bi bi-box-seam"></i> My Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold py-2.5 px-3 d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-gear"></i> Profile Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold py-2.5 px-3 d-flex align-items-center gap-2" href="{{ route('orders.track') }}">
                            <i class="bi bi-geo-alt"></i> Track Any Order
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Orders List -->
        <div class="col-lg-9">
            <div class="card border-0 rounded-2xl shadow-sm bg-white overflow-hidden">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold text-slate-900 mb-0 text-base">My Order History</h5>
                </div>

                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Order #</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Total</th>
                                    <th class="pe-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="ps-4 py-3 font-mono font-bold text-slate-900">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="py-3 text-xs text-slate-500">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $order->status_color }} text-white text-xs px-2.5 py-1 rounded-pill uppercase">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-mono font-bold text-slate-900">
                                        {{ $currency }}{{ number_format($order->total, 2) }}
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-xs font-semibold">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="p-5 text-center">
                        <i class="bi bi-bag-x fs-1 text-slate-300 d-block mb-3"></i>
                        <h6 class="fw-bold text-slate-800">No Orders Placed Yet</h6>
                        <p class="text-xs text-slate-500 mb-3">You haven't placed any orders with this account yet.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-brand-primary btn-sm rounded-pill px-4">
                            Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
