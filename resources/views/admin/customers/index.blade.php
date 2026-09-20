@extends('layouts.admin')

@section('page_title', 'Customer Directory')
@section('page_subtitle', 'Registered accounts and customer lifetime value')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0 text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                <tr>
                    <th class="ps-4 py-3">Customer</th>
                    <th class="py-3">Email Address</th>
                    <th class="py-3">Joined Date</th>
                    <th class="py-3">Total Orders</th>
                    <th class="pe-4 py-3 text-end">Lifetime Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $cust)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 d-flex align-items-center justify-content-center font-bold text-xs">
                                {{ substr($cust->name, 0, 1) }}
                            </div>
                            <strong class="text-slate-900">{{ $cust->name }}</strong>
                        </div>
                    </td>
                    <td class="py-3 text-slate-600 text-xs font-mono">
                        {{ $cust->email }}
                    </td>
                    <td class="py-3 text-slate-500 text-xs">
                        {{ $cust->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-3 font-mono fw-bold text-slate-700">
                        {{ $cust->orders_count }} orders
                    </td>
                    <td class="pe-4 py-3 text-end font-mono font-bold text-slate-900">
                        {{ $currency }}{{ number_format($cust->orders_sum_total ?? 0, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-slate-400">No customers registered yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-center">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
