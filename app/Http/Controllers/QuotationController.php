<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Quotation;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    public function create()
    {
        if (SiteSetting::get('section_quotation_enabled', '1') !== '1') {
            return redirect()->route('home')->with('error', 'Custom quotations are currently not enabled.');
        }

        $user = Auth::user();
        return view('quotations.create', compact('user'));
    }

    public function store(Request $request)
    {
        if (SiteSetting::get('section_quotation_enabled', '1') !== '1') {
            return redirect()->route('home')->with('error', 'Custom quotations are currently disabled.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'subject' => 'required|string|max:255',
            'requirements' => 'required|string|max:5000',
            'attachment_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip,doc,docx,dwg|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment_file')) {
            $attachmentPath = $request->file('attachment_file')->store('quotations', 'public');
        }

        $reference = 'QUO-' . date('Y') . '-' . strtoupper(Str::random(6));

        $quotation = Quotation::create([
            'reference_number' => $reference,
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'subject' => $validated['subject'],
            'requirements' => $validated['requirements'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        return redirect()->route('quotations.show', ['reference' => $quotation->reference_number])
            ->with('success', 'Your quotation request has been submitted! Our technical team will generate your customized quote shortly.');
    }

    public function track(Request $request)
    {
        $quotation = null;
        if ($request->filled('reference')) {
            $quotation = Quotation::with('items')
                ->where('reference_number', trim($request->reference))
                ->first();
        }

        return view('quotations.track', compact('quotation'));
    }

    public function show(string $reference)
    {
        $quotation = Quotation::with('items')
            ->where('reference_number', $reference)
            ->firstOrFail();

        return view('quotations.show', compact('quotation'));
    }

    public function accept(string $reference)
    {
        $quotation = Quotation::with('items')
            ->where('reference_number', $reference)
            ->firstOrFail();

        if ($quotation->status !== 'quoted') {
            return redirect()->back()->with('error', 'This quotation is not ready for acceptance or has already been processed.');
        }

        // Convert accepted quotation into an official Order
        $order = DB::transaction(function () use ($quotation) {
            $orderNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(6));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $quotation->user_id ?? Auth::id(),
                'customer_name' => $quotation->customer_name,
                'customer_email' => $quotation->customer_email,
                'customer_phone' => $quotation->customer_phone,
                'shipping_address' => 'Customer Pickup / Delivery as per Quote (' . $quotation->reference_number . ')',
                'city' => 'Custom Quote Order',
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'confirmed',
                'subtotal' => $quotation->subtotal,
                'discount' => 0.00,
                'shipping_fee' => 0.00,
                'total' => $quotation->total,
                'notes' => 'Generated from Custom Quotation #' . $quotation->reference_number . ': ' . $quotation->subject,
            ]);

            foreach ($quotation->items as $qItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $qItem->item_name,
                    'variant_name' => $qItem->specifications,
                    'price' => $qItem->unit_price,
                    'quantity' => $qItem->quantity,
                    'total' => $qItem->total,
                ]);
            }

            $quotation->update(['status' => 'accepted']);

            return $order;
        });

        return redirect()->route('orders.success', ['order_number' => $order->order_number])
            ->with('success', 'Quotation accepted! Your official order has been created.');
    }
}
