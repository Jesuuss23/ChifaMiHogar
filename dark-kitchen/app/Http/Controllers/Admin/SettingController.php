<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'payment_phone'           => 'required|string|max:20',
            'payment_name'            => 'required|string|max:100',
            'whatsapp_number'         => 'required|string|max:20',
            'whatsapp_community_url'  => 'nullable|string|max:255',
            'kitchen_lat'             => 'required|numeric',
            'kitchen_lng'             => 'required|numeric',
            'free_delivery_km'        => 'required|numeric|min:0',
            'extra_km_fee'            => 'required|numeric|min:0',
            'store_open'              => 'nullable|boolean',
            'yape_qr'                 => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        Setting::updateOrCreate(['key' => 'payment_phone'], ['value' => $validated['payment_phone']]);
        Setting::updateOrCreate(['key' => 'payment_name'], ['value' => $validated['payment_name']]);
        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => $validated['whatsapp_number']]);
        Setting::updateOrCreate(['key' => 'whatsapp_community_url'], ['value' => $validated['whatsapp_community_url'] ?? '']);
        Setting::updateOrCreate(['key' => 'kitchen_lat'], ['value' => $validated['kitchen_lat']]);
        Setting::updateOrCreate(['key' => 'kitchen_lng'], ['value' => $validated['kitchen_lng']]);
        Setting::updateOrCreate(['key' => 'free_delivery_km'], ['value' => $validated['free_delivery_km']]);
        Setting::updateOrCreate(['key' => 'extra_km_fee'], ['value' => $validated['extra_km_fee']]);
        Setting::updateOrCreate(['key' => 'store_open'], ['value' => $request->has('store_open') ? '1' : '0']);

        if ($request->hasFile('yape_qr')) {
            $oldQr = Setting::where('key', 'yape_qr')->value('value');
            if ($oldQr) {
                Storage::disk('public')->delete($oldQr);
            }
            $qrPath = $request->file('yape_qr')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'yape_qr'], ['value' => $qrPath]);
        }

        return back()->with('success', 'Configuraciones actualizadas correctamente.');
    }
}