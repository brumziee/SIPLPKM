<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <!-- BEGIN NAVBAR TOGGLER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- END NAVBAR TOGGLER -->

        <div class="flex-row navbar-nav order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="p-0 px-2 nav-link d-flex lh-1" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url(./static/avatars/userprofile.png)"> </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ auth()->user() ? auth()->user()->name : '' }}</div>
                        {{-- <div class="mt-1 small text-secondary">
                            {{ auth()->user() ? auth()->user()->getRoleNames()->first() : '' }}
                        </div> --}}
                    </div>
                </a>

                <!-- Dropdown hanya berisi Logout -->
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" class="dropdown-item text-danger fw-semibold"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Logout
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="navbar-menu">
        </div>
    </div>
</header>

<script>
    function markAsRead(productId) {
        fetch('{{ route("notifications.mark-as-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationBadge(data.notification_count);
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memproses notifikasi', 'error');
        });
    }

    function markAllAsRead() {
        fetch('{{ route("notifications.mark-all-as-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationBadge(data.notification_count);
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memproses notifikasi', 'error');
        });
    }

    function updateNotificationBadge(count) {
        const badge = document.querySelector('.navbar .badge');
        if (badge) badge.style.display = count > 0 ? 'inline' : 'none';
        if (badge && count > 0) badge.textContent = count > 99 ? '99+' : count;
    }

    function showNotification(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    setInterval(() => {
        fetch('{{ route("notifications.count") }}')
            .then(response => response.json())
            .then(data => updateNotificationBadge(data.notification_count))
            .catch(error => console.error('Error refreshing notification count:', error));
    }, 300000);
</script>
