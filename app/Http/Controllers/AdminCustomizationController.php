<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuItemCustomization;
use Illuminate\Http\Request;

class AdminCustomizationController extends Controller
{
    public function index(MenuItem $menuItem)
    {
        $customizations = $menuItem->customizations()->orderBy('sort_order')->get();

        return response()->json([
            'customizations' => $customizations
        ]);
    }

    public function store(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,multiple',
            'required' => 'boolean',
            'options' => 'required|array',
            'options.*.value' => 'required|string',
            'options.*.price' => 'required|numeric'
        ]);

        try {
            $lastOrder = $menuItem->customizations()->max('sort_order') ?? 0;

            $customization = MenuItemCustomization::create([
                'menu_item_id' => $menuItem->id,
                'name' => $request->name,
                'type' => $request->type,
                'required' => $request->required ?? false,
                'options' => $request->options,
                'sort_order' => $lastOrder + 1
            ]);

            return response()->json([
                'success' => true,
                'customization' => $customization
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(MenuItemCustomization $customization)
    {
        try {
            $customization->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
