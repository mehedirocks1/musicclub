<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;
use Modules\Members\Models\Member;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $title = 'My Profile';
    protected static ?string $slug = 'profile';

    protected string $view = 'member.profile';

    public Member $member;

    // Form fields
    public $profile_pic;
    public $member_id;
    public $username;
    public $name_bn;
    public $full_name;
    public $email;
    public $phone;
    public $father_name;
    public $mother_name;
    public $dob;
    public $id_number;
    public $gender;
    public $blood_group;
    public $education_qualification;
    public $profession;
    public $other_expertise;
    public $country;
    public $division;
    public $district;
    public $address;
    public $membership_type;
    public $registration_date;
    public $balance;

    public function mount(): void
    {
        $this->member = Auth::guard('member')->user();

        // Pre-fill all properties
        $this->profile_pic           = $this->member->profile_pic;
        $this->member_id             = $this->member->member_id;
        $this->username              = $this->member->username;
        $this->name_bn               = $this->member->name_bn;
        $this->full_name             = $this->member->full_name;
        $this->email                 = $this->member->email;
        $this->phone                 = $this->member->phone;
        $this->father_name           = $this->member->father_name;
        $this->mother_name           = $this->member->mother_name;
        $this->dob                   = $this->member->dob?->format('Y-m-d'); // date input format
        $this->id_number             = $this->member->id_number;
        $this->gender                = $this->member->gender;
        $this->blood_group           = $this->member->blood_group;
        $this->education_qualification = $this->member->education_qualification;
        $this->profession            = $this->member->profession;
        $this->other_expertise       = $this->member->other_expertise;
        $this->country               = $this->member->country;
        $this->division              = $this->member->division;
        $this->district              = $this->member->district;
        $this->address               = $this->member->address;
        $this->membership_type       = $this->member->membership_type;
        $this->registration_date     = $this->member->registration_date?->format('Y-m-d');
        $this->balance               = $this->member->balance;

        // Fill Filament form state automatically
        $this->form->fill($this->member->toArray());
    }

    protected function getFormModel(): Member
    {
        // The form binds to the member model
        return $this->member;
    }

    protected function getFormSchema(): array
    {
        return [
            FileUpload::make('profile_pic')->label('Profile Picture')->image()->maxSize(1024),
            TextInput::make('member_id')->label('Member ID')->required(),
            TextInput::make('username')->label('Username')->required(),
            TextInput::make('name_bn')->label('Name (Bangla)'),
            TextInput::make('full_name')->label('Full Name')->required(),
            TextInput::make('email')->label('Email')->email()->required(),
            TextInput::make('phone')->label('Phone')->required(),
            TextInput::make('father_name')->label('Father Name'),
            TextInput::make('mother_name')->label('Mother Name'),
            DatePicker::make('dob')->label('Date of Birth'),
            TextInput::make('id_number')->label('ID Number'),
            Select::make('gender')->options(array_combine(Member::GENDERS, Member::GENDERS)),
            Select::make('blood_group')->options(array_combine(Member::BLOOD_GROUPS, Member::BLOOD_GROUPS)),
            TextInput::make('education_qualification')->label('Education Qualification'),
            TextInput::make('profession')->label('Profession'),
            TextInput::make('other_expertise')->label('Other Expertise'),
            TextInput::make('country')->label('Country'),
            TextInput::make('division')->label('Division'),
            TextInput::make('district')->label('District'),
            TextInput::make('address')->label('Address'),
            Select::make('membership_type')->options(array_combine(Member::MEMBERSHIP_TYPES, Member::MEMBERSHIP_TYPES)),
            DatePicker::make('registration_date')->label('Registration Date'),
            TextInput::make('balance')->label('Balance')->disabled(),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState(); // Get updated form values
        $this->member->update($data);
        $this->notify('success', 'Profile updated successfully!');
    }
}
