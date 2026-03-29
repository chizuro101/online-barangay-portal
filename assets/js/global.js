// Global JavaScript Functions

// Utility Functions
const Utils = {
    // Format currency
    formatCurrency: (amount) => {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(amount);
    },
    
    // Format date
    formatDate: (date, format = 'long') => {
        const options = format === 'long' 
            ? { year: 'numeric', month: 'long', day: 'numeric' }
            : { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(date).toLocaleDateString('en-PH', options);
    },
    
    // Format time
    formatTime: (time) => {
        return new Date(`1970-01-01T${time}`).toLocaleTimeString('en-PH', {
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    // Format datetime
    formatDateTime: (datetime) => {
        const date = new Date(datetime);
        return `${Utils.formatDate(date)} at ${Utils.formatTime(date.toTimeString())}`;
    },
    
    // Escape HTML
    escapeHtml: (text) => {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },
    
    // Debounce function
    debounce: (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Generate random ID
    generateId: () => {
        return Math.random().toString(36).substr(2, 9);
    },
    
    // Validate email
    validateEmail: (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },
    
    // Validate phone number
    validatePhone: (phone) => {
        const re = /^[+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/;
        return re.test(phone);
    },
    
    // Get file extension
    getFileExtension: (filename) => {
        return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
    },
    
    // Format file size
    formatFileSize: (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
};

// Alert System
const Alerts = {
    // Show success message
    success: (message, title = 'Success') => {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    },
    
    // Show error message
    error: (message, title = 'Error') => {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: '#dc3545'
        });
    },
    
    // Show warning message
    warning: (message, title = 'Warning') => {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            confirmButtonColor: '#ffc107'
        });
    },
    
    // Show info message
    info: (message, title = 'Information') => {
        Swal.fire({
            icon: 'info',
            title: title,
            text: message,
            confirmButtonColor: '#17a2b8'
        });
    },
    
    // Show confirmation dialog
    confirm: (message, title = 'Are you sure?', callback) => {
        Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    },
    
    // Show loading state
    loading: (message = 'Loading...') => {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },
    
    // Close loading state
    close: () => {
        Swal.close();
    }
};

// AJAX Helper
const Ajax = {
    // Send POST request
    post: (url, data, callback, errorCallback) => {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: () => {
                Alerts.loading();
            },
            success: (response) => {
                Alerts.close();
                if (callback) callback(response);
            },
            error: (xhr, status, error) => {
                Alerts.close();
                console.error('AJAX Error:', error);
                if (errorCallback) errorCallback(error);
                else Alerts.error('An error occurred. Please try again.');
            }
        });
    },
    
    // Send GET request
    get: (url, callback, errorCallback) => {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: () => {
                Alerts.loading();
            },
            success: (response) => {
                Alerts.close();
                if (callback) callback(response);
            },
            error: (xhr, status, error) => {
                Alerts.close();
                console.error('AJAX Error:', error);
                if (errorCallback) errorCallback(error);
                else Alerts.error('An error occurred. Please try again.');
            }
        });
    },
    
    // Upload file
    upload: (url, formData, callback, errorCallback) => {
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                Alerts.loading('Uploading...');
            },
            success: (response) => {
                Alerts.close();
                if (callback) callback(response);
            },
            error: (xhr, status, error) => {
                Alerts.close();
                console.error('Upload Error:', error);
                if (errorCallback) errorCallback(error);
                else Alerts.error('Upload failed. Please try again.');
            }
        });
    }
};

