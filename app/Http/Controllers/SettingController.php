<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function company()
    {
        $setting = CompanySetting::getSettings();

        return view('settings.company', compact('setting'));
    }

    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'npwp' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $setting = CompanySetting::getSettings();

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('company', 'public');
        }

        $setting->update($validated);

        return back()->with('success', 'Pengaturan perusahaan berhasil diperbarui');
    }

    public function payroll()
    {
        $setting = CompanySetting::getSettings();

        return view('settings.payroll', compact('setting'));
    }

    public function updatePayroll(Request $request)
    {
        $validated = $request->validate([
            'overtime_rate_per_hour' => 'required|numeric|min:0',
            'late_penalty_per_minute' => 'required|numeric|min:0',
            'working_hours_per_day' => 'required|integer|min:1',
            'bpjs_kesehatan_capping' => 'required|numeric|min:0',
            'bpjs_jp_capping' => 'required|numeric|min:0',
            'overtime_formula' => 'required|in:flat,depnaker',
        ]);

        $settings = CompanySetting::getSettings();

        $settings->update($validated);

        return back()->with('success', 'Pengaturan payroll berhasil diperbarui');
    }
}
