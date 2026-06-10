<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Schedule;
use App\Models\Notification;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('user_id', auth()->id())->get();
        return view('jadwal', compact('schedules'));
    }

    private function normalizeRequestTime(Request $request)
    {
        if ($request->has('start_time') && is_string($request->start_time)) {
            $parts = explode(':', $request->start_time);
            if (count($parts) >= 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                $request->merge(['start_time' => sprintf('%02d:%02d', (int)$parts[0], (int)$parts[1])]);
            }
        }
        if ($request->has('end_time') && is_string($request->end_time)) {
            $parts = explode(':', $request->end_time);
            if (count($parts) >= 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                $request->merge(['end_time' => sprintf('%02d:%02d', (int)$parts[0], (int)$parts[1])]);
            }
        }
    }

    public function store(Request $request)
    {
        $this->normalizeRequestTime($request);

        $request->validate([
            'topic' => 'required|string',
            'course' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'routine_type' => 'required|string',
            'routine_config' => 'nullable|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $schedule = Schedule::create([
            'user_id' => auth()->id(),
            'topic' => $request->topic,
            'course' => $request->course,
            'title' => $request->title,
            'description' => $request->description,
            'routine_type' => $request->routine_type,
            'routine_config' => $request->routine_config,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Jadwal Baru Dibuat 📅',
            'description' => "Kamu menjadwalkan sesi belajar '{$schedule->title}' untuk kelas '{$schedule->course}' pada jam {$schedule->start_time}.",
            'type' => 'schedule',
        ]);

        return response()->json(['message' => 'Jadwal berhasil disimpan', 'schedule' => $schedule], 201);
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $this->normalizeRequestTime($request);

        $request->validate([
            'topic' => 'required|string',
            'course' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'routine_type' => 'required|string',
            'routine_config' => 'nullable|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $schedule->update([
            'topic' => $request->topic,
            'course' => $request->course,
            'title' => $request->title,
            'description' => $request->description,
            'routine_type' => $request->routine_type,
            'routine_config' => $request->routine_config,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Jadwal Diperbarui ⚙️',
            'description' => "Sesi belajar '{$schedule->title}' telah berhasil diperbarui.",
            'type' => 'schedule',
        ]);

        return response()->json(['message' => 'Jadwal berhasil diperbarui', 'schedule' => $schedule], 200);
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Jadwal Dihapus 🗑️',
            'description' => "Sesi belajar '{$schedule->title}' telah dihapus dari jadwal belajarmu.",
            'type' => 'schedule',
        ]);

        $schedule->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus'], 200);
    }
}
