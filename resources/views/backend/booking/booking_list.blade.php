@extends('admin.admin_dashboard')
@section('main')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Booking</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.room.list') }}" class="btn btn-primary px-5">Add Booking</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>B No</th>
                            <th>B Date</th>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Check In/Out</th>
                            <th>Total Room</th>
                            <th>Guest</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($allData as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><span class="badge bg-success"><a class=" text-dark" href="{{ route('edit_booking',$item->id) }}">{{ $item->code }}</a></span></td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item['user']['name'] }}</td>
                            <td>{{ $item['room']['type']['name'] }}</td>

                            <td><span class="badge bg-primary">{{ $item->check_in }}</span> / <br><span class="badge bg-warning text-dark">{{ $item->check_out }}</span></td>

                            <td>{{ $item->number_of_rooms }}</td>
                            <td>{{ $item->persion }}</td>

                            <td>@if ($item->payment_status == '1')
                                <span class="badge bg-success">Complete</span>
                                @else
                                <span class="badge bg-danger">Pending</span>
                                @endif
                            </td>

                            <td>@if ($item->status == '1')
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Pending</span>
                                @endif
                            </td>

                            <td>
                                <a href="" class="btn btn-danger px-3 radius-30" id="delete">Delete</a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>SL</th>
                            <th>B No</th>
                            <th>B Date</th>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Check In/Out</th>
                            <th>Total Room</th>
                            <th>Guest</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection
