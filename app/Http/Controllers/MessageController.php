<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->middleware('auth');
        $this->smsService = $smsService;
    }

    /**
     * Get all messages for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get received messages (threads only - no replies)
        $received = Message::with(['sender:id,name,phone_number', 'replies.sender:id,name'])
            ->threads()
            ->inbox($user->id)
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'conversation_id' => $msg->conversation_id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name ?? 'Unknown',
                    'sender_phone' => $msg->sender->phone_number ?? null,
                    'subject' => $msg->subject,
                    'content' => $msg->content,
                    'is_read' => $msg->is_read,
                    'is_replied' => $msg->is_replied,
                    'reply_count' => $msg->replies->count(),
                    'latest_reply' => $msg->replies->last() ? [
                        'content' => $msg->replies->last()->content,
                        'sender_name' => $msg->replies->last()->sender->name ?? 'Unknown',
                        'created_at' => $msg->replies->last()->created_at->diffForHumans(),
                    ] : null,
                    'created_at' => $msg->created_at->diffForHumans(),
                    'created_at_full' => $msg->created_at->format('M d, Y h:i A'),
                ];
            });
        
        // Get sent messages (threads only)
        $sent = Message::with(['receiver:id,name,phone_number', 'replies.receiver:id,name'])
            ->threads()
            ->sent($user->id)
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'conversation_id' => $msg->conversation_id,
                    'receiver_id' => $msg->receiver_id,
                    'receiver_name' => $msg->receiver->name ?? 'Unknown',
                    'receiver_phone' => $msg->receiver->phone_number ?? null,
                    'subject' => $msg->subject,
                    'content' => $msg->content,
                    'is_read' => $msg->is_read,
                    'sent_as_sms' => $msg->sent_as_sms,
                    'sms_status' => $msg->sms_status,
                    'reply_count' => $msg->replies->count(),
                    'latest_reply' => $msg->replies->last() ? [
                        'content' => $msg->replies->last()->content,
                        'receiver_name' => $msg->replies->last()->receiver->name ?? 'Unknown',
                        'created_at' => $msg->replies->last()->created_at->diffForHumans(),
                    ] : null,
                    'created_at' => $msg->created_at->diffForHumans(),
                    'created_at_full' => $msg->created_at->format('M d, Y h:i A'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'received' => $received,
            'sent' => $sent,
            'unread_count' => Message::inbox($user->id)->unread()->count()
        ]);
    }

    /**
     * Get a single message with its conversation thread
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $message = Message::with(['sender:id,name,phone_number', 'receiver:id,name,phone_number', 'parent'])
            ->findOrFail($id);
        
        // Check authorization
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Get the root message of the conversation
        $rootMessage = $message->parent_id ? $message->parent : $message;
        
        // Get all messages in this conversation
        $conversationMessages = Message::with(['sender:id,name', 'receiver:id,name'])
            ->where('conversation_id', $rootMessage->conversation_id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($user) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name ?? 'Unknown',
                    'receiver_id' => $msg->receiver_id,
                    'receiver_name' => $msg->receiver->name ?? 'Unknown',
                    'subject' => $msg->subject,
                    'content' => $msg->content,
                    'is_read' => $msg->is_read,
                    'sent_as_sms' => $msg->sent_as_sms,
                    'sms_status' => $msg->sms_status,
                    'is_mine' => $msg->sender_id === $user->id,
                    'created_at' => $msg->created_at->diffForHumans(),
                    'created_at_full' => $msg->created_at->format('M d, Y h:i A'),
                ];
            });
        
        // Mark as read if user is the receiver
        if ($message->receiver_id === $user->id && !$message->is_read) {
            $message->markAsRead();
        }
        
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $rootMessage->id,
                'conversation_id' => $rootMessage->conversation_id,
                'subject' => $rootMessage->subject,
                'sender_name' => $rootMessage->sender->name ?? 'Unknown',
                'receiver_name' => $rootMessage->receiver->name ?? 'Unknown',
                'created_at' => $rootMessage->created_at->format('M d, Y h:i A'),
            ],
            'conversation' => $conversationMessages
        ]);
    }

    /**
     * Send a new message
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|string',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'in:low,normal,high,urgent',
            'send_sms' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $data = $validator->validated();
        $sendSMS = $data['send_sms'] ?? false;
        $receiverId = $data['receiver_id'];
        
        // Handle "DA" (all officers) special case
        if ($receiverId === 'DA') {
            // Get all officers (Admin, DA Admin, and Superadmins)
            $officers = User::where(function ($query) {
                $query->whereIn('role', ['Admin', 'DA Admin'])
                      ->orWhere('is_superadmin', true);
            })->pluck('id')->toArray();
            
            if (empty($officers)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['receiver_id' => 'No DA officers found']
                ], 422);
            }
            
            // Create messages for each officer
            $messages = [];
            foreach ($officers as $officerId) {
                $message = Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $officerId,
                    'subject' => $data['subject'],
                    'content' => $data['content'],
                    'priority' => $data['priority'] ?? 'normal',
                    'sent_as_sms' => $sendSMS,
                    'sms_status' => $sendSMS ? 'pending' : 'not_sent',
                ]);
                
                if ($sendSMS) {
                    $this->sendMessageAsSMS($message);
                }
                
                $messages[] = $message;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Message sent to all DA officers!',
                'count' => count($messages)
            ], 201);
        }
        
        // Normal case - send to single recipient
        if (!User::where('id', $receiverId)->exists()) {
            return response()->json([
                'success' => false,
                'errors' => ['receiver_id' => 'User not found']
            ], 422);
        }
        
        // Create the message
        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'subject' => $data['subject'],
            'content' => $data['content'],
            'priority' => $data['priority'] ?? 'normal',
            'sent_as_sms' => $sendSMS,
            'sms_status' => $sendSMS ? 'pending' : 'not_sent',
        ]);
        
        // Send SMS if requested
        if ($sendSMS) {
            $this->sendMessageAsSMS($message);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'data' => $message
        ], 201);
    }

    /**
     * Reply to a message
     */
    public function reply(Request $request, $id)
    {
        $user = Auth::user();
        
        $parentMessage = Message::findOrFail($id);
        
        // Check authorization - can only reply if you're the sender or receiver
        if ($parentMessage->sender_id !== $user->id && $parentMessage->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'send_sms' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $data = $validator->validated();
        $sendSMS = $data['send_sms'] ?? false;
        
        // Determine the receiver (reply goes to the other person in the conversation)
        $receiverId = ($parentMessage->sender_id === $user->id) 
            ? $parentMessage->receiver_id 
            : $parentMessage->sender_id;
        
        // Get the root message to maintain conversation_id
        $rootMessage = $parentMessage->parent_id ? $parentMessage->parent : $parentMessage;
        
        // Create the reply
        $reply = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'parent_id' => $rootMessage->id,
            'conversation_id' => $rootMessage->conversation_id,
            'subject' => 'Re: ' . $rootMessage->subject,
            'content' => $data['content'],
            'priority' => $parentMessage->priority,
            'sent_as_sms' => $sendSMS,
            'sms_status' => $sendSMS ? 'pending' : 'not_sent',
        ]);
        
        // Mark parent as replied
        $rootMessage->markAsReplied();
        
        // Send SMS if requested
        if ($sendSMS) {
            $this->sendMessageAsSMS($reply);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully!',
            'data' => $reply
        ], 201);
    }

    /**
     * Mark a message as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $message = Message::where('id', $id)
            ->where('receiver_id', $user->id)
            ->firstOrFail();
        
        $message->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);
    }

    /**
     * Delete a message
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $message = Message::findOrFail($id);
        
        // Only sender or receiver can delete
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Get list of farmers (for DA officers to send messages to)
     */
    public function getFarmers()
    {
        // Get all farmer role users
        $farmers = User::where('role', 'Farmer')
            ->select('id', 'name', 'email', 'phone_number', 'municipality')
            ->orderBy('name')
            ->get();
        
        return response()->json($farmers);
    }

    /**
     * Get list of DA officers (for farmers to send messages to)
     * Includes all Admin, DA Admin, and Superadmin users
     */
    public function getOfficers()
    {
        // Get all admin/DA admin users - the complete list
        $officers = User::where(function ($query) {
                $query->whereIn('role', ['Admin', 'DA Admin'])
                      ->orWhere('is_superadmin', true);
            })
            ->select('id', 'name', 'email', 'phone_number', 'municipality', 'role')
            ->orderBy('name')
            ->get();
        
        return response()->json($officers);
    }

    /**
     * Send a message via SMS
     */
    private function sendMessageAsSMS($message)
    {
        try {
            $receiver = User::find($message->receiver_id);
            
            if (!$receiver || !$receiver->phone_number) {
                $message->update([
                    'sms_status' => 'failed',
                    'sms_error' => 'Receiver has no phone number'
                ]);
                return;
            }
            
            // Format the SMS content
            $sender = User::find($message->sender_id);
            $smsContent = "SmartHarvest Message\n\n";
            $smsContent .= "From: " . ($sender->name ?? 'Unknown') . "\n";
            $smsContent .= "Subject: " . $message->subject . "\n\n";
            $smsContent .= $message->content;
            
            // Truncate if too long (SMS limit is ~160 characters, but we'll allow 300)
            if (strlen($smsContent) > 300) {
                $smsContent = substr($smsContent, 0, 297) . '...';
            }
            
            // Send SMS
            $result = $this->smsService->sendMessage(
                $receiver->phone_number,
                $smsContent,
                'SmartHarvest'
            );
            
            // Update message status
            if ($result['success']) {
                $message->update([
                    'sms_status' => 'sent'
                ]);
                
                Log::info('Message sent via SMS', [
                    'message_id' => $message->id,
                    'receiver' => $receiver->phone_number
                ]);
            } else {
                $message->update([
                    'sms_status' => 'failed',
                    'sms_error' => $result['message'] ?? 'Unknown error'
                ]);
                
                Log::error('Failed to send message via SMS', [
                    'message_id' => $message->id,
                    'error' => $result['message'] ?? 'Unknown error'
                ]);
            }
        } catch (\Exception $e) {
            $message->update([
                'sms_status' => 'failed',
                'sms_error' => $e->getMessage()
            ]);
            
            Log::error('Exception sending message via SMS', [
                'message_id' => $message->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
