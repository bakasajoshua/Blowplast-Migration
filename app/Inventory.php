<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Temps\Temp;

class Inventory extends BaseModel
{
    protected $table = 'Item Master';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetInventory";

    private $endpointColumns = [
        'Item_No' => 'Item_x0020_No.',
        'Item_Description' => 'ItemName',
        // '' => 'Item_x0020_Group_x0020_Code',
        // '' => 'Item_x0020_Group_x0020_Name',
        // '' => 'UOM',
    ];
    private $chunkQty = 100;

    public function synchItems()
    {
        ini_set("memory_limit", "-1");
        $synchData = $this->synch($this->functionCall, $this->endpointColumns);
        $items = [];

        foreach ($synchData as $key => $item) {
        	$item['Company_Code'] = 'BUL';
            $items[] = $item;
        }

        $chunks = collect($items);
        foreach($chunks as $key => $chunk){
            Inventory::insert($chunk);
        }
        return true;
    }

    public static function scheduledItemImportKE($verbose=false)
    {
        if ($verbose)
            echo "==> Pull the Items " . date('Y-m-d H:i:s') . "\n";
        $items = Inventory::where('Company_Code', 'BPL')->get();
        if ($verbose)
            echo "==> Item deletion started " . date('Y-m-d H:i:s') . "\n";
        foreach ($items as $key => $item) {
            $item->delete();
        }
        if ($verbose)
            echo "==> Item deletion complete " . date('Y-m-d H:i:s') . "\n";

        ini_set("memory_limit", "-1");
        if ($verbose)
            echo "==> Pulling Unique items from sales " . date('Y-m-d H:i:s') . "\n";
        $sales = \App\Temps\Temp::get()->unique('itm_id');
        if ($verbose)
            echo "==> Pulling Unique items from sales complete " . date('Y-m-d H:i:s') . "\n";


        if ($verbose)
            echo "==> Preparing Items Data " . date('Y-m-d H:i:s') . "\n";
        $data = [];
        foreach($sales as $sale) {
            $data[] = [
                    'Item_No' => $sale->itm_id,
                    'Item_Description' => $sale->itm_desc,
                    'Company_Code' => 'BPL',
                    'Dimension1' => self::generateCategory($sale->wh_nm),
                ];
        }
        if ($verbose)
            echo "==> Preparing Items Data complete " . date('Y-m-d H:i:s') . "\n";

        if ($verbose)
            echo "==> Inserting Item master data " . date('Y-m-d H:i:s') . "\n";
        $chunks = collect($data)->chunk(20);
        foreach ($chunks as $key => $chunk) {
            Inventory::insert($chunk->toArray());
        }
        if ($verbose)
            echo "==> Inserting Item master data complete " . date('Y-m-d H:i:s') . "\n";
        return true;
    }

    public static function generateCategory($data)
    {
        $wh_cat_arr = explode("-", $data);
        return $wh_cat_arr[0];
    }

    public static function updateKECategories()
    {
        $KEItems = Inventory::where('Company_Code', 'BPL')->get();
        foreach ($KEItems as $key => $item) {
            dd($item);
            $sale = \App\Temps\Temp::where('itm_desc',$item->Item_Description)->get();
            if (!$sale->isEmpty()){
                $wh_cat = $sale->first()->wh_nm;
                $wh_cat_arr = explode("-", $wh_cat);
                $item->Dimension1 = $wh_cat_arr[0];
                $item->save();
            }
            // dd($sale);
            
        }
        return true;
    }
}