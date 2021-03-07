<?php

namespace App;

use App\Temps\Temp;
use Illuminate\Database\Eloquent\Model;
use DB;

class SalesInvoiceCreditMemoHeader extends BaseModel
{
    protected $table = 'Sales Invoice Credit Memo Headers';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetInvoiceCreditHeader";

    private $endpointColumns = [
			'Invoice_Credit_Memo_No' => 'Document_x0020_No',
			'SI_Document_No' => 'Document_x0020_No',
			'Sell-To-Customer-No' => 'Customer_x0020_No.',
			'Sell-To-Customer-Name' => 'Customer_x0020_Name',
			'Bill-To-Customer-No' => 'Customer_x0020_No.',
			'Bill-To-Customer-Name' => 'Customer_x0020_Name',
			'SI_Posting_Date' => 'Posting_x0020_Date',
			'SI_Due_Date' => 'Due_x0020_Date',
			'Company_Code' => 'Company_x0020_Code',
			'Type' => 'Type',
			'Total_Amount_Excluding_Tax' => 'Total_x0020_Amount_x0020_Excluding_x0020_Tax',
			'Total_Amount_Including_Tax' => 'Total_x0020_Amount_x0020_Including_x0020_Tax',
			'Currency_Code' => 'Currency_x0020_Code',
		];
    private $chunkQty = 100;

    public function lines()
    {
        return $this->hasMany(SalesInvoiceCreditMemoLine::class, 'Invoice_Credit_Memo_No', 'Invoice_Credit_Memo_No');
    }

    public static function insertChunk($chunks)
    {
        foreach ($chunks as $key => $data) {
            SalesInvoiceCreditMemoHeader::insert($data->toArray());
        }
        return true;
    }

    public function synchHeaders($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        return SalesInvoiceCreditMemoHeader::insertChunk($chunks);
    }

    public function synchHeadersKE()
    {
        ini_set("memory_limit", "-1");
        echo "==> Deleting KE sales data " . date('Y-m-d H:i:s') . "\n";
        foreach (SalesInvoiceCreditMemoHeader::where('Company_Code', 'BPL')->get() as $key => $header) {
            $header->delete();
        }
        foreach (SalesInvoiceCreditMemoLine::where('Company_Code', 'BPL')->get() as $key => $header) {
            $header->delete();
        }
        echo "==> Finished deleting KE sales data " . date('Y-m-d H:i:s') . "\n";
        // echo "==> Start pulling Data " . date('Y-m-d H:i:s') . "\n";
        // $data = DB::connection('oracle')->select('select * from SLS$INVOICE$REG$DTL$VW');
        // echo "==> Finished pulling Data " . date('Y-m-d H:i:s') . "\n";
        // echo "==> Start  Data " . date('Y-m-d H:i:s') . "\n";
        // foreach ($data as $key => $value) {
        //     $value = (array) $value;
        //     Temp::insert($value);
        // }
        // echo "==> Finished inserting Data " . date('Y-m-d H:i:s') . "\n";
        echo "==> Inserting data in warehouse " . date('Y-m-d H:i:s') . "\n";
        echo "==> Inserting the headers " . date('Y-m-d H:i:s') . "\n";
        $this->insertKESales(false, true);
        echo "==> Finished inserting the headers " . date('Y-m-d H:i:s') . "\n";
        echo "==> Inserting the lines " . date('Y-m-d H:i:s') . "\n";
        $lines = new SalesInvoiceCreditMemoLine;
        $lines->insertKESalesLines();
        echo "==> Finished inserting the lines " . date('Y-m-d H:i:s') . "\n";
        echo "==> Finished inserting the warehouse data " . date('Y-m-d H:i:s') . "\n";
        return true;
    }

