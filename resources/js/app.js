import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

// Toast configuration for SweetAlert2
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Toast = Toast;
