<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product as ProductModel; //include model (database) Product disini agar bisa di panggil di dalam fungsi
use Carbon\Carbon; //fungsi carbon adalah membuat waktu sekarang
use Livewire\WithPagination; //digunakan untuk membuat paginate

class Cart extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; //agar tidak terjadi bug di laravel 8 karen kita menggunakan bootstrap
    public $tax = "0%"; //dimana ini adalah discount 
    public $search = '';

    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {   
        //variabel produk yang isinya semua product yang di database di urutkan berdasarkan dibuat dan di urutkan secara descendan atau diurutkan dari terkecil ke yang paling besar
        $products = ProductModel::where('name', 'like', '%'.$this->search.'%')->orderBy('created_at', 'DESC')->paginate(12); 


        $condition = new \Darryldecode\Cart\CartCondition([
            'name' => 'pajak',
            'type' => 'tax',
            'target' => 'total',
            'value' => $this->tax,
            'order' => 1
        ]);

        //ambil session user berdasarkan id
        \Cart::session(Auth()->id())->condition($condition);

        $items = \Cart::session(Auth()->id())->getContent()->sortBy(function ($cart){
            return $cart->attributes->get('added at'); 
        });
        //cek apakah cart kosong atau tidak
        //apabila kosong maka 
        if(\Cart::isEmpty()){
            $cartData = [];
        }
        //dan apabila tidak kosong
        else
        {
            //apabila tidak kosong maka kita perulangkan dengan foreach 
            foreach($items as $item){
                $cart[] = [
                    'rowId' => $item->id, //ini agar idnya unik
                    'name' => $item->name, //untuk menambahkan nama 
                    'qty' => $item->quantity,
                    'pricesingle' => $item->price,
                    'price' => $item->getPriceSum(), //agar menambahkan jumlah atau harga
                ];
            }
            // kemudian tampung di variabel cartData isi dari cartnya
            $cartData = collect($cart);
        }

        $sub_total = \Cart::session(Auth()->id())->getSubTotal();
        $total = \Cart::session(Auth()->id())->getTotal();

        $newCondition = \Cart::session(Auth()->id())->getCondition('pajak');
        $pajak = $newCondition->getCalculatedValue($sub_total);

        $summary = [
            'sub_total' => $sub_total,
            'pajak' => $pajak,
            'total' => $total
        ];


        return view('livewire.cart', [
            'products' => $products,
            'carts' => $cartData,
            'summary' => $summary
        ]);
    }
    
    public function addItem($id){
        $rowId = "Cart".$id;
        $cart = \Cart::session(Auth()->id())->getContent();
        $cekItemId = $cart->whereIn('id', $rowId);

        if($cekItemId->isNotEmpty()){
            \Cart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => 1
                ]
            ]);
        }
        else
        {
            $product = ProductModel::findOrFail($id);
            \Cart::session(Auth()->id())->add([
                'id' => "Cart".$product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => [
                    'added_at' => Carbon::now()
                ],
            ]);
        }
    }

    public function increaseItem($rowId){
        // pertama kita ambil id product 
        // kemudian ambil semua isi product
        // ambil session login id
        // cek eloquent id
        $idProduct = substr($rowId, 4, 5);
        $product = ProductModel::find($idProduct);
        $cart = \Cart::session(Auth()->id())->getContent();
        $checkItem = $cart->whereIn('id', $rowId);
        
        // kita cek apakah value quantity yang ingin kita tambah sama dengan quantity product yang tersedia
        if($product->qty == $checkItem[$rowId]->quantity){
            session()->flash('error', 'Kuantiti terbatas');
        }
        else{
            \Cart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => 1
                ]
            ]);
        }

    }
    
    public function decreaseItem($rowId){
        $cart = \Cart::session(Auth()->id())->getContent();
        $checkItem = $cart->whereIn('id', $rowId);
        if($checkItem[$rowId]->quantity == 1)
        {
            $this->removeItem($rowId);
            // \Cart::session(Auth()->id())->remove($rowId);
        }
        else
        {
            \Cart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => -1
                ]
            ]);
        }
    }

    public function removeItem($rowId){
        \Cart::session(Auth()->id())->remove($rowId);
    }

    public function addTax(){
        $this->tax = "+10%";
    }

    public function removeTax(){
        $this->tax = "0%";
    }
}