    public function insertKESales($empty=false, $verbose=false)
    {
        ini_set("memory_limit", "-1");
        $data = [];
        if ($empty) {
            if ($verbose)
                echo "==> Deleting the existing data\n";
            SalesInvoiceCreditMemoHeader::truncate();
            if ($verbose)
                echo "==> Finished deleting the existing data\n";
        }
        if ($verbose)
            echo "==> Inserting data into the Warehouse\n";
        foreach (Temp::get() as $key => $sales) {
            if (SalesInvoiceCreditMemoHeader::where('Invoice_Credit_Memo_No', $sales->invoice_id)->get()->isEmpty())
            {
                $data[] = [
                    'Invoice_Credit_Memo_No' => $sales->invoice_id,
                    'SI_Document_No' => $sales->invoice_doc_id,
                    'Sell-To-Customer-No' => $sales->eo_nm,
                    'Sell-To-Customer-Name' => $sales->eo_nm,
                    'Bill-To-Customer-No' => $sales->eo_nm,
                    'Bill-To-Customer-Name' => $sales->eo_nm,
                    'SI_Posting_Date' => date('Y-m-d', strtotime($sales->invoice_doc_dt)),
                    // 'SI_Due_Date' => 'Due_x0020_Date',
                    'Company_Code' => 'BPL',
                    'Type' => $sales->type,
                    'Total_Amount_Excluding_Tax' => $sales->itm_amt_gs,
                    'Total_Amount_Including_Tax' => $sales->net_amnt,
                    'Currency_Code' => $sales->curr_sp,
                ];
                
            }
        }
        $chunks = collect($data)->chunk($this->chunkQty);
        foreach ($chunks as $key => $chunk) {
            SalesInvoiceCreditMemoHeader::insert($chunk->toArray());
        }

        if ($verbose){
            echo "==> Finished inserting data into the Warehouse\n";
            // echo "==> Inserting the lines data into the warehouse\n";
        }

        // $lines = new SalesInvoiceCreditMemoLine;
        // $lines->insertKESalesLines(true);
        // if ($verbose)
        //     echo "==> Finished inserting line data into the Warehouse\n";

    }

    public function insertUGData()
    {
        echo "==> Deleting UG Sales header data " . date('Y-m-d H:i:s') . "\n";
        foreach(SalesInvoiceCreditMemoHeader::where('Company_Code', 'BUL')->get() as $header){
            $header->delete();
        }foreach(SalesInvoiceCreditMemoLine::where('Company_Code', 'BUL')->get() as $line){
            $line->delete();
        }
        echo "==> Finishing deleting the UG sales data " . date('Y-m-d H:i:s') . "\n";
        echo "==> Pulling temp data " . date('Y-m-d H:i:s') . "\n";
        $sales = TempUGSalesHeader::get();
        echo "==> Inserting headers data " . date('Y-m-d H:i:s') . "\n";
        $data = [];
        foreach ($sales as $key => $sale) {
            $data[] = [
                'Invoice_Credit_Memo_No' => $sale->Document_No,
                'SI_Document_No' => $sale->Document_No,
                'Sell-To-Customer-No' => $sale->Customer_No,
                'Sell-To-Customer-Name' => $sale->Customer_Name,
                'Bill-To-Customer-No' => $sale->Customer_No,
                'Bill-To-Customer-Name' => $sale->Customer_Name,
                'SI_Posting_Date' => $sale->Posting_Date,
                'SI_Due_Date' => $sale->Due_Date,
                'Company_Code' => $sale->Company_Code,
                'Type' => $sale->Type,
                'Total_Amount_Excluding_Tax' => $sale->Total_Amount_Excluding_Tax,
                'Total_Amount_Including_Tax' => $sale->Total_Amount_Including_Tax,
                'Currency_Code' => $sale->Currency_Code,
            ];
        }

        $chunks = collect($data)->chunk($this->chunkQty);
        foreach ($chunks as $key => $chunk) {
            SalesInvoiceCreditMemoHeader::insert($chunk->toArray());
        }
        echo "==> Finished inserting headers data " . date('Y-m-d H:i:s') . "\n";
        echo "==> Pulling temp lines data " . date('Y-m-d H:i:s') . "\n";
        $saleslines = TempUGSalesLine::get();
        echo "==> Inserting lines data " . date('Y-m-d H:i:s') . "\n";
        $data = [];
        foreach ($saleslines as $key => $line) {
            $data[] = [
                'SI_Li_Line_No' => $line->Entry_No . '-' . $sale->LineNum,
                'Invoice_Credit_Memo_No' => $line->Document_No,
                'SI_Li_Document_No' => $line->Document_No,
                'Item_No' => $line->ItemCode,
                'Item_Weight_kg' => $line->Item_Weight_in_kg,
                'Item_Price_kg' => $line->Item_Price_in_kg,
                'Item_Description' => $line->Item_Description,
                'Quantity' => $line->Quantity,
                'Unit_Price' => $line->Unit_Price,
                'Unit_Cost' => $line->Unit_Cost,
                'Company_Code' => $line->Company_Code,
                'Currency_Code' => $line->Currency_Code,
                'Type' => $line->Type,
                'Total_Amount_Excluding_Tax' => $line->Total_Amount_Excluding_Tax,
                'Total_Amount_Including_Tax' => $line->Total_Amount_Including_Tax,
                'Sales_Unit_of_Measure' => $line->Sales_Unit_of_Measure,
                'SI_Li_Posting_Date' => $line->Posting_Date,
                'SI_Li_Due_Date' => $line->Due_Date,
            ];
        }
        $chunks = collect($data)->chunk($this->chunkQty);
        foreach ($chunks as $key => $chunk) {
            SalesInvoiceCreditMemoLine::insert($chunk->toArray());
        }
        echo "==> Finished inserting lines data " . date('Y-m-d H:i:s') . "\n";
        return true;
    }

