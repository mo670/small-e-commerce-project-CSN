<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShopOwnerRequest;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ShopOwnerController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $owners = User::where('role', User::ROLE_SHOP_OWNER)
            ->with('shop')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/ShopOwners/Index', [
            'owners' => $owners,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        $shopsWithoutOwner = Shop::whereNull('user_id')->select('id', 'name')->get();

        return Inertia::render('Admin/ShopOwners/Create', [
            'availableShops' => $shopsWithoutOwner,
        ]);
    }

    public function store(ShopOwnerRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_SHOP_OWNER,
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);

        if (! empty($data['shop_id'])) {
            Shop::where('id', $data['shop_id'])->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.shop-owners.index')
            ->with('success', 'Shop owner created successfully!');
    }

    public function edit(User $shopOwner): Response
    {
        if ($shopOwner->role !== User::ROLE_SHOP_OWNER) {
            abort(404);
        }

        $shopOwner->load('shop');
        $availableShops = Shop::whereNull('user_id')
            ->orWhere('user_id', $shopOwner->id)
            ->select('id', 'name')
            ->get();

        return Inertia::render('Admin/ShopOwners/Edit', [
            'owner' => $shopOwner,
            'availableShops' => $availableShops,
        ]);
    }

    public function update(ShopOwnerRequest $request, User $shopOwner): RedirectResponse
    {
        if ($shopOwner->role !== User::ROLE_SHOP_OWNER) {
            abort(404);
        }

        $data = $request->validated();
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $shopOwner->update($updateData);

        // Update shop assignment
        if (isset($data['shop_id'])) {
            // Remove previous shop link
            Shop::where('user_id', $shopOwner->id)->update(['user_id' => null]);

            if ($data['shop_id']) {
                Shop::where('id', $data['shop_id'])->update(['user_id' => $shopOwner->id]);
            }
        }

        return redirect()->route('admin.shop-owners.index')
            ->with('success', 'Shop owner updated successfully!');
    }

    public function destroy(User $shopOwner): RedirectResponse
    {
        if ($shopOwner->role !== User::ROLE_SHOP_OWNER) {
            abort(404);
        }

        // Unlink shop
        Shop::where('user_id', $shopOwner->id)->update(['user_id' => null]);
        $shopOwner->delete();

        return redirect()->route('admin.shop-owners.index')
            ->with('success', 'Shop owner deleted successfully.');
    }
}

