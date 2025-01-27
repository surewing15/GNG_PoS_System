
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Saved Successfully',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK'
    });
</script>
@endif
@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session('error') }}',
     confirmButtonText: 'OK'
});
</script>
@endif
@if(session('delete'))
<script>
Swal.fire({
icon: 'success', // Use 'success' for a successful deletion
title: 'Deleted',
text: '{{ session('delete') }}',
confirmButtonText: 'OK'
});
</script>
@endif

