<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $settingsRow = DB::table('settings')->first();
        $settings = [
            'membership_fee' => $settingsRow->membership_fee ?? null,
        ];

        $shippingCosts = DB::table('settings_shipping_costs')->orderBy('min_value_threshold')->get();

        return view('pages.dashboard.settings.index', compact('settings', 'shippingCosts'));
    }

    public function update(Request $request)
    {
        // Update membership fee
        if ($request->has('membership_fee')) {
            DB::table('settings')->update([
                'membership_fee' => $request->input('membership_fee'),
                'updated_at' => now(),
            ]);
        }

        // Update shipping cost (from modal)
        if ($request->route('id')) {
            DB::table('settings_shipping_costs')
                ->where('id', $request->route('id'))
                ->update([
                    'min_value_threshold' => $request->input('min_value_threshold'),
                    'max_value_threshold' => $request->input('max_value_threshold'),
                    'shipping_cost' => $request->input('shipping_cost'),
                    'updated_at' => now(),
                ]);
            return redirect()->route('dashboard.settings.index')->with('success', 'Shipping cost updated.');
        }

        return redirect()->route('dashboard.settings.index')->with('success', 'Settings updated.');
    }
}
