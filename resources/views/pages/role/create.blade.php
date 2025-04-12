@extends('layouts.master-layout', ['title' => 'Admin - Thêm vai trò'])
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Phân quyền /</span> Thêm vai trò</h4>
        <!-- Hoverable Table rows -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Thêm vai trò</h5>
                        <a href="{{ route('roles.index') }}"><small class="text-muted float-end d-flex align-item-center"><i
                                    class='bx bx-left-arrow-alt'></i> Quay về</small></a>
                    </div>
                    <div class="card-body">
                        <form class="row" action="{{ route('roles.store') }}" method="POST">
                            @csrf
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="name">Tên vai trò</label>
                                <input value="{{ old('name') }}" name="name" type="text" class="form-control"
                                    id="name" placeholder="Customer" />
                                @if ($errors->first('name'))
                                    <span class="text-danger" style="font-size: 0.8rem;">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="description">Mô tả</label>
                                <input value="{{ old('description') }}" name="description" type="text"
                                    class="form-control" id="description" placeholder="Mô tả" />
                                @if ($errors->first('description'))
                                    <span class="text-danger"
                                        style="font-size: 0.8rem;">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="form-group d-flex justify-content-between">
                                    <h5>Quyền của vai trò</h5>
                                    <div class="col-auto ms-3 d-flex align-items-center mt-3">
                                        <label class="colorinput me-2">
                                            <input id="select-all" name="select-all" type="checkbox" value="true"
                                                class="colorinput-input form-check-input check-action" />
                                            <span class="colorinput-color bg-info"></span>
                                        </label>
                                        <label for="select-all">Chọn tất cả</label>
                                    </div>
                                </div>
                                @foreach ($permissions as $item)
                                    <div class="col-12 d-flex justify-content-between">
                                        <div class="form-group">
                                            <div>
                                                <label for="">{{ $item->name }}</label>
                                                <input type="hidden" class="parent-checkbox-{{ $item->id }}" hidden
                                                    name="permission_id[]" value="{{ $item->id }}">
                                            </div>
                                        </div>
                                        <div class="form-group form-check-{{ $item->id }}">
                                            <div class="row gutters-xs">
                                                @php
                                                    $allowedActions = $item->permissionActions
                                                        ->pluck('action_id')
                                                        ->toArray();
                                                @endphp
                                                @foreach ($actions as $action)
                                                    @if (in_array($action->id, $allowedActions))
                                                        <div class="col-auto ms-3 d-flex align-items-center mt-3">
                                                            <label class="colorinput me-2">
                                                                <input
                                                                    id="checkbox-{{ $action->id }}-{{ $item->id }}"
                                                                    name="actions[{{ $item->id }}][]" type="checkbox"
                                                                    value="{{ $action->id }}"
                                                                    class="colorinput-input form-check-input check-action" />
                                                                <span class="colorinput-color bg-info"></span>
                                                            </label>
                                                            <label
                                                                for="checkbox-{{ $action->id }}-{{ $item->id }}">{{ $action->name }}</label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Thêm ngay</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
