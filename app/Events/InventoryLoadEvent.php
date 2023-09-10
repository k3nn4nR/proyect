<?php

namespace App\Events;

use App\Models\Inventory;
use App\Models\Warehouse;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class InventoryLoadEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct($payment)
    {
        DB::beginTransaction();
        try {
            if($payment->items->isNotEmpty()){
                foreach($payment->items as $item){
                    if($item->inventories->isNotEmpty()){
                        $last = $item->inventories->last();
                        Inventory::create([
                            'warehouse_id'  => $last->warehouse_id,
                            'inventoryable_id'  => $last->inventoryable_id,
                            'inventoryable_type'    => $last->inventoryable_type,
                            'amount'    => $last->amount + $item->pivot->amount,
                        ]);
                        $last->delete();
                    }else{
                        $item->inventories()->create([
                            'amount' => $item->pivot->amount,
                            'warehouse_id' => Warehouse::get()->first()->id,
                        ]);
                    }
                }
            }
            if($payment->types->isNotEmpty()){
                foreach($payment->types as $type){
                    if($type->inventories->isNotEmpty()){
                        $last = $type->inventories->last();
                        Inventory::create([
                            'warehouse_id'  => $last->warehouse_id,
                            'inventoryable_id'  => $last->inventoryable_id,
                            'inventoryable_type'    => $last->inventoryable_type,
                            'amount'    => $last->amount + $type->pivot->amount,
                        ]);
                        $last->delete();
                    }else{
                        $type->inventories()->create([
                            'amount' => $type->pivot->amount,
                            'warehouse_id' => Warehouse::all()->first()->id,
                        ]);
                    }
                }
            }
            DB::commit();
            return true;
        } catch(\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
