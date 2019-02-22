@extends('layouts.app')
@section('content')
    <main>

        <section class="section section-lg pt-lg-0 mt--200">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        @if (isset($error))
                            <div class="alert alert-danger" role="alert">
                                <span class="alert-inner--icon"><i class="ni ni-sound-wave"></i></span>
                                <strong>Something went wrong!</strong> {{$error}}
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
                                    <div class="card-body py-5">
                                        <form method="post" action="/inventory/shop-owner-connections?shop={{$shopOwner->name}}">
                                            <input type="hidden" name="shop" value="{{$shopOwner->name}}">
                                            @foreach ($connections as $connection)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img style="width:100%;" src="{{$connection->image}}" alt="{{$connection->name}}">
                                                </div>
                                                <div class="col-md-6">
                                                    <p>
                                                        Connect {{$connection->name}}
                                                    </p>
                                                    <label for="{{$connection->id}}" class="custom-toggle">
                                                        <input name="selected[{{$connection->id}}]" class="toggler" id="{{$connection->id}}" type="checkbox"
                                                        @if($connection->is_selected) checked @endif
                                                        >
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                    <div class="form-group toggled @if(!$connection->is_selected) hide-first @endif" id="client_id_{{$connection->id}}">
                                                        <label for="{{$connection->id}}client_id">
                                                            Client ID
                                                        </label>
                                                        <input type="text"
                                                               name="client_id"
                                                               id="{{$connection->id}}client_id"
                                                               placeholder="{{
                                                               isset($connection->selectedConnection[0]) &&  isset($connection->selectedConnection[0]->client_id)
                                                               ? '****'
                                                               : ''
                                                               }}"
                                                               class="form-control"
                                                        />
                                                    </div>
                                                    <div class="form-group toggled @if(!$connection->is_selected) hide-first @endif" id="client_secret_{{$connection->id}}">
                                                        <label for="{{$connection->id}}client_secret">
                                                            Client Secret
                                                        </label>
                                                        <input type="text"
                                                               name="client_secret"
                                                               id="{{$connection->id}}client_secret"
                                                               placeholder="{{
                                                               isset($connection->selectedConnection[0]) &&  isset($connection->selectedConnection[0]->client_secret)
                                                               ? '****'
                                                               : ''
                                                               }}"
                                                               class="form-control"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            @endforeach
                                            <p>
                                                There will be more connections coming soon...
                                            </p>
                                            <button onClick="this.form.submit(); this.disabled=true; this.innerHTML='Saving...'" class="btn btn-primary" type="submit">Save</button>
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
            title = "Connections";

        window.history.replaceState({}, document.title,"https://jookbot.com/inventory/connections?");

        let toggler = document.getElementsByClassName('toggler'),
            toggled = document.getElementsByClassName('toggled'),
            length  = toggler.length;

        for(let index = 0; index < length; index++){
            let element = toggler[index];
            element.addEventListener('click', function(e){
                if(typeof(e.path[0].id) === 'string'){
                    for(let i = 0; i < toggled.length; i++){
                        if (e.path[0].checked){
                            toggled[i].style.display = 'block';
                        } else {
                            toggled[i].style.display = 'none';
                        }
                    }
                }
            });
        }


    </script>
@endsection