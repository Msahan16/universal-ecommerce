<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,text,color,number',
        ]);

        Attribute::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully!');
    }

    public function storeValue(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:20',
        ]);

        AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value' => $validated['value'],
            'color_code' => $validated['color_code'] ?? null,
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Value added to ' . $attribute->name);
    }

    public function destroyValue(AttributeValue $value)
    {
        $value->delete();
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute value removed.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute removed.');
    }
}
