@if ($errors->any())
<div class="alert alert-danger alert-dismissible show" role="alert" id="errors-alert">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- <script>
    // Menghilangkan alert setelah 1 detik
    setTimeout(function () {
        const alert = document.getElementById('errors-alert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); // Hapus elemen setelah fade-out
        }
    }, 2000); // 1 detik
</script> --}}
