@extends('layouts.master-layout', ['title' => 'Admin - Thông tin cá nhân'])
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .rp-task {
    width: 150px;
}

.box-purple {
    background-color: rgb(222, 206, 255);
    color: rgb(140, 87, 255);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}
.nav-tabs {
        border-bottom: 2px solid #3fbbc0;
    }

    .nav-tabs .tab-custom {
        background-color: #f8f9fa;
        border: 1px solid #3fbbc0;
        border-bottom: none;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        padding: 10px 20px;
        color: #3fbbc0;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-tabs .tab-custom:hover {
        background-color: #e0f7f9;
        color: #31999c;
    }

    .nav-tabs .tab-custom.active {
        background-color: #3fbbc0;
        color: #ffffff;
        font-weight: 600;
        border-color: #3fbbc0;
    }

    .cmr-item {
        position: absolute;
        bottom: 5px;
        right: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e7e7e7;
        cursor: pointer;
        transition: all ease 0.3s;
    }

    .cmr-item:hover {
        opacity: 0.7;
    }

</style>
@endsection
@section('script')
<script>
    document.getElementById('avatar').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('avatar-preview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            }

            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light d-flex align-items-center"><a href="{{ route('dashboard') }}"><i class='bx bx-left-arrow-alt' style="font-size: 2.0rem;"></i></a> Thông tin cá nhân</span></h4>
        <div class="row">
            <form action="{{ route('profile.update') }}" method="POST" class="col-md-12 d-flex" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-4 px-3 py-5 bg-white aside-shadow">
                    <div class="text-center">
                        <div class="img-avatar mx-auto position-relative" style="width: 120px; height: 120px;">
                            <img id="avatar-preview" width="100%" height="100%" class="rounded-circle object-fit-cover" src="{{ auth()->user()->avatar }}" alt="">
                            <input id="avatar" type="file" name="avatar" hidden>
                            <label for="avatar" class="cmr-item"><i class='bx bxs-camera'></i></label>
                        </div>
                        <h5 class="fs-5 mt-3">{{ auth()->user()->name }}</h5>
                        <span class="badge bg-label-primary">{{ auth()->user()->staff->code ?? 'N/A' }}</span>
                    </div>
                    <div class="rp-task mx-auto d-flex flex-column mt-4" style="gap: 10px;">
                        <div class="d-flex g-10 align-items-center" style="gap: 10px;">
                            <div class="btn {{ auth()->user()->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                <i class='bx bx-ghost' ></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span>Trạng thái</span>
                                <span>{{ auth()->user()->is_active ? 'Hoạt động' : 'không hoạt động' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <div class="btn bg-label-warning">
                                <i class='bx bxs-group'></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span>Vai trò</span>
                                <span>{{ auth()->user()->role->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 bg-white p-5">
                    <div class="row mt-3">
                        {{-- Họ và tên --}}
                        <div class="col-md-6 mb-3">
                          <label class="form-label" for="name">Họ và tên (<span class="text-danger">*</span>)</label>
                          <input value="{{ auth()->user()->name }}" name="name" type="text" class="form-control" id="name" placeholder="Họ và tên" />
                          @if ($errors->first('name'))
                            <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('name') }}</span>
                          @endif
                        </div>
                      
                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                          <label class="form-label" for="email">Email (<span class="text-danger">*</span>)</label>
                          <input value="{{ auth()->user()->email }}" name="email" type="text" class="form-control" id="email" placeholder="example@gmail.com" />
                          @if ($errors->first('email'))
                            <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('email') }}</span>
                          @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">Mật khẩu (<span class="text-danger">*</span>)</label>
                            <input value="" name="password" type="password" class="form-control" id="password" placeholder="Mật khẩu" />
                            @if ($errors->first('password'))
                              <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('password') }}</span>
                            @endif
                          </div>
                      
                        <div class="col-md-6 mb-3">
                          <label class="form-label" for="phone">Số điện thoại</label>
                          <input value="{{ auth()->user()?->staff?->phone }}" name="staff[phone]" type="text" class="form-control" id="phone" placeholder="Số điện thoại" />
                          @if ($errors->first('staff.phone'))
                            <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('staff.phone') }}</span>
                          @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="gender">Giới tính</label>
                            <select name="staff[gender]" class="form-control" id="gender">
                              <option value="">-- Giới tính --</option>
                              <option {{ auth()->user()?->staff?->gender == 'male' ? 'selected' : '' }} value="male">Nam</option>
                              <option {{ auth()->user()?->staff?->gender == 'female' ? 'selected' : '' }} value="female">Nữ</option>
                            </select>
                            @if ($errors->first('staff.gender'))
                              <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('staff.gender') }}</span>
                            @endif
                          </div>
    
                        <div class="col-md-6 mb-3">
                          <label class="form-label" for="dob">Ngày sinh</label>
                          <input value="{{ auth()->user()?->staff?->dob }}" name="staff[dob]" type="date" class="form-control" id="dob" />
                          @if ($errors->first('staff.dob'))
                            <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('staff.dob') }}</span>
                          @endif
                        </div>
                      
                        {{-- Địa chỉ --}}
                        <div class="col-md-6 mb-3">
                          <label class="form-label" for="address">Địa chỉ</label>
                          <input name="staff[address]" rows="4" type="text" class="form-control" id="address" placeholder="Địa chỉ" type="text" value="{{ auth()->user()?->staff?->address }}">
                          @if ($errors->first('staff.address'))
                            <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('staff.address') }}</span>
                          @endif
                        </div>
                      
                        <div class="col-12">
                          <button type="submit" class="btn btn-primary">Lưu</button>
                          <a href="{{ route('staffs.index') }}" class="btn btn-outline-danger">Hủy</a>
                        </div>
                      </div>
                </div>
                
            </form>
        </div>
    </div>
@endsection
