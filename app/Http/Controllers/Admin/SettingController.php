<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Requests\SettingRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function __construct() {
        $this->middleware('can:view settings')->only('index', 'show');
        $this->middleware('can:create settings')->only('create', 'store');
        $this->middleware('can:edit settings')->only('edit', 'update', 'bulkUpdate');
        $this->middleware('can:delete settings')->only('destroy');
    }

    public function index()
    {
        $settings = Setting::getAllGrouped();
        return view('admin.settings.index', compact('settings'));
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        $setting->update($request->validated());
        
        return redirect()->route('settings.index')
            ->with('success', 'Setting updated successfully');
    }

    public function bulkUpdate(Request $request)
    {
        // \Log::debug("Bulk update started", [
        //     'input' => $request->except(['_token', '_method']),
        //     'files' => array_keys($request->allFiles())
        // ]);

        $request->validate([
            'settings' => 'required|array',
        ]);

        \DB::beginTransaction();

        try {
            $uploadedFiles = $request->file('settings') ?? [];

            foreach ($request->input('settings') as $key => $value) {
                $setting = Setting::where('key', $key)->first();

                if (!$setting) {
                    //\Log::warning("Setting not found: {$key}");
                    continue;
                }

                // Handle file removal
                if ($setting->type === 'file' && $request->has("remove_file.$key")) {
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                       // \Log::debug("Removed file for setting: {$key}");
                    }
                    $setting->value = null;
                    $setting->save();
                    continue;
                }

                // Get the uploaded file if available
                $valueToSet = $setting->type === 'file' && isset($uploadedFiles[$key]) && $uploadedFiles[$key] instanceof \Illuminate\Http\UploadedFile
                    ? $uploadedFiles[$key]
                    : $value;

                if (!Setting::setValue($key, $valueToSet)) {
                    throw new \Exception("Failed to update setting: {$key}");
                }
            }

            \DB::commit();
            //\Log::info("Settings updated successfully");
            return redirect()->route('settings.index')
                ->with('success', 'Settings updated successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            // \Log::error("Bulk update failed: " . $e->getMessage(), [
            //     'exception' => $e,
            //     'request' => $request->all()
            // ]);
            return redirect()->back()
                ->with('error', 'Settings update failed: ' . $e->getMessage());
        }
    }
}
