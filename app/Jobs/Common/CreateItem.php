<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\ItemCreated;
use App\Events\Common\ItemCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class CreateItem extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Item
    {
        event(new ItemCreating($this->request));

        \DB::transaction(function () {
            
            $this->model = Item::create($this->request->all());

            $Inventory = Inventory::create([
                'item_id' => $this->model->id,
                'quantity' => $this->request['quantity'],
                'description' => $this->request['quantity']. ' quantities of ' .$this->model->name. " created",
                'user_id' => Auth::user()->id,
                'before' => 0,
                'after' => $this->request['quantity'],
                'type' => 'created'
            ]);

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'items');

                $this->model->attachMedia($media, 'picture');
            }

            $this->dispatch(new CreateItemTaxes($this->model, $this->request));
        });

        event(new ItemCreated($this->model, $this->request));

        return $this->model;
    }
}
