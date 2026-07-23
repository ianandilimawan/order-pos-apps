<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Promo;
use App\Http\Requests\CreatePromoRequest;
use App\Http\Requests\UpdatePromoRequest;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-promos')->only(['index', 'show']);
        $this->middleware('permission:create-promo')->only(['create', 'store']);
        $this->middleware('permission:edit-promo')->only(['edit', 'update']);
        $this->middleware('permission:delete-promo')->only('destroy');
    }

    public function index()
    {
        return view('admin.promos.index');
    }

    public function create()
    {
        $promo = new Promo();

        return view('admin.promos.create', compact('promo'));
    }

    public function store(CreatePromoRequest $request)
    {
        $data = $request->validated();
        
        $data['is_active'] = $request->has('is_active');

        $promo = Promo::create($data);

        ActivityLogService::logCreate($promo);

        return redirect()->route('admin.promos.index')->with('success', 'Promo created successfully.');
    }

    public function show(string $id)
    {
        $promo = Promo::findOrFail($id);
        return view('admin.promos.show', compact('promo'));
    }

    public function edit(string $id)
    {
        $promo = Promo::findOrFail($id);

        return view('admin.promos.edit', compact('promo'));
    }

    public function update(UpdatePromoRequest $request, string $id)
    {
        $promo = Promo::findOrFail($id);
        $data = $request->validated();

        $data['is_active'] = $request->has('is_active');

        ActivityLogService::logUpdate($promo, clone $promo, $data);
        
        $promo->update($data);

        return redirect()->route('admin.promos.index')->with('success', 'Promo updated successfully.');
    }

    public function destroy(string $id)
    {
        $promo = Promo::findOrFail($id);
        
        ActivityLogService::logDelete($promo);
        
        $promo->delete();

        return redirect()->route('admin.promos.index')->with('success', 'Promo deleted successfully.');
    }
}
