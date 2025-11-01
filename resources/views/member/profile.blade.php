<x-member-layout>
    <div class="max-w-5xl mx-auto mt-10 p-4">

        <h2 class="text-3xl font-bold mb-8 text-gray-800">My Profile</h2>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

                <!-- Profile Picture -->
                <div class="flex flex-col items-center md:items-start">
                    <div class="w-32 h-32 mb-4">
                        <img src="{{ $member->profile_pic ? asset('storage/' . $member->profile_pic) : asset('images/default-profile.png') }}"
                             alt="Profile Picture" class="w-full h-full object-cover rounded-full border">
                    </div>
                    <label class="block mb-1 font-medium text-gray-700">Update Profile Picture</label>
                    <input type="file" name="profile_pic" class="w-full text-sm text-gray-700 border rounded p-1">
                    @error('profile_pic') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Form Fields -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $member->full_name) }}" class="w-full border rounded px-3 py-2" placeholder="Full Name">
                        @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Username</label>
                        <input type="text" value="{{ $member->username }}" class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}" class="w-full border rounded px-3 py-2" placeholder="Email">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full border rounded px-3 py-2" placeholder="Phone">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Father's Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $member->father_name) }}" class="w-full border rounded px-3 py-2" placeholder="Father's Name">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Mother's Name</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name', $member->mother_name) }}" class="w-full border rounded px-3 py-2" placeholder="Mother's Name">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $member->dob?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">NID / ID Number</label>
                        <input type="text" name="id_number" value="{{ old('id_number', $member->id_number) }}" class="w-full border rounded px-3 py-2" placeholder="NID">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Gender</label>
                        <select name="gender" class="w-full border rounded px-3 py-2">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $member->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $member->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Blood Group</label>
                        <input type="text" name="blood_group" value="{{ old('blood_group', $member->blood_group) }}" class="w-full border rounded px-3 py-2" placeholder="Blood Group">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Profession</label>
                        <input type="text" name="profession" value="{{ old('profession', $member->profession) }}" class="w-full border rounded px-3 py-2" placeholder="Profession">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Other Expertise</label>
                        <input type="text" name="other_expertise" value="{{ old('other_expertise', $member->other_expertise) }}" class="w-full border rounded px-3 py-2" placeholder="Other Expertise">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-medium mb-1">Address</label>
                        <textarea name="address" class="w-full border rounded px-3 py-2" rows="3">{{ old('address', $member->address) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Membership Type</label>
                        <input type="text" value="{{ $member->membership_type }}" class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
                    </div>

                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition font-medium">
                    Save Changes
                </button>
            </div>

        </form>

    </div>
</x-member-layout>
