<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\ItemUpdated;
use App\Events\Common\ItemUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class UpdateItem extends Job implements ShouldUpdate
{
    public function handle(): Item
    {
        event(new ItemUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $quantity = $this->model->quantity;
            $this->model->update($this->request->all());
            $Inventory = Inventory::create([
                'item_id' => $this->model->id,
                'user_id' => Auth::user()->id,
                'quantity' => $this->request['quantity'],
                'description' => $this->model->name . ' quantity updated to '. $this->request['quantity'].' quantities',
                'before' => $quantity,
                'after' => $this->request['quantity'],
                'type' => 'updated'
            ]);

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'items');
                $this->model->attachMedia($media, 'picture');
            }
            $this->deleteRelationships($this->model, ['taxes']);
            $this->dispatch(new CreateItemTaxes($this->model, $this->request));
        });

        event(new ItemUpdated($this->model, $this->request));

        return $this->model;
    }
}
