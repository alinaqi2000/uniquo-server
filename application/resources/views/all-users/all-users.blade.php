@extends('layouts.app', ['title' => __('Site Setting')])

@section('content')

    {{-- @include('users.partials.header', [
        'title' => __('All User Settings') . ' ' . auth()->user()->name,
        'description' => __(
            'This is your settings page. You can update the setting that may affect your app\'s business logic.'
        ),
        'class' => 'col-lg-7',
    ]) --}}
    <div class="header bg-primary pb-6 pt-5 pt-lg-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-12 col-12">
                        <h6 class="h2 text-white d-inline-block mb-0">Users</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Users</a></li>
                                <li class="breadcrumb-item active" aria-current="page">All users</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--6">
        <div class="row">

            <div class="col">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                            <h3 class="mb-0">{{ __('All Users') }}</h3>
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


                        <a href="{{ route('adduser') }}" class="btn btn-success mb-3">Add New User</a>
                        <div class="table-responsive" id="all-users-table-styling">
                            <div>
                                <table class="table align-items-center data-table" >
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Phone Code</th>
                                            <th scope="col">Phone No</th>
                                            <th scope="col">User Type</th>
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
                                    ajax: "{{ route('allusers') }}",
                                    columns: [
                                        {data: 'id', name: 'id'},
                                        {data: 'username', name: 'username'},
                                        {data: 'email', name: 'email'},
                                        {data: 'full_name', name: 'full_name'},
                                        {data: 'phone_code', name: 'phone_code'},
                                        {data: 'phone_no', name: 'phone_no'},
                                        {data: 'type', name: 'type'},
                                        {data: 'action', name: 'action', orderable: true, searchable: false},
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
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
