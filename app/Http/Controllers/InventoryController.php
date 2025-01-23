<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        $data['inventories'] = Inventory::latest()->get();
        return view('inventory.index',$data);
       
    }

    public function singleInventory($id) {
        
        $data['inventories'] = Inventory::where('item_id',$id)->latest()->get();
        return view('inventory.index',$data);
       
    }
}
