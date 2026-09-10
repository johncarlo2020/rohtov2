@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bolder text-dark mb-1">
                <i class="fa-solid fa-crown text-warning me-2"></i>VIP Schedule & Group Allocation Breakdown
            </h4>
            <p class="text-sm text-muted mb-0">Overview of VIP group schedule presets and allocated pax capacity.</p>
        </div>
    </div>

    {{-- VIP Group Schedule Breakdown --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 14px;">
                <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fa-solid fa-layer-group text-warning me-2"></i>VIP Group Schedule & Allocation Breakdown
                    </h5>
                    <span class="badge bg-warning text-dark text-xxs font-weight-bold">5 VIP Groups</span>
                </div>
                <div class="card-body px-0 pt-2 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">VIP Group Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Active Dates</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time Slots & Allocated Pax</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>KOL AND MEDIA INFLUENCER</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30 & Oct 1</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 11am-3pm (4 x 20 pax = 80 pax)</div>
                                        <div><strong class="text-primary">1 Oct:</strong> 11am-1pm (2 x 10 pax = 20 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>LONGCHAMP VIC</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 3pm-5pm (2 x 10 pax = 20 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>THE GARDENS EMERALD MEMBER</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30 & Oct 1</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 5pm-7pm (2 x 6 pax = 12 pax)</div>
                                        <div><strong class="text-primary">1 Oct:</strong> 3pm-5pm (2 x 6 pax = 12 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>MAYBANK PREMIUM CUSTOMER</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 7pm-10pm (3 x 10 pax = 30 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>PIN PRESTIGE</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Oct 1</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">1 Oct:</strong> 1pm-3pm (2 x 6 pax = 12 pax)</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
