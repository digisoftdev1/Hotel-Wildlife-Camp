<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\CustomerMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use App\Http\Controllers\Controller;

class CustomerMessageController extends Controller
{
    public function index()
    {
        $customers = CustomerMessage::latest()->get();
        return view('customermessage.messagelist', compact('customers'));
    }

    #[OA\Post(
        path: '/customer-messages',
        summary: 'Submit a customer message',
        description: 'Submits a new customer message from the contact form.',
        tags: ['Messages'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone', 'subject', 'message'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'phone', type: 'string', pattern: '^[0-9]{10}$', example: '9841234567'),
                    new OA\Property(property: 'subject', type: 'string', example: 'Booking Inquiry'),
                    new OA\Property(property: 'message', type: 'string', example: 'I would like to book a room.'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Message submitted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Message submitted successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation failed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Failed to submit message. Please try again later.'),
                    ]
                )
            )
        ]
    )]

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|digits:10',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ], [
                'required' => 'This field is required.',
                'email' => 'Please enter a valid email.',
                'phone' => 'Please enter phone number',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            $customerMessage = CustomerMessage::create($request->only(
                'name',
                'email',
                'phone',
                'subject',
                'message'
            ));

            return response()->json([
                'status' => true,
                'message' => 'Message submitted successfully.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to submit message. Please try again later.'
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $message = CustomerMessage::findOrFail($id);

            if ($message->status === 'new') {
                $message->status = 'read';
                $message->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update message status'
            ], 500);
        }
    }
}