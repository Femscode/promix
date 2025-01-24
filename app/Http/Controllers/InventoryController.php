<?php

namespace App\Http\Controllers;

use App\Models\Common\Item;
use App\Models\Inventory;

use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        $data['items'] = Item::latest()->get();
        $data['inventories'] = Inventory::latest()->get();
        return view('inventory.index',$data);
       
    }

    public function singleInventory($id) {
        $data['item'] = $item = Item::find($id);
        $data['totalquantity'] = $item->totalquantity();
        $data['availablequantity'] = $item->quantity;
        $data['totalsold'] = $item->totalsold();
        $data['inventories'] = Inventory::where('item_id',$id)->latest()->get();
        $data['january'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 1, 1), Carbon::createFromDate(null, 1, 31)])->sum('quantity');
        $data['february'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 2, 1), Carbon::createFromDate(null, 2, 28)])->sum('quantity');
        $data['march'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 3, 1), Carbon::createFromDate(null, 3, 31)])->sum('quantity');
        $data['april'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 4, 1), Carbon::createFromDate(null, 4, 30)])->sum('quantity');
        $data['may'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 5, 1), Carbon::createFromDate(null, 5, 31)])->sum('quantity');
        $data['june'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 6, 1), Carbon::createFromDate(null, 6, 30)])->sum('quantity');
        $data['july'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 7, 1), Carbon::createFromDate(null, 7, 31)])->sum('quantity');
        $data['august'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 8, 1), Carbon::createFromDate(null, 8, 31)])->sum('quantity');
        $data['september'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 9, 1), Carbon::createFromDate(null, 9, 30)])->sum('quantity');
        $data['october'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 10, 1), Carbon::createFromDate(null, 10, 31)])->sum('quantity');
        $data['november'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 11, 1), Carbon::createFromDate(null, 11, 30)])->sum('quantity');
        $data['december'] = Inventory::where('item_id', $id)->where('type','purchase')->whereBetween('created_at', [Carbon::createFromDate(null, 12, 1), Carbon::createFromDate(null, 12, 31)])->sum('quantity');

       
        return view('inventory.singlerecord',$data);
       
    }
}
