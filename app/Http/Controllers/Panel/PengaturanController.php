<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('panel.pengaturan.index', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'footer_text' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $settingsToUpdate = $request->only(['app_name', 'footer_text']);

        foreach ($settingsToUpdate as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        if ($request->hasFile('app_logo')) {
            $oldLogo = Setting::where('key', 'app_logo')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }

            $path = $request->file('app_logo')->store('logos', 'public');

            Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['value' => $path]
            );
        }

        return redirect()->back()->with('notification', ['type' => 'success', 'message' => 'Pengaturan berhasil diperbarui!']);
    }
}