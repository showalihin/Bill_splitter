<x-app-layout>
    <x-slot name="header">
        <div class="rs-flex rs-justify-between rs-items-center">
            <div>
                <h2 style="margin: 0;">User Management</h2>
                <p class="rs-text-sm rs-text-secondary" style="margin: 0.25rem 0 0;">View all registered users on the platform</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="rs-btn rs-btn-secondary rs-btn-sm">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="rs-slide-up">
        <div class="rs-card">
            <div class="rs-table-wrapper">
                <table class="rs-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Registered On</th>
                            <th>Total Bills Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="rs-font-medium">{{ $user->name }}</div>
                                    <div class="rs-text-xs rs-text-secondary">{{ $user->email }}</div>
                                </td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="rs-badge rs-badge-primary">Admin</span>
                                    @else
                                        <span class="rs-badge rs-badge-neutral">User</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    {{ $user->bill_sessions_count }} bills
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="rs-mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
