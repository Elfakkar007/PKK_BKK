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
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method', 'site_logo']);

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        Setting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings berhasil diperbarui.');
    }
}