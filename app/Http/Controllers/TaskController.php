<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request): JsonResponse
    {
        
        $query = Task::with('user')->forUser(auth()->id());

        // Filter by completion status
        if ($request->has('completed')) {
            $completed = filter_var($request->completed, FILTER_VALIDATE_BOOLEAN);
            $query->where('completed', $completed);
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $tasks = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'message' => 'Tasks retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->completed ?? false,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $task->load('user'),
            'message' => 'Task created successfully.'
        ], 201);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): JsonResponse
    {
        // Authorization check - user can only view their own tasks
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to task.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $task->load('user'),
            'message' => 'Task retrieved successfully.'
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        // Authorization check
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to task.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $task->update($request->only(['title', 'description', 'completed']));

        return response()->json([
            'success' => true,
            'data' => $task->fresh()->load('user'),
            'message' => 'Task updated successfully.'
        ]);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        // Authorization check
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to task.'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ], 200);
    }

    /**
     * Mark task as completed.
     */
    public function complete(Task $task): JsonResponse
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to task.'
            ], 403);
        }

        $task->markAsCompleted();

        return response()->json([
            'success' => true,
            'data' => $task->fresh()->load('user'),
            'message' => 'Task marked as completed.'
        ]);
    }

    /**
     * Mark task as pending.
     */
    public function pending(Task $task): JsonResponse
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to task.'
            ], 403);
        }

        $task->markAsPending();

        return response()->json([
            'success' => true,
            'data' => $task->fresh()->load('user'),
            'message' => 'Task marked as pending.'
        ]);
    }

    /**
     * Get user's task statistics.
     */
    public function statistics(): JsonResponse
    {
        $userId = auth()->id();
        
        $stats = [
            'total_tasks' => Task::forUser($userId)->count(),
            'completed_tasks' => Task::forUser($userId)->completed()->count(),
            'pending_tasks' => Task::forUser($userId)->pending()->count(),
            'recent_tasks' => Task::forUser($userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Task statistics retrieved successfully.'
        ]);
    }
}