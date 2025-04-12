<?php 
namespace App\Services;

use App\Models\EmployeeProfile;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContact;

class UserService {
    public function all($role = null) {
        try {
            $users = User::with('customer');
    
            if ($role) {
                $users->where('role_id', $role);
            }
    
            $users = $users->orderByDesc('id')->get(); 
    
            return $users;
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }
    
    public function getAllUser($not_role_id = null) {
        try {
            $users = User::orderByDesc('id')->whereHas('role', function ($query) {
                $query->where('name', '!=', 'Supper Admin');
            });

            if ($not_role_id) {
                $users->where('role_id', '!=', $not_role_id);
            }
            
            return $users->get();
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();
            $user = User::create($data);

            if ($user) {
                $data['user_detail']['user_id'] = $user->id;
                $data['user_detail']['employee_code'] = $this->generateCodeFromName();
                UserDetail::create($data['user_detail']);
            }
            toastr()->success('Người dùng đã được tạo thành công!');
            return redirect()->back();
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function createCustomer($request) {
        try {
            $data = $request->validated();

            $data['code'] = $this->generateCodeCustomer();
            $customer = Customer::create($data);

            if ($customer) {
                $data['contact']['customer_id'] = $customer->id;
                CustomerContact::create($data['contact']);
                
                $data['user']['name'] = $data['name'];
                $data['user']['customer_id'] = $customer->id;
                $data['user']['role_id'] = 5;
                user::create($data['user']);

                toastr()->success('Khách hàng đã được tạo thành công!');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function createStaff($request) {
        try {
            $data = $request->validated();

            $user = User::create($data);
            if ($user) {
                $data['staff']['user_id'] = $user->id;
                $data['staff']['code'] = $this->generateCodeStaff();
                EmployeeProfile::create($data['staff']);

                toastr()->success('Nhân sự đã được tạo thành công!');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function findById($id) {
        try {
            return User::find($id);
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();

            if (!$data['password']) {
                unset($data['password']);
            }
            $user = User::find($id);

            if (!$user) {
                toastr()->error('Người dùng không tồn tại!');
                return redirect()->back();
            }

            $user->update($data);

            if ($user) {
                $data['user_detail']['user_id'] = $user->id;
                UserDetail::updateOrCreate(['user_id' => $user->id], $data['user_detail']);
            }

            toastr()->success('Người dùng đã được cập nhật thành công!');
            return redirect()->route('users.index');
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function updateCustomer($request, $id) {
        try {
            $data = $request->validated();

            if (!$data['user']['password']) {
                unset($data['user']['password']);
            }

            $user = User::find($id);

            if (!$user) {
                toastr()->error('Khách hàng không tồn tại!');
                return redirect()->back();
            }

            $user->customer->update($data);

            if ($data['user']) {
                $user->update($data['user']);
            }
            
            toastr()->success('Khách hàng đã được cập nhật thành công!');
            return redirect()->route('customers.index');
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function updateStaff($request, $id) {
        try {
            $data = $request->validated();

            if (!$data['password']) {
                unset($data['password']);
            }

            $user = User::find($id);

            if (!$user) {
                toastr()->error('Nhân sự không tồn tại!');
                return redirect()->back();
            }

            $user->update($data);
            if ($data['staff']) {
                $user->staff->update($data['staff']);
            }

            toastr()->success('Nhân sự đã được cập nhật thành công!');
            return redirect()->route('staffs.index');

        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    private function generateCodeStaff()
    {
        $max_code = EmployeeProfile::max('code');
        if ($max_code) {
            $lastNumber = (int) substr($max_code, 3);
            return 'MNV' . sprintf('%05d', $lastNumber + 1);
        }

        return 'MNV00001';
    }

    private function generateCodeCustomer()
    {
        $max_code = Customer::max('code');
        if ($max_code) {
            $lastNumber = (int) substr($max_code, 3);
            return 'KH' . sprintf('%05d', $lastNumber + 1);
        }

        return 'KH00001';
    }
}