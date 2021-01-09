<div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weigh-bold mb-3">Products List</h2>
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th width="20%">Images</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Prices</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $index=>$product)
                        <tr>
                                <td> {{$index + 1}} </td>
                                <td> {{$product->name}} </td>
                                <td> <img src="{{asset('storage/images/'.$product->image)}}" alt="Product Image" class="img-fluid"> </td>
                                <td> {{$product->description}} </td>
                                <td> {{$product->qty}} </td>
                                <td> Rp. {{number_format($product->price, 0, ',','.')}} </td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                    <div style="display:flex; justify-content:center">
                        {{$products->links()}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weigh-bold mb-3">Create Products </h2>
                    <form wire:submit.prevent="store">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input wire:model="name" type="text" class="form-control" id="productName">
                            <!-- error handling -->
                            @error('name') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <div class="custom-file">
                                <input wire:model="image" type="file" class="custom-file-input" id="customFile">
                                <label for="cutomFile" class='custom-file-label'>Choose Image</label>
                                <!-- error handling -->
                                @error('image') <small class="text-danger">{{$message}}</small>@enderror 
                            </div>
                            @if($image)
                                <label class="mt-2">Image Preview: </label>
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview Image" class="img-fluid">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="DescriptionProduct">Product Description</label>
                            <textarea wire:model="description" class="form-control" id="DescriptionProduct"></textarea>
                            <!-- error handling -->
                            @error('description') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="qtyProduct">Product Quantity</label>
                            <input wire:model="qty" type="number" class="form-control" id="qtyProduct">
                            <!-- error handling -->
                            @error('qty') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="priceProduct">Product Price</label>
                            <input wire:model="price" type="number" class="form-control" id="priceProduct">
                            <!-- error handling -->
                            @error('price') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>

                    </form>


                    <h1>{{$name}}</h1>
                    <h1>{{$description}}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
