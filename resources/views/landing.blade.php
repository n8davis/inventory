@extends('layouts.app')
@section('content')
<main>

    <section class="section section-lg pt-lg-0 mt--200">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="row row-grid">
              <div class="col-lg-4">
                <div class="card shadow border-0 mb-3">
                    <div class="card-body">
                        <form action="/inventory?shop={{$shopOwner->name}}" method="GET">
                            <input type="hidden" name="shop" value="{{$shopOwner->name}}">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input type="text" id="q" name="q" placeholder="Search...." value="{{strlen($search) > 0 ? $search : null}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Go</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card shadow border-0">
                  <div class="card-body py-5">
                    <div class="icon icon-shape icon-shape-primary rounded-circle mb-4">
                      <i class="ni ni-cart"></i>
                    </div>

                    @if(count($products)>0)
                    <div class="float-right">
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Page {{$page}}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          @for( $p = 1; $p <= $total_pages; $p++)
                            <a class="dropdown-item" href="/inventory?shop={{$shopOwner->name}}&page={{$p}}">
                              page {{$p}}
                            </a>
                          @endfor
                        </div>
                      </div>
                    </div>
                    @endif
                    @if(count($products)>0)
                        <h6 class="text-primary text-uppercase">Inventory</h6>
                        <table class="table table-striped table-hover table-responsive">
                      <tr>
                        <th></th>
                        <th>Title</th>
                        <th>Inventory</th>
                        <th>Type</th>
                        <th>Vendor</th>
                      </tr>
                      @foreach ($products as $product)
                        <tr class="cursor"
                            onclick="window.location.href='/inventory/products/{{$product->id}}?shop={{$shopOwner->name}}'"
                        >
                          <td>
                            @if (strlen($product->image) > 0)
                            <img style="width:80px;" src="{{$product->image}}" alt="{{$product->title}}">
                            @else
                              <img style="width:80px;" src="https://cdn.shopify.com/s/files/applications/b7f5a1fa3e70a03d0781922c6f528be0_512x512.png?1550610484" alt="{{$product->title}}">
                            @endif
                          </td>
                          <td>{{$product->title}}</td>
                          <td>
                            {{$product->total_in_stock}} in stock for {{$product->total_variants}}
                            @if ($product->total_variants !== 1) variants @else variant @endif
                          </td>
                          <td>
                            <span class="badge badge-pill badge-primary">{{$product->type}}</span>
                          </td>
                          <td>
                            {{$product->vendor}}
                          </td>
                        </tr>
                      @endforeach
                    </table>
                    @else
                        <h2>No Products</h2>
                    @endif

                    @if(count($products)>0)
                    <div class="float-right">
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Page {{$page}}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          @for( $p = 1; $p <= $total_pages; $p++)
                          <a class="dropdown-item" href="/inventory?shop={{$shopOwner->name}}&page={{$p}}">
                            page {{$p}}
                          </a>
                          @endfor
                        </div>
                      </div>
                    </div>
                    @endif
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
      title = "Dashboard",
      page = parseInt("{{$page}}");
</script>
@endsection
