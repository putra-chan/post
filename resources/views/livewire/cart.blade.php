<div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6"><h2 class="font-weight-bold">Products List</h2></div>
                        <div class="col-md-6">
                            <input wire:model="search" type="text" class="form-control" placeholder="Search.......">
                        </div>
                    </div>
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{asset('storage/images/'.$product->image)}}" alt="product" style="objec-fit:content; width:100%; height:125px">
                                    </div>
                                    <div class="card-footer">
                                        <h6 class="text-center font-weight-bold">{{$product->name}}</h6>
                                        <button wire:click="addItem({{$product->id}})" class="btn btn-primary btn-block">Add To Cart</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                        <h4 class="text-danger container-fluid">No found product</h4>
                        @endforelse
                    </div>
                    <div style="display:flex; justify-content:center">
                        {{$products->links()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Cart</h2>
                    <table class="table table-sm table-bordered table-striped table-hovered container-fluid">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Remove Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($carts as $index=>$cart)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>
                                        {{$cart['name']}} 
                                        <br>
                                        <span href="#" wire:click="decreaseItem('{{$cart['rowId']}}')" class="font-weight-bold" style="cursor:pointer; font-size: 12px">- </span> <!-- Jangan pernah makek a (angkor) setelah menginstal turbolink gunakan span -->
                                        {{$cart['qty']}} 
                                        <span href="#" wire:click="increaseItem('{{$cart['rowId']}}')" class="font-weight-bold" style="cursor:pointer; font-size: 12px"> +</span>
                                        <br>
                                        <p class="text-danger">
                                            @if(session()->has('error'))
                                            {{session('error')}}
                                            @endif
                                        </p>
                                    </td> 
                                    <td>Rp. {{ number_format($cart['price'], 0, ',', '.') }}</td>
                                    <td><button wire:click="removeItem('{{$cart['rowId']}}')" class="btn btn-danger">X</button></td>
                                    
                                </tr>
                            @empty
                                <td colspan="5"><h6 class="text-center">Empty Cart</h6></td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <h4 class="font-weight-bold">Cart Summary</h4>
                    <h5 class="font-weight-bold">Sub Total: Rp. {{ number_format($summary['sub_total'], 0, ',', '.') }}</h5>
                    <h5 class="font-weight-bold">Tax: Rp. {{ number_format($summary['pajak'], 0, ',', '.') }}</h5>
                    <h5 class="font-weight-bold">Total: Rp. {{ number_format($summary['total'], 0, ',', '.') }}</h5>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button wire:click="addTax" class="btn btn-primary btn-block">Add Tax</button>
                    <button wire:click="removeTax" class="btn btn-danger btn-block">Remove Tax</button>
                    <button type="submit" class="btn btn-success btn-block">Simapng</button>
                </div>
            </div>
        </div>
    </div>
</div>
