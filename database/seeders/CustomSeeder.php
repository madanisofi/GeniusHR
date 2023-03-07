<?php

namespace Database\Seeders;

use App\Models\AdditionalInformation;
use App\Models\AllowanceOption;
use App\Models\AwardType;
use App\Models\Branch;
use App\Models\Competencies;
use App\Models\DeductionOption;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Document;
use App\Models\Educations;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\ExpenseType;
use App\Models\GoalType;
use App\Models\Group;
use App\Models\IncomeType;
use App\Models\JobCategory;
use App\Models\LeaveType;
use App\Models\LoanOption;
use App\Models\OvertimeCompensation;
use App\Models\PaymentType;
use App\Models\PayslipType;
use App\Models\Performance_Type;
use App\Models\PermissionType;
use App\Models\Position;
use App\Models\RoomType;
use App\Models\Shift;
use App\Models\TerminationType;
use App\Models\TrainingType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = [
            ['name' => 'Branch 1', 'created_by' => 2],
            ['name' => 'Branch 2', 'created_by' => 2],
            ['name' => 'Branch 3', 'created_by' => 2],
            ['name' => 'Branch 4', 'created_by' => 2],
            ['name' => 'Branch 5', 'created_by' => 2],
            ['name' => 'Branch 6', 'created_by' => 2],
            ['name' => 'Branch 7', 'created_by' => 2],
            ['name' => 'Branch 8', 'created_by' => 2],
            ['name' => 'Branch 9', 'created_by' => 2],
            ['name' => 'Branch 10', 'created_by' => 2],
            ['name' => 'Branch 11', 'created_by' => 2],
            ['name' => 'Branch 12', 'created_by' => 2],
            ['name' => 'Branch 13', 'created_by' => 2],
            ['name' => 'Branch 14', 'created_by' => 2],
            ['name' => 'Branch 15', 'created_by' => 2],
            ['name' => 'Branch 16', 'created_by' => 2],
            ['name' => 'Branch 17', 'created_by' => 2],
            ['name' => 'Branch 18', 'created_by' => 2],
            ['name' => 'Branch 19', 'created_by' => 2],
            ['name' => 'Branch 20', 'created_by' => 2],
            ['name' => 'Branch 21', 'created_by' => 2],
            ['name' => 'Branch 22', 'created_by' => 2],
            ['name' => 'Branch 23', 'created_by' => 2],
            ['name' => 'Branch 24', 'created_by' => 2],
            ['name' => 'Branch 25', 'created_by' => 2],
            ['name' => 'Branch 26', 'created_by' => 2],
            ['name' => 'Branch 27', 'created_by' => 2],
            ['name' => 'Branch 28', 'created_by' => 2],
            ['name' => 'Branch 29', 'created_by' => 2],
            ['name' => 'Branch 30', 'created_by' => 2],
            ['name' => 'Branch 31', 'created_by' => 2],
            ['name' => 'Branch 32', 'created_by' => 2],
            ['name' => 'Branch 33', 'created_by' => 2],
            ['name' => 'Branch 34', 'created_by' => 2],
            ['name' => 'Branch 35', 'created_by' => 2],
            ['name' => 'Branch 36', 'created_by' => 2],
            ['name' => 'Branch 37', 'created_by' => 2],
        ];
        foreach ($branches as $branch) {
            Branch::create($branch);
        }

        $departments = [
            ['name' => 'Department 1', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 2', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 3', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 4', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 5', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 6', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 7', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 8', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 9', 'branch_id' => 1, 'created_by' => 2],
            ['name' => 'Department 10', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 11', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 12', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 13', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 14', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 15', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 16', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 17', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 18', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 19', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
            ['name' => 'Department 20', 'branch_id' => fake()->numberBetween(1, Branch::count()), 'created_by' => 2],
        ];
        foreach ($departments as $department) {
            Department::create($department);
        }

        $designations = [
            ['name' => 'Designation 1', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 2', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 3', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 4', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 5', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 6', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 7', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 8', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 9', 'department_id' => 1, 'created_by' => 2],
            ['name' => 'Designation 10', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 11', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 12', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 13', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 14', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 15', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 16', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 17', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 18', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 19', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 20', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 21', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 22', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 23', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 24', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 25', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 26', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 27', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 28', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 29', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],
            ['name' => 'Designation 30', 'department_id' => fake()->numberBetween(1, Department::count()), 'created_by' => 2],

        ];
        foreach ($designations as $designation) {
            Designation::create($designation);
        }

        $leave_types = [
            ['title' => 'Holiday', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
            ['title' => 'Casual', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
            ['title' => 'Sick', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
            ['title' => 'Maternity', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
            ['title' => 'Paternity', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
            ['title' => 'Marriage', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
            ['title' => 'Funeral', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
            ['title' => 'Study', 'days' => fake()->numberBetween(1,36), 'parent' => null, 'start_date' => 00, 'end_date' => 00, 'select_all' => 'off', 'reduction' => 0, 'created_by' => 2],
        ];
        foreach ($leave_types as $leave_type) {
            LeaveType::create($leave_type);
        }

        $document_types = [
            ['name' => 'KTP', 'is_required' => 0, 'created_by' => 2],
            ['name' => 'NPWP', 'is_required' => 0, 'created_by' => 2],
            ['name' => 'BPJS Kesehatan', 'is_required' => 0, 'created_by' => 2],
            ['name' => 'BPJS Ketenagakerjaan', 'is_required' => 0, 'created_by' => 2],
        ];
        foreach ($document_types as $document_type) {
            Document::create($document_type);
        }

        $payslip_types = [
            ['name' => 'Gaji Bulanan', 'created_by' => 2],
            ['name' => 'Gaji Lembur', 'created_by' => 2],
            ['name' => 'Gaji Lembur Mingguan', 'created_by' => 2],
            ['name' => 'Gaji Lembur Bulanan', 'created_by' => 2],
            ['name' => 'Gaji Lembur Tahunan', 'created_by' => 2],
            ['name' => 'Gaji Lembur Hari Libur', 'created_by' => 2],
            ['name' => 'Gaji Lembur Mingguan Hari Libur', 'created_by' => 2],
            ['name' => 'Gaji Lembur Bulanan Hari Libur', 'created_by' => 2],
            ['name' => 'Gaji Lembur Tahunan Hari Libur', 'created_by' => 2],
            ['name' => 'Gaji Lembur Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Mingguan Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Bulanan Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Tahunan Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Mingguan Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Bulanan Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Tahunan Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Mingguan Hari Libur Nasional', 'created_by' => 2],
            ['name' => 'Gaji Lembur Bulanan Hari Libur Nasional', 'created_by' => 2],
        ];
        foreach ($payslip_types as $payslip_type) {
            PayslipType::create($payslip_type);
        }

        $allowance_options = [
            ['name' => 'Tunjangan Transport', 'created_by' => 2],
            ['name' => 'Tunjangan Makan', 'created_by' => 2],
            ['name' => 'Tunjangan Kesehatan', 'created_by' => 2],
        ];
        foreach ($allowance_options as $allowance_option) {
            AllowanceOption::create($allowance_option);
        }

        $loan_options = [
            ['name' => 'Pinjaman Karyawan', 'created_by' => 2],
            ['name' => 'Pinjaman Koperasi', 'created_by' => 2],
        ];
        foreach ($loan_options as $loan_option) {
            LoanOption::create($loan_option);
        }

        $deduction_options = [
            ['name' => 'Potongan Karyawan', 'created_by' => 2],
            ['name' => 'Potongan Koperasi', 'created_by' => 2],
        ];
        foreach ($deduction_options as $deduction_option) {
            DeductionOption::create($deduction_option);
        }

        $goal_types = [
            ['name' => 'KPI', 'created_by' => 2],
            ['name' => 'OKR', 'created_by' => 2],

        ];
        foreach ($goal_types as $goal_type) {
            GoalType::create($goal_type);
        }

        $training_types = [
            ['name' => 'Pelatihan', 'created_by' => 2],
            ['name' => 'Sertifikasi', 'created_by' => 2],

        ];
        foreach ($training_types as $training_type) {
            TrainingType::create($training_type);
        }

        $award_types = [
            ['name' => 'Penghargaan Karyawan', 'created_by' => 2],
            ['name' => 'Penghargaan Perusahaan', 'created_by' => 2],
        ];
        foreach ($award_types as $award_type) {
            AwardType::create($award_type);
        }

        $termination_types = [
            ['name' => 'Resign', 'created_by' => 2],
            ['name' => 'Pemutusan Kontrak', 'created_by' => 2],
        ];
        foreach ($termination_types as $termination_type) {
            TerminationType::create($termination_type);
        }

        $job_categories = [
            ['title' => 'Karyawan Tetap', 'created_by' => 2],
            ['title' => 'Karyawan Kontrak', 'created_by' => 2],
            ['title' => 'Karyawan Outsourcing', 'created_by' => 2],
        ];
        foreach ($job_categories as $job_category) {
            JobCategory::create($job_category);
        }

        $performance_types = [
            ['name' => 'Kinerja', 'created_by' => 2],
            ['name' => 'Kedisiplinan', 'created_by' => 2],
            ['name' => 'Kerjasama', 'created_by' => 2],
            ['name' => 'Kepemimpinan', 'created_by' => 2],
            ['name' => 'Kepribadian', 'created_by' => 2],
        ];
        foreach ($performance_types as $performance_type) {
            Performance_Type::create($performance_type);
        }

        $competencies = [
            ['name' => 'Kerja Sama', 'created_by' => 2],
            ['name' => 'Kepemimpinan', 'created_by' => 2],
            ['name' => 'Kepribadian', 'created_by' => 2],
        ];
        foreach ($competencies as $competency) {
            Competencies::create($competency);
        }

        $expense_types = [
            ['name' => 'Biaya Perjalanan Dinas', 'created_by' => 2],
        ];
        foreach ($expense_types as $expense_type) {
            ExpenseType::create($expense_type);
        }

        $income_types = [
            ['name' => 'Pendapatan Perjalanan Dinas', 'created_by' => 2],
        ];
        foreach ($income_types as $income_type) {
            IncomeType::create($income_type);
        }

        $payment_types = [
            ['name' => 'Transfer Bank', 'created_by' => 2],
            ['name' => 'Cash', 'created_by' => 2],
        ];
        foreach ($payment_types as $payment_type) {
            PaymentType::create($payment_type);
        }

        $shifts = [
            ['name' => 'Shift 1', 'start_time' => '08:00:00', 'end_time' => '17:00:00', 'created_by' => 2],
            ['name' => 'Shift 2', 'start_time' => '17:00:00', 'end_time' => '02:00:00', 'created_by' => 2],
            ['name' => 'Shift 3', 'start_time' => '02:00:00', 'end_time' => '08:00:00', 'created_by' => 2],
        ];
        foreach ($shifts as $shift) {
            Shift::create($shift);
        }

        $room_types = [
            ['name' => 'Single', 'created_by' => 2],
            ['name' => 'Double', 'created_by' => 2],
            ['name' => 'Triple', 'created_by' => 2],
            ['name' => 'Quad', 'created_by' => 2],
        ];
        foreach ($room_types as $room_type) {
            RoomType::create($room_type);
        }

        $employee_types = [
            ['name' => 'Karyawan Tetap', 'created_by' => 2],
            ['name' => 'Karyawan Kontrak', 'created_by' => 2],
            ['name' => 'Karyawan Outsourcing', 'created_by' => 2],
        ];
        foreach ($employee_types as $employee_type) {
            EmployeeType::create($employee_type);
        }

        $positions = [
            ['name' => 'Backend Developer', 'created_by' => 2],
            ['name' => 'Frontend Developer', 'created_by' => 2],
            ['name' => 'UI/UX Designer', 'created_by' => 2],
            ['name' => 'Project Manager', 'created_by' => 2],
            ['name' => 'System Analyst', 'created_by' => 2],
            ['name' => 'System Administrator', 'created_by' => 2],
            ['name' => 'IT Support', 'created_by' => 2],
            ['name' => 'IT Manager', 'created_by' => 2],
            ['name' => 'IT Director', 'created_by' => 2],
            ['name' => 'HRD', 'created_by' => 2],
            ['name' => 'HRD Manager', 'created_by' => 2],
            ['name' => 'HRD Director', 'created_by' => 2],
            ['name' => 'Finance', 'created_by' => 2],
            ['name' => 'Finance Manager', 'created_by' => 2],
            ['name' => 'Finance Director', 'created_by' => 2],
            ['name' => 'Marketing', 'created_by' => 2],
            ['name' => 'Marketing Manager', 'created_by' => 2],
            ['name' => 'Marketing Director', 'created_by' => 2],
            ['name' => 'Sales', 'created_by' => 2],
            ['name' => 'Sales Manager', 'created_by' => 2],
            ['name' => 'Sales Director', 'created_by' => 2],
            ['name' => 'Operation', 'created_by' => 2],
            ['name' => 'Operation Manager', 'created_by' => 2],
            ['name' => 'Operation Director', 'created_by' => 2],
            ['name' => 'President Director', 'created_by' => 2],
            ['name' => 'Vice President Director', 'created_by' => 2],
            ['name' => 'General Manager', 'created_by' => 2],
            ['name' => 'Manager', 'created_by' => 2],
            ['name' => 'Supervisor', 'created_by' => 2],
            ['name' => 'Staff', 'created_by' => 2],
        ];
        foreach ($positions as $position) {
            Position::create($position);
        }

        $groups = [
            ['name' => 'IT Department', 'created_by' => 2],
            ['name' => 'HR Department', 'created_by' => 2],
            ['name' => 'Finance Department', 'created_by' => 2],
            ['name' => 'Marketing Department', 'created_by' => 2],
            ['name' => 'Sales Department', 'created_by' => 2],
            ['name' => 'Operation Department', 'created_by' => 2],

        ];
        foreach ($groups as $group) {
            Group::create($group);
        }

        $educations = [
            ['name' => 'SD', 'created_by' => 2],
            ['name' => 'SMP', 'created_by' => 2],
            ['name' => 'SMA', 'created_by' => 2],
            ['name' => 'D1', 'created_by' => 2],
            ['name' => 'D2', 'created_by' => 2],
            ['name' => 'D3', 'created_by' => 2],
            ['name' => 'D4', 'created_by' => 2],
            ['name' => 'S1', 'created_by' => 2],
            ['name' => 'S2', 'created_by' => 2],
            ['name' => 'S3', 'created_by' => 2],
        ];
        foreach ($educations as $education) {
            Educations::create($education);
        }

        $additional_informations = [
            ['name' => 'No. NPWP', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. BPJS Kesehatan', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. BPJS Ketenagakerjaan', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. Rekening', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'Nama Bank', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'Nama Rekening', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. KTP', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. SIM', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. Passport', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. KITAS', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. KIMS', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. KITAP', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
            ['name' => 'No. KITAS', 'type' => 'text', 'can_insert' => 1, 'send_notification' => 0, 'reminder' => 1, 'is_required' => 0, 'created_by' => 2],
        ];
        foreach ($additional_informations as $additional_information) {
            AdditionalInformation::create($additional_information);
        }

        $permission_types = [
            ['title' => 'Meeting Klien', 'days' => 3, 'many_submission' => 'no', 'clock_out' => 'no', 'get_consumption_fee' => 'yes', 'created_by' => 2],
            ['title' => 'Meeting Internal', 'days' => 3, 'many_submission' => 'no', 'clock_out' => 'no', 'get_consumption_fee' => 'yes', 'created_by' => 2],
            ['title' => 'Meeting Luar Kota', 'days' => 3, 'many_submission' => 'no', 'clock_out' => 'no', 'get_consumption_fee' => 'yes', 'created_by' => 2],
            ['title' => 'Meeting Luar Negeri', 'days' => 3, 'many_submission' => 'no', 'clock_out' => 'no', 'get_consumption_fee' => 'yes', 'created_by' => 2],
            ['title' => 'Cuti', 'days' => 3, 'many_submission' => 'yes', 'clock_out' => 'no', 'get_consumption_fee' => 'no', 'created_by' => 2],
            ['title' => 'Izin', 'days' => 3, 'many_submission' => 'yes', 'clock_out' => 'no', 'get_consumption_fee' => 'no', 'created_by' => 2],
            ['title' => 'Sakit', 'days' => 3, 'many_submission' => 'yes', 'clock_out' => 'no', 'get_consumption_fee' => 'no', 'created_by' => 2],
            ['title' => 'Lembur', 'days' => 3, 'many_submission' => 'yes', 'clock_out' => 'yes', 'get_consumption_fee' => 'yes', 'created_by' => 2],
            ['title' => 'Perjalanan Dinas', 'days' => 3, 'many_submission' => 'yes', 'clock_out' => 'yes', 'get_consumption_fee' => 'yes', 'created_by' => 2],
            ['title' => 'Perjalanan Dinas Luar Kota', 'days' => 3, 'many_submission' => 'yes', 'clock_out' => 'yes', 'get_consumption_fee' => 'yes', 'created_by' => 2],
            ['title' => 'Perjalanan Dinas Luar Negeri', 'days' => 3, 'many_submission' => 'yes', 'clock_out' => 'yes', 'get_consumption_fee' => 'yes', 'created_by' => 2],
        ];
        foreach ($permission_types as $permission_type) {
            PermissionType::create($permission_type);
        }

        $overtime_compensations = [
            ['name' => 'Overtime', 'created_by' => 2],
            ['name' => 'Overtime Weekend', 'created_by' => 2],
            ['name' => 'Overtime Holiday', 'created_by' => 2],
            ['name' => 'Overtime Weekend Holiday', 'created_by' => 2],
        ];
        foreach ($overtime_compensations as $overtime_compensation) {
            OvertimeCompensation::create($overtime_compensation);
        }

        /**
         * Create non-master data
         */
        $data_amount = 100;
        for ($i=0; $i < $data_amount; $i++) {
            $name = fake()->name;
            $branch = 1;
            $department = 1;
            $designation = 1;
            $user = User::create([
                'name' => $name,
                'email' => fake()->email(),
                'password' => bcrypt('1234'),
                'lang' => 'en',
                'created_by' => 2,
            ]);
            Employee::create([
                'user_id' => $user->id,
                'name' => $name,
                'dob' => fake()->dateTimeBetween('-30 years', '-20 years'),
                'birthplace' => fake()->city(),
                'gender' => fake()->randomElement(['Male', 'Female']),
                'phone' => fake()->phoneNumber(), 'address' => fake()->address(),
                'email' => fake()->email(), 'password' => bcrypt('1234'),
                'employee_id' => '#EMP' . str_pad((Employee::count() + 1), '7', '0', STR_PAD_LEFT),
                'employee_no' => null,
                'branch_id' => $branch,
                'department_id' => $department,
                'designation_id' => $designation,
                'position_id' => fake()->numberBetween(1, Position::count()),
                'employeetype_id' => fake()->numberBetween(1, EmployeeType::count()),
                'room_id' => null,
                'group_now' => 0,
                'role_id' => fake()->numberBetween(1, Role::count()),
                'education_id' => fake()->numberBetween(1, Educations::count()),
                'company_doj' => now(),
                'is_active' => 1,
                'created_by' => 2
            ]);
        }
    }
}
