<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class StockController extends Controller
{

    public function indexJson(Request $request)
    {

        $customer = Stock::whereStatus('AC')
            ->where("name", "LIKE", "%{$request->input('search')}%")->latest()->limit(5)->get();

        return response()->json(
            $customer,
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // \Illuminate\Support\Facades\DB::enableQueryLog();

        $Stocks = Stock::with('category')->whereStatus('AC')->latest('id')->paginate(15);

        // dd(\Illuminate\Support\Facades\DB::getQueryLog());
        return view('stock.index')->with('stocks', $Stocks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::whereStatus('AC')->get();

        return view('stock.create')->with('category', $category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price_sell' => 'required|numeric',
            'price_buy' => 'required|numeric',
            'category_id' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        if ($validated) {
            $stock = new Stock;
            $stock->name = $request->name;
            $stock->desc = $request->desc;
            $stock->price_buy = $request->price_buy;
            $stock->price_sell = $request->price_sell;
            $stock->status = 'AC';
            $stock->amount = $request->amount;
            $stock->category_id = $request->category_id;
            $stock->save();
            // return redirect()->route('stock.edit', $stock->id)->with('message', $stock->name . ' Sukses di buat');
            return back()->with('message', $stock->name . ' Sukses di buat');
        }

        return back()->withErrors($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Transaction the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function transaction(Stock $stock)
    {

        $company = Company::find(1)->first();

        return view('stock.transaction')->with('company', $company);
    }

    public function saveTransaction(Request $request)
    {

        //Parse array dari input
        $stocks = json_decode($request->details, true);
        // dd($request->all());


        // Buar headernya
        $invoice = new Invoice();
        // $invoice->no = $request->header;
        $invoice->date = $request->date;
        $invoice->due = $request->duedate;
        $invoice->customer_id = $request->customer;
        $invoice->amount = $request->amount ?? 100;
        $invoice->save();

        // loop lalu insert ke detail
        foreach ($stocks as $stock) {
            $detail = new InvoiceDetail();
            $detail->invoice_no = $invoice->no;
            $detail->stock_id = $stock['id'];
            $detail->quantity = $stock['qty'];
            $detail->save();
            // var_dump($stock['id']);
        }

        return back()->with('message', $invoice->no . ' Sukses di buat');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        $category = Category::whereStatus('AC')->get();

        return view('stock.edit')->with('stock', $stock)->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price_sell' => 'required|numeric',
            'price_buy' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        if ($validated) {
            $stock->name = $request->name;
            $stock->desc = $request->desc;
            $stock->price_buy = $request->price_buy;
            $stock->price_sell = $request->price_sell;
            $stock->category_id = $request->category_id;
            $stock->save();
            return back()->with('message', $stock->name . ' Sukses di ubah');
        }

        return back()->withErrors($validated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
