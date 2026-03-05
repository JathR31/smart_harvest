<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SMSApiPhilippinesService;
use App\Models\User;
use App\Models\SMSAnnouncement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SMSAnnouncementController extends Controller
{
    protected $smsService;
    
    public function __construct(SMSApiPhilippinesService $smsService)
    {
        $this->middleware(['auth', 'admin']);
        $this->smsService = $smsService;
    }
    
    /**
     * Show SMS announcements page
     */
    public function index()
    {
        $announcements = SMSAnnouncement::with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $balance = $this->smsService->checkBalance();
        
        return view('admin.sms-announcements', [
            'announcements' => $announcements,
            'balance' => $balance
        ]);
    }
    
    /**
     * Show create announcement form
     */
    public function create()
    {
        // Get all farmers with phone numbers
        $farmers = User::where('role', 'Farmer')
            ->whereNotNull('phone')
            ->orWhereNotNull('phone_number')
            ->select('id', 'name', 'phone', 'phone_number', 'location')
            ->orderBy('name')
            ->get();
        
        // Get municipalities for filtering
        $municipalities = User::where('role', 'Farmer')
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->sort();
        
        $balance = $this->smsService->checkBalance();
        
        return view('admin.sms-announcements-create', [
            'farmers' => $farmers,
            'municipalities' => $municipalities,
            'balance' => $balance
        ]);
    }
    
    /**
     * Send SMS announcement
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:160',
            'recipient_type' => 'required|in:all,selected,municipality',
            'recipients' => 'required_if:recipient_type,selected|array',
            'municipality' => 'required_if:recipient_type,municipality|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Get recipients based on type
        $phoneNumbers = $this->getRecipients($request);
        
        if (empty($phoneNumbers)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid recipients found with phone numbers.'
            ]);
        }
        
        // Send SMS announcement
        $result = $this->smsService->sendAnnouncement(
            $phoneNumbers,
            $request->message,
            'SmartHarvest'
        );
        
        // Save announcement record
        $announcement = SMSAnnouncement::create([
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'recipient_type' => $request->recipient_type,
            'recipient_filter' => $request->municipality ?? null,
            'total_recipients' => count($phoneNumbers),
            'sent_count' => $result['sent'],
            'failed_count' => $result['failed'],
            'status' => $result['success'] ? 'sent' : 'partial',
            'sent_at' => now(),
            'details' => json_encode($result['details'])
        ]);
        
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'sent' => $result['sent'],
            'failed' => $result['failed'],
            'announcement_id' => $announcement->id
        ]);
    }
    
    /**
     * Get announcement details
     */
    public function show($id)
    {
        $announcement = SMSAnnouncement::with('sender')->findOrFail($id);
        
        return view('admin.sms-announcements-show', [
            'announcement' => $announcement,
            'details' => json_decode($announcement->details, true)
        ]);
    }
    
    /**
     * Preview recipients count
     */
    public function previewRecipients(Request $request)
    {
        $phoneNumbers = $this->getRecipients($request);
        
        return response()->json([
            'count' => count($phoneNumbers),
            'recipients' => array_map(function($phone) {
                return $this->maskPhoneNumber($phone);
            }, $phoneNumbers)
        ]);
    }
    
    /**
     * Check SMS balance
     */
    public function checkBalance()
    {
        $result = $this->smsService->checkBalance();
        
        return response()->json($result);
    }
    
    /**
     * Get recipients based on request parameters
     */
    protected function getRecipients(Request $request)
    {
        $query = User::where('role', 'Farmer');
        
        switch ($request->recipient_type) {
            case 'all':
                // All farmers
                break;
                
            case 'selected':
                // Specific farmers
                $query->whereIn('id', $request->recipients ?? []);
                break;
                
            case 'municipality':
                // Farmers from specific municipality
                $query->where('location', $request->municipality);
                break;
        }
        
        // Get phone numbers
        $users = $query->get();
        $phoneNumbers = [];
        
        foreach ($users as $user) {
            $phone = $user->phone_number ?? $user->phone;
            if ($phone) {
                $phoneNumbers[] = $phone;
            }
        }
        
        return array_unique($phoneNumbers);
    }
    
    /**
     * Mask phone number for display
     */
    protected function maskPhoneNumber($phoneNumber)
    {
        if (!$phoneNumber) {
            return 'XXX XXX XXXX';
        }
        
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (strlen($cleaned) >= 4) {
            $lastFour = substr($cleaned, -4);
            return '+639XX XXX ' . $lastFour;
        }
        
        return 'XXX XXX XXXX';
    }
}
