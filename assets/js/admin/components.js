// Admin Components JavaScript
$(document).ready(function() {
    // Initialize admin-specific components
    
    // Dashboard animations
    $('.stat-card').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
    
    // Quick actions button effects
    $('.quick-actions .btn').hover(
        function() {
            $(this).addClass('shadow');
        },
        function() {
            $(this).removeClass('shadow');
        }
    );
    
    // Activity feed animations
    $('.activity-item').hide().each(function(index) {
        $(this).delay(100 * index).fadeIn(500);
    });
    
    // Form validation enhancements
    $('form').on('submit', function(e) {
        var form = $(this);
        var requiredFields = form.find('[required]');
        
        requiredFields.each(function() {
            var field = $(this);
            if (!field.val()) {
                field.addClass('is-invalid');
                e.preventDefault();
            } else {
                field.removeClass('is-invalid');
            }
        });
    });
    
    // Remove invalid class on input
    $('input, textarea, select').on('input', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Confirm delete actions
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
});

// Utility functions
function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
}

function showErrorMessage(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
    });
}

function showLoadingSpinner() {
    Swal.fire({
        title: 'Please wait...',
        html: 'Processing your request...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}
