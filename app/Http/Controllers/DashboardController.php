<?php

namespace App\Http\Controllers;

use App\Models\DailyTest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\WhatsappDevice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $students = fn () => Student::query()->where('school_id', Auth::user()->school_id);

        $stats = [
            'total_students' => $students()->count(),
            'active_students' => $students()->where('status', 'active')->count(),
            'completed_students' => $students()->where('status', 'completed')->count(),
            'banned_students' => $students()->where('status', 'banned')->count(),
            'inactive_students' => $students()->where('status', 'inactive')->count(),
            'total_classes' => SchoolClass::query()
                ->where('school_id', Auth::user()->school_id)
                ->count(),
            'total_daily_tests' => DailyTest::query()
                ->where('school_id', Auth::user()->school_id)
                ->count(),
            'total_teachers' => Teacher::query()
                ->where('school_id', Auth::user()->school_id)
                ->count(),
        ];

        $whatsappDevice = WhatsappDevice::where('school_id', Auth::user()->school_id)->first();

        return view('schooladmin.dashboard.dashboard', compact('stats', 'whatsappDevice'));
    }

    public function generateWhatsappQRCode()
    {
        $whatsappDevice = WhatsappDevice::where('school_id', Auth::user()->school_id)->first();

        if (empty($whatsappDevice) || empty($whatsappDevice->wachat_device_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Please save your WhatsApp device number first.',
            ]);
        }

        $response = $this->generateWhatsappDeviceQRCode();

        if (empty($response)) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate QR code. Please try again.',
            ]);
        }

        $msg = $response['msg'] ?? null;

        if ($msg === 'Device already connected!') {
            return response()->json([
                'success' => true,
                'connected' => true,
                'message' => 'Device connected! Now you can send messages to students, teachers and parents.',
            ]);
        }

        if (!empty($response['qrcode'])) {
            return response()->json([
                'success' => true,
                'connected' => false,
                'qrcode' => $response['qrcode'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $msg ?? 'Unable to generate QR code. Please try again.',
        ]);
    }

    public function updateWhatsappDeviceNumber(Request $request)
    {   
        $request->validate([
            'whatsapp_device_number' => 'required|string|max:12|min:12|regex:/^92\d{10}$/',
        ]);

        $addDeviceApiUrl = env('WACHAT_API_URL').'/add-device';
        $getRegisteredDevicesApiUrl = env('WACHAT_API_URL').'/list-devices';
        $updateDeviceNumberApiUrl = env('WACHAT_API_URL').'/update-device';
        $apiKey = env('WACHAT_API_KEY');

        
        $checkWhatsappDeviceExists = WhatsappDevice::where('school_id', Auth::user()->school_id)->first();
        
        if($checkWhatsappDeviceExists && $checkWhatsappDeviceExists->wachat_device_number == $request->whatsapp_device_number){
            return redirect()->back()->with('error', 'Whatsapp Device Number already exists. Please use a different number.');
        }
        
        // DB::beginTransaction();

        if($checkWhatsappDeviceExists && $checkWhatsappDeviceExists->wachat_device_number != $request->whatsapp_device_number){
            
            DB::beginTransaction();
            
            WhatsappDevice::where('school_id', Auth::user()->school_id)->update([
                'wachat_device_number' => $request->whatsapp_device_number,
            ]);
    
            Http::post($updateDeviceNumberApiUrl, [
                'api_key' => $apiKey,
                'mobile' => $request->whatsapp_device_number,
                'webhook_url' => "https://edutrack.softwebies.com/school/".Auth::user()->school_id,
                'device_id' => $checkWhatsappDeviceExists->wachat_device_id,
            ]);

            DB::commit();
        }

        if(!$checkWhatsappDeviceExists){

            DB::beginTransaction();

            WhatsappDevice::create([
                'school_id' => Auth::user()->school_id,
                'user_id' => Auth::user()->id,
                'wachat_device_number' => $request->whatsapp_device_number,
            ]);

            // register new device
            Http::post($addDeviceApiUrl, [
                'api_key' => $apiKey,
                'mobile' => $request->whatsapp_device_number,
                'webhook_url' => "https://edutrack.softwebies.com/school/".Auth::user()->school_id,
            ]);

            // get registered devices list
            $registeredDevices = Http::post($getRegisteredDevicesApiUrl, [
                'api_key' => $apiKey,
            ]);

            if(!empty($registeredDevices->json()['devices'])){
                foreach($registeredDevices->json()['devices'] as $device){
                    if($device['mobile'] == $request->whatsapp_device_number){
                        WhatsappDevice::where('school_id', Auth::user()->school_id)->update([
                            'wachat_device_id' => $device['device_id'],
                        ]);
                        break;
                    }
                }   
            }

            DB::commit();
        }


        return redirect()->back()->with('success', 'Whatsapp Device Number updated successfully and you will need to scan the QR code again.');
    }

    function generateWhatsappDeviceQRCode()
    {
        $whatsappDevice = WhatsappDevice::where('school_id', Auth::user()->school_id)->first();

        $generateQRApiUrl = env('WACHAT_API_URL').'/generate-qr';
        $apiKey = env('WACHAT_API_KEY');

        if(!empty($whatsappDevice) && !empty($whatsappDevice->wachat_device_id)){
            $generateQRResponse = Http::post($generateQRApiUrl, [
                'api_key' => $apiKey,
                'device' => $whatsappDevice->wachat_device_number,
            ]);

            return $generateQRResponse->json();
        }

    }
}