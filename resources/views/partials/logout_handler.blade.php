<script>
// Handle logout with CSRF token expiration fallback
function handleLogout(event) {
    sessionStorage.setItem('isLoggedOut','true');
    
    // Try POST logout, fallback to GET if CSRF fails
    event.preventDefault();
    
    fetch('{{ route("logout") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    }).then(response => {
        if (response.status === 419) {
            // CSRF token expired, use fallback GET logout
            window.location.href = '{{ route("logout.expired") }}';
        } else {
            window.location.href = '{{ route("login") }}';
        }
    }).catch(error => {
        // Network error or other issue, use fallback
        window.location.href = '{{ route("logout.expired") }}';
    });
    
    return false;
}
</script>
