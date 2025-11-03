<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::whereIn('name', ['super_admin', 'Super Admin'])->delete();
        // 1) Create roles
        $superAdmin  = Role::firstOrCreate(['name' => 'super_administrator']);
        $editor      = Role::firstOrCreate(['name' => 'editor']);
        $admin       = Role::firstOrCreate(['name' => 'administrator']);
        $viewer      = Role::firstOrCreate(['name' => 'viewer']);
        $member      = Role::firstOrCreate(['name' => 'member']); // optional for custom member panel

        $this->command->info('Roles created: super_administrator, editor, administrator, viewer, member');

        // 2) Load all permissions created by Filament Shield
        $allPermissions = Permission::all();

        if ($allPermissions->isEmpty()) {
            $this->command->warn('No permissions found in DB. Did you run php artisan shield:generate --all ?');
        } else {
            // 3) Super Administrator → all permissions
            $superAdmin->syncPermissions($allPermissions);
            $this->command->info('Assigned ALL permissions to super_administrator.');

            // 4) Editor → create/edit/update/publish/restore
            $editorPerms = $allPermissions->filter(fn(Permission $perm) => 
                Str::contains($perm->name, ['create', 'edit', 'update', 'publish', 'restore'])
            )->pluck('name')->unique()->toArray();

            if (!empty($editorPerms)) {
                $editor->syncPermissions($editorPerms);
                $this->command->info('Assigned editor permissions (create/edit/update/publish/restore).');
            }

            // 5) Administrator → view/edit/update
            $adminPerms = $allPermissions->filter(fn(Permission $perm) => 
                Str::contains($perm->name, ['view', 'edit', 'update'])
            )->pluck('name')->unique()->toArray();

            if (!empty($adminPerms)) {
                $admin->syncPermissions($adminPerms);
                $this->command->info('Assigned administrator permissions (view/edit/update).');
            }

            // 6) Viewer → view/read/list/download only
            $viewerPerms = $allPermissions->filter(fn(Permission $perm) => 
                Str::contains($perm->name, ['view', 'read', 'list', 'download'])
            )->pluck('name')->unique()->toArray();

            if (!empty($viewerPerms)) {
                $viewer->syncPermissions($viewerPerms);
                $this->command->info('Assigned viewer permissions (view/read/list/download).');
            }

            // 7) Member → limited personal permissions (custom member panel)
            $memberPerms = $allPermissions->filter(fn(Permission $perm) => 
                Str::contains($perm->name, ['view', 'read', 'list', 'download', 'profile', 'own'])
            )->pluck('name')->unique()->toArray();

            if (!empty($memberPerms)) {
                $member->syncPermissions($memberPerms);
                $this->command->info('Assigned member permissions (view/read/list/download/profile/own).');
            }
        }

        // 8) Attach super_administrator role to the first user automatically
        $userClass = class_exists(\App\Models\User::class)
            ? \App\Models\User::class
            : (class_exists(\Modules\Members\Models\Member::class) ? \Modules\Members\Models\Member::class : null);

        if ($userClass) {
            $first = $userClass::first();
            if ($first) {
                $first->assignRole('super_administrator');
                $this->command->info("Assigned 'super_administrator' role to first user (id={$first->getKey()}).");
            } else {
                $this->command->warn("No users found. Create a user with `php artisan make:filament-user` first.");
            }
        } else {
            $this->command->warn('No recognized user model found. Skipping auto-assign.');
        }

        // 9) Clear Spatie permission cache (fixed)
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $this->command->info('Permission cache cleared.');
    }
}
