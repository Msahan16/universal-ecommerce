<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_email', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%");
            });
        }

        $quotations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.quotations.index', compact('quotations'));
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['items', 'user']);
        return view('admin.quotations.show', compact('quotation'));
    }

    public function generate(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'estimated_days' => 'required|integer|min:1',
            'valid_until' => 'required|date',
            'tax' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.specifications' => 'nullable|string|max:500',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $quotation) {
            // Delete old items if updating
            $quotation->items()->delete();

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $subtotal += $itemTotal;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'item_name' => $item['item_name'],
                    'specifications' => $item['specifications'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $itemTotal,
                ]);
            }

            $tax = (float) ($validated['tax'] ?? 0);
            $total = $subtotal + $tax;

            $quotation->update([
                'estimated_days' => $validated['estimated_days'],
                'valid_until' => $validated['valid_until'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'status' => 'quoted',
            ]);
        });

        return redirect()->route('admin.quotations.show', $quotation->id)
            ->with('success', 'Official quotation generated and published for customer #' . $quotation->reference_number);
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,quoted,accepted,rejected',
        ]);

        $quotation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Quotation status updated to ' . strtoupper($request->status));
    }
}
