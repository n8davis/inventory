@extends('layouts.app')
@section('content')
    <main>
        <section class="section section-lg pt-lg-0 mt--200">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        @if (isset($errors))
                        <div class="alert alert-danger" role="alert">
                            <span class="alert-inner--icon"><i class="ni ni-sound-wave"></i></span>
                            <strong>Something went wrong!</strong> {{$errors}}
                        </div>
                        @elseif (isset($success))
                        <div class="alert alert-success" role="alert">
                            <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
                            <strong>Success!</strong> {{$success}}
                        </div>
                        @endif
                        <div class="row row-grid">
                            <div class="col-lg-4">
                                <div class="card card-lift--hover shadow border-0">
                                    <div class="card-body">
                                        <div>
                                            <h2 class="d-inline-block">{{$product->title}}</h2>
                                            <a href="#" title="View In Shopify"
                                               class="btn btn-primary float-right"
                                               onclick="ShopifyApp.redirect('/admin/products/{{$product->id}}')">
                                                View In Shopify
                                            </a>
                                        </div>

                                        @if (strlen($product->image) > 0)
                                            <img style="width:80px;" src="{{$product->image}}" alt="{{$product->title}} Image">
                                        @else
                                            <img style="width:80px;" src="https://cdn.shopify.com/s/files/applications/b7f5a1fa3e70a03d0781922c6f528be0_512x512.png?1550610484" alt="{{$product->title}}">
                                        @endif
                                    </div>
                                    <div class="card-body py-5">
                                        <p>
                                            Updating the inventory here will immediately set the inventory in Shopify
                                            @if( strlen($connectedTo) > 0) and in {{$connectedTo}}@endif.
                                        </p>
                                        <form method="post" action="/inventory/products/{{$product->id}}?shop={{$shopOwner->name}}">
                                            <input type="hidden" name="_method" value="put">
                                            <input type="hidden" name="shop" value="{{$shopOwner->name}}">
                                            @foreach ($product->variants as $variant)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name-{{$variant->title}}">
                                                            Variant Title
                                                        </label>
                                                        <input type="text"
                                                           id="name-{{$variant->title}}"
                                                           class="form-control"
                                                           placeholder="{{ucwords($variant->title, " ")}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="{{$variant->id}}">
                                                            Inventory
                                                        </label>
                                                        <input type="text"
                                                           id="{{$variant->id}}"
                                                           name="variants[{{$variant->id}}]"
                                                           value="{{$variant->inventoryLevel->available}}"
                                                           class="form-control"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            <button onClick="this.form.submit(); this.disabled=true; this.innerHTML='Updating...'" class="btn btn-primary" type="submit">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        let shop = "{{$shopOwner->name}}",
            token = "{{$shopOwner->token}}",
            title = "{{$product->title}}";
    </script>
@endsection