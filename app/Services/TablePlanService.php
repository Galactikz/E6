<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\ReceptionTable;
use App\Models\TablePlan;
use App\Models\TableSeat;
use App\Models\Wedding;
use Illuminate\Support\Facades\DB;

class TablePlanService
{
    public function createPlan(Wedding $wedding, array $data): TablePlan
    {
        return TablePlan::create(array_merge($data, [
            'wedding_id' => $wedding->id,
        ]));
    }

    public function createTable(TablePlan $plan, array $data): ReceptionTable
    {
        $table = ReceptionTable::create(array_merge($data, [
            'table_plan_id' => $plan->id,
        ]));

        $this->createSeats($table);

        return $table->load('seats');
    }

    private function createSeats(ReceptionTable $table): void
    {
        for ($i = 1; $i <= $table->capacity; $i++) {
            TableSeat::create([
                'reception_table_id' => $table->id,
                'seat_number' => $i,
            ]);
        }
    }

    public function updateTable(ReceptionTable $table, array $data): ReceptionTable
    {
        $oldCapacity = $table->capacity;
        $table->update($data);
        $newCapacity = $table->fresh()->capacity;

        if ($newCapacity > $oldCapacity) {
            for ($i = $oldCapacity + 1; $i <= $newCapacity; $i++) {
                TableSeat::firstOrCreate([
                    'reception_table_id' => $table->id,
                    'seat_number' => $i,
                ]);
            }
        } elseif ($newCapacity < $oldCapacity) {
            TableSeat::where('reception_table_id', $table->id)
                ->where('seat_number', '>', $newCapacity)
                ->delete();
        }

        return $table->fresh(['seats.guest']);
    }

    public function assignGuest(TableSeat $seat, ?int $guestId): TableSeat
    {
        if ($guestId !== null) {
            TableSeat::where('reception_table_id', '!=', $seat->reception_table_id)
                ->orWhere('id', '!=', $seat->id)
                ->where('guest_id', $guestId)
                ->update(['guest_id' => null]);
        }

        $seat->update(['guest_id' => $guestId]);
        return $seat->fresh(['guest']);
    }

    public function moveGuest(int $guestId, int $targetSeatId): TableSeat
    {
        return DB::transaction(function () use ($guestId, $targetSeatId) {
            TableSeat::where('guest_id', $guestId)->update(['guest_id' => null]);

            $targetSeat = TableSeat::findOrFail($targetSeatId);
            $targetSeat->update(['guest_id' => $guestId]);

            return $targetSeat->fresh(['guest']);
        });
    }

    public function getPlanState(TablePlan $plan): array
    {
        $plan->load(['tables.seats.guest.group']);

        return [
            'id' => $plan->id,
            'name' => $plan->name,
            'room_dimensions' => $plan->room_dimensions,
            'tables' => $plan->tables->map(fn($table) => [
                'id' => $table->id,
                'name' => $table->name,
                'shape' => $table->shape,
                'capacity' => $table->capacity,
                'position' => $table->position,
                'color' => $table->color,
                'available_seats' => $table->available_seats,
                'seats' => $table->seats->map(fn($seat) => [
                    'id' => $seat->id,
                    'seat_number' => $seat->seat_number,
                    'guest_id' => $seat->guest_id,
                    'guest' => $seat->guest ? [
                        'id' => $seat->guest->id,
                        'full_name' => $seat->guest->full_name,
                        'group' => $seat->guest->group?->name,
                        'meal_type' => $seat->guest->meal_type,
                    ] : null,
                ]),
            ]),
        ];
    }
}