// Form Helper
const Forms = {
    // Serialize form to object
    serialize: (form) => {
        const formData = new FormData(form);
        const object = {};
        formData.forEach((value, key) => {
            if (object[key]) {
                if (!Array.isArray(object[key])) {
                    object[key] = [object[key]];
                }
                object[key].push(value);
            } else {
                object[key] = value;
            }
        });
        return object;
    },
    
    // Validate required fields
    validateRequired: (form) => {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    },
    
    // Validate email
    validateEmail: (field) => {
        const email = field.value.trim();
        if (!Utils.validateEmail(email)) {
            field.classList.add('is-invalid');
            return false;
        } else {
            field.classList.remove('is-invalid');
            return true;
        }
    },
    
    // Validate phone
    validatePhone: (field) => {
        const phone = field.value.trim();
        if (!Utils.validatePhone(phone)) {
            field.classList.add('is-invalid');
            return false;
        } else {
            field.classList.remove('is-invalid');
            return true;
        }
    },
    
    // Clear form
    clear: (form) => {
        form.reset();
        const invalidFields = form.querySelectorAll('.is-invalid');
        invalidFields.forEach(field => {
            field.classList.remove('is-invalid');
        });
    },
    
    // Show field error
    showError: (field, message) => {
        field.classList.add('is-invalid');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    },
    
    // Clear field error
    clearError: (field) => {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
};

// Table Helper
const Tables = {
    // Initialize DataTable
    init: (tableId, options = {}) => {
        const defaultOptions = {
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        };
        
        const mergedOptions = { ...defaultOptions, ...options };
        return $(tableId).DataTable(mergedOptions);
    },
    
    // Export to CSV
    exportCSV: (tableId, filename) => {
        const table = $(tableId).DataTable();
        const csvData = table.buttons().exportData();
        
        let csv = '';
        csvData.header.forEach((header, index) => {
            csv += (index > 0 ? ',' : '') + `"${header}"`;
        });
        csv += '\n';
        
        csvData.body.forEach((row, rowIndex) => {
            row.forEach((cell, cellIndex) => {
                csv += (cellIndex > 0 ? ',' : '') + `"${cell}"`;
            });
            csv += '\n';
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    },
    
    // Search table
    search: (tableId, searchTerm) => {
        const table = $(tableId).DataTable();
        table.search(searchTerm).draw();
    }
};

// Modal Helper
const Modals = {
    // Show modal
    show: (modalId) => {
        $(modalId).modal('show');
    },
    
    // Hide modal
    hide: (modalId) => {
        $(modalId).modal('hide');
    },
    
    // Load content into modal
    load: (modalId, url, callback) => {
        Ajax.get(url, (response) => {
            $(modalId + ' .modal-content').html(response);
            if (callback) callback();
        });
    },
    
    // Confirm action
    confirm: (modalId, callback) => {
        $(modalId).on('click', '.btn-confirm', function() {
            if (callback) callback();
            Modals.hide(modalId);
        });
    }
};

// File Upload Helper
const FileUpload = {
    // Preview image
    previewImage: (input, previewId) => {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    },
    
    // Validate file type
    validateType: (file, allowedTypes) => {
        const extension = Utils.getFileExtension(file.name);
        return allowedTypes.includes(extension.toLowerCase());
    },
    
    // Validate file size
    validateSize: (file, maxSize) => {
        return file.size <= maxSize;
    },
    
    // Format file info
    formatInfo: (file) => {
        return `${file.name} (${Utils.formatFileSize(file.size)})`;
    }
};

// Notification System
const Notifications = {
    // Show notification
    show: (message, type = 'info', duration = 5000) => {
        const notification = $(`
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut('slow', function() {
                $(this).remove();
            });
        }, duration);
    },
    
    // Show success notification
    success: (message) => {
        Notifications.show(message, 'success');
    },
    
    // Show error notification
    error: (message) => {
        Notifications.show(message, 'danger');
    },
    
    // Show warning notification
    warning: (message) => {
        Notifications.show(message, 'warning');
    },
    
    // Show info notification
    info: (message) => {
        Notifications.show(message, 'info');
    }
};

// Initialize on document ready
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Form validation on input
    $('form').on('input', 'input, textarea, select', function() {
        if ($(this).hasClass('is-invalid')) {
            Forms.clearError(this);
        }
    });
    
    // Email validation
    $('input[type="email"]').on('blur', function() {
        if (this.value.trim()) {
            Forms.validateEmail(this);
        }
    });
    
    // Phone validation
    $('input[type="tel"]').on('blur', function() {
        if (this.value.trim()) {
            Forms.validatePhone(this);
        }
    });
    
    // File upload preview
    $('input[type="file"][accept*="image"]').on('change', function() {
        const preview = $(this).siblings('img, .preview');
        if (preview.length) {
            FileUpload.previewImage(this, preview);
        }
    });
    
    // Confirm delete actions
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const message = $(this).data('confirm') || 'Are you sure you want to delete this item?';
        const url = $(this).attr('href');
        
        Alerts.confirm(message, 'Delete Item', function() {
            window.location.href = url;
        });
    });
    
    // Print button
    $('.btn-print').on('click', function(e) {
        e.preventDefault();
        window.print();
    });
    
    // Export buttons
    $('.btn-export-csv').on('click', function(e) {
        e.preventDefault();
        const tableId = $(this).data('table');
        const filename = $(this).data('filename') || 'export.csv';
        Tables.exportCSV(tableId, filename);
    });
    
    // Search functionality
    $('.table-search').on('keyup', Utils.debounce(function() {
        const tableId = $(this).data('table');
        const searchTerm = $(this).val();
        Tables.search(tableId, searchTerm);
    }, 300));
    
    // Modal form validation
    $('.modal form').on('submit', function(e) {
        if (!Forms.validateRequired(this)) {
            e.preventDefault();
            Alerts.error('Please fill in all required fields.');
        }
    });
    
    // Auto-resize textareas
    $('textarea').each(function() {
        this.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
});

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
    if (DEBUG_MODE) {
        Alerts.error('An error occurred: ' + e.error.message);
    }
});

// Global unhandled promise rejection handler
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
    if (DEBUG_MODE) {
        Alerts.error('An unexpected error occurred.');
    }
});
