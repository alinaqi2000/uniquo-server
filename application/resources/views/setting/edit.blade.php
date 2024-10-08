@extends('layouts.app', ['title' => __('Site Setting')])

@section('content')
    {{-- @include('users.partials.header', [
        'title' => __('Site Settings') . ' ' . auth()->user()->name,
        'description' => __(
            'This is your settings page. You can update the setting that may effect your app\'s business logic.'
        ),
        'class' => 'col-lg-7',
    ]) --}}

    <div class="header bg-primary pb-6 pt-5 pt-lg-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-12 col-12">
                        <h6 class="h2 text-white d-inline-block mb-0">Settings</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit settings</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--6">
        <div class="row">

            <div class="col order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('Edit Settings') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('setting.update') }}" autocomplete="off">
                            @csrf
                            @method('put')


                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @php
                                $settings = \App\Models\Setting::isParent()->get();
                            @endphp
                            @foreach ($settings as $setting)
                                @php
                                    $items = $setting->children()->get();
                                @endphp
                                <h6 class="heading-small text-muted mb-4">{{ __($setting->title) }}</h6>


                                <div class="pl-lg-4 row">
                                    @foreach ($items as $item)
                                        <div class="form-group col-12 col-lg-6{{ $errors->has($item->key) ? ' has-danger' : '' }}">
                                            <label class="form-control-label"
                                                for="input-name">{{ __($item->title) }}</label>
                                            <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                                                class="form-control form-control-alternative{{ $errors->has($item->key) ? ' is-invalid' : '' }}"
                                                placeholder="{{ __($item->title) }}"
                                                value="{{ old($item->key, $item->value) }}" required autofocus>

                                            @if ($errors->has($item->key))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first($item->key) }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>
                                <hr class="my-4" />
                            @endforeach
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
   
        {{-- <div class="row mt-5">

            <div class="col">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                            <h3 class="mb-0">{{ __('Payment Methods') }}</h3>
                    </div>
                    <div class="card-body pt--3">

                        @if(Session::has('success'))
                            <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(Session::has('error'))
                            <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif


                        <a href="{{ route('addcategory') }}" class="btn btn-success mb-3">Add New Category</a>
                        <div class="table-responsive" id="all-users-table-styling">
                            <div>
                                <table class="table align-items-center data-table" >
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Slug</th>
                                            <th scope="col">Suggest ID</th>
                                            <th scope="col">Verified</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>    
                        </div>

                        <!-- For Datatables -->
                        <script type="text/javascript">
                            $(function () {
                                var table = $('.data-table').DataTable({
                                    processing: true,
                                    serverSide: true,
                                    ajax: "{{ route('allcategories') }}",
                                    columns: [
                                        {data: 'id', name: 'id'},
                                        {data: 'title', name: 'title'},
                                        {data: 'slug', name: 'slug'},
                                        {data: 'suggested_by', name: 'suggested_by'},
                                        {
                                            data: 'verified',
                                            name: 'verified',
                                            render: function (data) {
                                                return data ? 'Yes' : 'No'; // Rendering "Yes" for 1 and "No" for 0
                                            }
                                        },
                                        {data: 'action', name: 'action', orderable: true, searchable: true},
                                    ]
                                });
                                // Function to hide the alerts after 5 seconds
                                setTimeout(function(){
                                    $('#success-alert').fadeOut();
                                    $('#error-alert').fadeOut();
                                }, 5000); // 5 seconds (5000 milliseconds)
                            });
                        </script>
                         
                    </div>
                </div>
            </div>
        </div> --}}

        @include('layouts.footers.auth')
    </div>
@endsection
