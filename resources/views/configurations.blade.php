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
                                        <form method="post" action="/inventory/configurations?shop={{$shopOwner->name}}">
                                            <input type="hidden" name="shop" value="{{$shopOwner->name}}">

                                            @foreach($configuration as $config)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="config-{{$config->id}}">
                                                                {{ucwords(str_replace('_', ' ', $config->entity), ' ')}}
                                                            </label>
                                                            @if ($config->is_dropdown)
                                                                <select class="form-control" name="{{$config->entity}}" id="config-{{$config->id}}">
                                                                    @foreach($config->dropdown_settings as $value => $text)
                                                                        <option @if($value == $config->value) selected @endif value="{{$value}}">{{$text}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                            <input type="text"
                                                               id="config-{{$config->id}}"
                                                               class="form-control"
                                                               name="{{$config->entity}}"
                                                               value="{{$config->value}}">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
            title = "Configuration";

    </script>
@endsection