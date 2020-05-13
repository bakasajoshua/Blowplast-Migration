<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}