    public static function fix()
    {
        foreach (self::missing_customer() as $key => $customer) {
            $existing_customer = Customer::where('Customer_Name', $customer)->get();

            if ($existing_customer->isEmpty()) {
                $db_customer = Customer::create([
                                    'Customer_No' => round(microtime(true) * 1000),
                                    'Customer_Name' => $customer,
                                ]);
            } else {
                $db_customer = $existing_customer->first();
            }
            
            $headers = SalesInvoiceCreditMemoHeader::where('Sell-To-Customer-Name', $customer)->get();

            $value_stream = NULL;
            foreach ($headers as $key => $header) {
                $data = [
                        'Sell-To-Customer-No' => $db_customer->Customer_No,
                        'Bill-To-Customer-No' => $db_customer->Customer_No,
                    ];
                $header->fill($data);
                $header->save();
                $lines = $header->lines;
                if ($lines->isEmpty())
                    $value_stream = $lines->first()->Value_Stream;
            }
            if (!$db_customer->Value_Stream) {
                $db_customer->Value_Stream = $value_stream;
                $db_customer->save();    
            }
            
        }
        return true;
    }



    public static function missing_customer() {
        return ['TECHNO RELIEF SERVICES LTD.', 'Murphy Chemicals (Ea) Ltd(Dr)', 'Kenya Oil Company Ltd(Dr)', 'Kapa Oil Refineries Ltd. A-C 1(Dr)', 'BLOWPLAST (UGANDA) LTD', 'SYNERGY LUBRICANT SOLUTIONS LIMITED', 'USD-FCS - GT TRADING & INVESTMENTS CO. LTD', 'FCS - HANSE IMPEX COMPANY LIMITED', 'R & R PLASTIC LIMITED', 'Bidco Africa Limited(Dr)', 'Edible Oil Products Ltd(Dr)', 'GIL OIL COMPANY LTD', 'FCS - HEENA PROMOTIONS', 'TANIA EAST AFRICA LIMITED', 'RUBIS ENERGY KENYA PUBLIC LIMITED COMPANY', 'FCS - AGREVAL EAST AFRICA LIMITED', 'FCS - VARSANI IMPEX LIMITED', 'Darfords Industries Limited(Dr)', 'Norbrook Kenya Limited(Dr)', 'USD-SARRCHEM INTERNATIONAL KENYA LTD', 'PROTEA CHEMICALS KENYA LIMITED', 'FCS - ETEMO BIN COLLECTOR', 'HIGHRIDGE PHARMACEUTICALS LTD A-C 1 1(DR)', 'FCS - ZEREGABER GENERAL TRADING LTD', 'Rift Valley Products Ltd(Dr)', 'Pwani Oil Products Ltd(Dr)', 'Suprima Industries (Kenya) Ltd(Dr)', 'QUANTUM LUBRICANTS (E.A) LIMITED', 'USD-POPULATION SERVICES INT-ZIMBABWE', 'USD - FCS - KIFMAS COMPANY LIMITED', 'SUPERSLEEK LTD', 'Sample A-C(Dr)', 'Pz Cussons East Africa Ltd.(Dr)', 'Interconsumer Products Ltd.(Dr)', 'KANSAI PLASCON KENYA LIMITED', 'FCS - VICOOL GROUP LIMITED', 'CROWN PAINTS RWANDA LTD(DR)', 'SEASON GLOBAL LTD', 'USD-GOLDEN AFRICA KENYA LIMITED (DR)', 'FCS - ISH PLAST LIMITED', 'MEDISEL KENYA LTD(DR)', 'TAPIOCA LIMITED', 'Fcs - Lynntech Chemicals Equipment Ltd(Dr)', 'USD-ORBIT PRODUCT AFRICA LIMITED', 'ULTRAVETIS ZAMBIA LTD', 'Githunguri Dairy Farmers Co-Op Society Ltd(Dr)', 'Pan Africa Chemicals Ltd(Dr)', 'FCS - BITUTECH LIMITED', 'KENYA STATIONERS LIMITED', 'FCS - RUBI PLASTICS INDUSTRIS LIMITED', 'Highchem East Africa Ltd(Dr)', 'ARAX MILLS LTD', 'Unoplast Tanzania Limited(Dr)', 'Twiga Chemicals Industries Ltd(Dr)', 'KENYA PIPELINE COMPANY LIMITED', 'Syngenta East Africa Ltd(Dr)', 'Friendly Polymars Ltd(Dr)', 'FCS - PALM HOUSE DAIRIES LIMITED.', 'THIKA WAX WORKS LIMITED', 'SPECTRA CHEMICALS (K) LTD', 'Unilever Kenya Ltd.(Dr)', 'Fcs - Mulili Kaleso(Dr)', 'FCS - MEDCURE HEALTHCARE LTD', 'BRENNTAG KENYA LTD', 'ODEX CHEMICALS LTD', 'FCS - LASAP (K) LTD', 'PREMIER FOOD LTD', 'Techno Relief Services (Epz) Ltd(Dr)', 'Star Plastics Ltd(Dr)', 'FCS - TIRELESS EFFORT ENTERPRISES', 'TROPICAL HEAT LTD', 'Biodeal Laboratories Ltd A-C 2(Dr)', 'SIKA EAST AFRICA LTD', 'City General Stores Limited(Dr)', 'FCS - PREMBHAV PLASTICS LIMITED', 'NAIROBI PLASTIC LTD', 'ULTRAVETIS TANZANIA LTD', 'FCS - EIGHTEEN HOLDING LIMITED', 'MR. GREEN TRADING AFRICA KENYA (DR) LIMITED', 'Inter Beauty Products Ltd(Dr)', 'Tri Clover Inds (K) Ltd(Dr)', 'Lacheka Lubricants Ltd A-C 2(Dr)', 'ORBIT PRODUCTS AFRICA LIMITED', 'CROWN PAINTS KENYA LTD(DR)', 'Thermopak Ltd(Dr)', 'Well Stock (K) Ltd(Dr)', 'Dawa Limited A-C 2(Dr)', 'FCS - JESUS TEACHING MINISTRY', 'Impact Chemicals(Dr)', 'KENROM CHEMICALS KENYA LIMITED', 'FCS - MARLOW LINK TIMBER PRODUCTS LTD', 'Cash Sales Bpl 6(Dr)', 'Kenpoly Manufacturers Ltd.(Dr)', 'Kel Chemicals Ltd(Dr)', 'GULF ENERGY HOLDINGS LIMITED', 'JAY RAJ ENTERPRISES LTD(DR)', 'Solvochem East Africa Ltd(Dr)', 'Fcs - Organic Solutions Ltd(Dr)', 'Razco Ltd.(Dr)', 'FCS - PINCAT INDUSTRIES LTD', 'FCS - MREMBO BEAUTY PRODUCTS LTD', 'Flame Tree Africa Ltd(Dr)', 'FCS - NIA COSMETIC LIMITED', 'FCS - CHEMPLUS HOLDINGS LTD', 'DINLAS PHARMA EPZ LIMITED', 'FARMERS CENTRE LTD (KSH)', 'FCS - E.M TECHNOLOGIES LTD', 'Ecolab East Africa(Dr)', 'FCS - NATURAL EXTRACT INDUSTRIES LTD', 'TROPIKAL BRANDS (AFRICA) LTD', 'TRUFOODS LTD.', 'Powerex Lubricants Ltd(Dr)', 'Trade House Africa Ltd(Dr)', 'Monasa Nets (Kenya) Ltd(Dr)', 'Fcs - Jungle Macs Epz Ltd(Dr)', 'FCS - PRIYANN ENTERPRISES LTD', 'MVITA OILS LIMITED', 'FCS - DIVANNS LIMITED', 'Diamond Industries Ltd(Dr)', 'Ashut Engineers Ltd(Dr)', 'Dera Chemical Industries (K) Ltd(Dr)', 'AESTHETICS  LTD.', 'ULTRAVETIS EAST AFRICA LIMITED', 'PREMIER FOOD INDUSTRIES LTD', 'BE ENERGY LIMITED', 'Angelica Industries Ltd(Dr)', 'FCS - GLOBAL SLACKERS ENTERPRISES LIMITED', 'KOBIAN KENYA LIMITED', 'FARMBASE LTD-KSHS', 'FCS - RUSHABH INDUSTRIES LIMITED', 'Gulf Energy Ltd(Dr)', 'Fcs - Salwa Kenya Limited(Dr)', 'Flamingo Horticulture Kenya Limited(Dr)', 'VETCARE KENYA LIMITED(DR)', 'FARMBASE LTD', 'Beiersdorf East Africa Ltd.(Dr)', 'FCS - MAZ INTERNATIONAL LIMITED(DR)', 'Brookside Dairy Ltd.(Dr)', 'Polysynthetics Eastern Africa Ltd(Dr)', 'Skylink Oil Limited(Dr)', 'Kentainers Ltd(Dr)', 'Cooper K-Brands Limited(Dr)', 'VALENCIA COSMETICS LTD', 'FARMERS CENTRE LTD', 'FCS - BETA WORLD HOLDING', 'Shell Lubricants Kenya Limited(Dr)', 'Cash Sales-Bpl 2 Msa(Dr)', 'USD-TASCO INDUSTRIES LIMITED', 'Associated Battery Manufacturers (E.A) Ltd(Dr)', 'USD-FCS - ORIBUT COMPANY LIMITED', 'Canon Chemicals Ltd(Dr)', 'HACO INDUSTRIES KENYA LTD', 'Total Kenya Ltd(Dr)', 'Pyrethrum Board Of Kenya(Dr)', 'GOLDEN AFRICA KENYA LIMITED(DR)', 'WEBUYE WHOLESALERS', 'Agro-Chemical AND Food Co. Ltd(Dr)', 'BAYER EAST AFRICA  LTD', 'USD-BLOWPLAST UGANDA LTD', 'EAST AFRICAN SEED CO. LTD', 'VIVEK INVESTMENTS LTD', 'FCS - RELIEFLINE (KENYA) LIMITED-KSH', 'PREMIER FOOD LTD DONT USE THIS ACCOUNT', 'MCDAVE HOLDINGS LTD.', 'Top Food E.A. Ltd(Dr)', 'SANPAC AFRICA LIMITED', 'United Millers Ltd(Dr)', 'SEASON GENERAL TRADING LTD.', 'AGRISCOPE (AFRICA) LIMITED', 'FCS - GREEN CROP PROTECTION AFRICA LIMITED', 'AMALO COMPANY LIMITED', 'Menengai Oil Refineries Ltd(Dr)', 'The Real Ipm Company (K) Ltd(Dr)', 'DEEKAY RELIEFT LTD - LOCAL', 'FCS - MEPRON ENTERPRISES LIMITED', 'USD-MADAPLAST SARL'];
    }
}