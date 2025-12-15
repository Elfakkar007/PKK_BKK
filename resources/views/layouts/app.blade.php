<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ settings('site_name', 'BKK SMKN 1 Purwosari') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
</head>
<body>
    @include('components.navbar')
    
    <!-- SweetAlert for Flash Messages -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0d6efd',
                timer: 3000,
                timerProgressBar: true,
            });
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545',
            });
        });
    </script>
    @endif
    
    @if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#ffc107',
            });
        });
    </script>
    @endif
    
    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorList = '<ul class="text-start mb-0">';
            @foreach($errors->all() as $error)
                errorList += '<li>{{ $error }}</li>';
            @endforeach
            errorList += '</ul>';
            
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan',
                html: errorList,
                confirmButtonColor: '#dc3545',
                width: '600px',
            });
        });
    </script>
    @endif
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- Global Confirmation Scripts -->
    <script>
        // Konfirmasi hapus dengan SweetAlert2
        function confirmDelete(event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
        
        // Konfirmasi approve
        function confirmApprove(event, message = 'Approve data ini?') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Approve!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
        
        // Konfirmasi submit form
        function confirmSubmit(event, message = 'Yakin ingin menyimpan perubahan?') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
        
        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Link berhasil disalin',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>