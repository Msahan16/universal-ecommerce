<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.cms.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle section toggles (unchecked checkboxes)
        $checkboxKeys = [
            'section_hero_enabled',
            'section_categories_enabled',
            'section_featured_enabled',
            'section_promo_enabled',
            'section_features_enabled',
            'section_testimonials_enabled',
            'section_quotation_enabled',
        ];

        foreach ($checkboxKeys as $key) {
            SiteSetting::set($key, $request->has($key) ? '1' : '0', 'sections', 'boolean');
            unset($data[$key]);
        }

        // Handle file uploads if present
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('settings', 'public');
            SiteSetting::set('logo', $path, 'general', 'image');
            unset($data['logo']);
        }

        if ($request->hasFile('hero_image_file')) {
            $path = $request->file('hero_image_file')->store('settings', 'public');
            SiteSetting::set('hero_image', $path, 'hero', 'image');
            unset($data['hero_image']);
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                SiteSetting::set($key, $value);
            }
        }

        return redirect()->route('admin.cms.index')->with('success', 'Website settings and CMS content updated successfully!');
    }
}
