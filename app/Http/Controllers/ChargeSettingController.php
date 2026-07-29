<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\ChargeSetting;
use App\Http\Requests\CreateChargeSettingRequest;
use App\Http\Requests\UpdateChargeSettingRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class ChargeSettingController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-charge_settings')->only(['index', 'show']);
        $this->middleware('permission:create-charge_settings')->only(['create', 'store']);
        $this->middleware('permission:edit-charge_settings')->only(['edit', 'update']);
        $this->middleware('permission:delete-charge_settings')->only('destroy');
    }

    public function index()
    {
        return view('admin.charge_settings.index');
    }

    public function create()
    {
        $chargeSetting = new ChargeSetting();
        $fileUrls = $this->getFileUrls(ChargeSetting::class);

        return view('admin.charge_settings.create', compact('chargeSetting', 'fileUrls'));
    }

    public function store(CreateChargeSettingRequest $request)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, ChargeSetting::class, 'charge_setting');

        $chargeSetting = ChargeSetting::create($data);

        ActivityLogService::logCreate($chargeSetting);

        return redirect()->route('admin.charge_settings.index')->with('success', 'ChargeSetting created successfully.');
    }

    public function show(ChargeSetting $chargeSetting)
    {
        return view('admin.charge_settings.show', compact('chargeSetting'));
    }

    public function edit(ChargeSetting $chargeSetting)
    {
        $fileUrls = $this->getFileUrls(ChargeSetting::class, $chargeSetting);

        return view('admin.charge_settings.edit', compact('chargeSetting', 'fileUrls'));
    }

    public function update(UpdateChargeSettingRequest $request, ChargeSetting $chargeSetting)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, ChargeSetting::class, 'charge_setting', $chargeSetting);

        $oldValues = $chargeSetting->getOriginal();

        $chargeSetting->update($data);

        ActivityLogService::logUpdate($chargeSetting, $oldValues);

        return redirect()->route('admin.charge_settings.index')->with('success', 'ChargeSetting updated successfully.');
    }

    public function destroy(ChargeSetting $chargeSetting)
    {
        $this->deleteAssociatedFiles(ChargeSetting::class, $chargeSetting);

        ActivityLogService::logDelete($chargeSetting);

        $chargeSetting->delete();
        return redirect()->route('admin.charge_settings.index')->with('success', 'ChargeSetting deleted successfully.');
    }


}
