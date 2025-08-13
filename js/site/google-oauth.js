/**
 * Google OAuth Login Handler
 * Handles Google login button interactions and provides user feedback
 */

$(document).ready(function() {
    
    // Google login button click handler
    $('.google-login-btn').on('click', function(e) {
        e.preventDefault();
        
        // Show loading state
        var $btn = $(this);
        var originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Conectando con Google...');
        $btn.prop('disabled', true);
        
        // Get the OAuth URL from the href
        var oauthUrl = $btn.attr('href');
        
        // Redirect to Google OAuth
        setTimeout(function() {
            window.location.href = oauthUrl;
        }, 500);
    });
    
    // Handle OAuth error messages
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error') === 'oauth_failed') {
        // Show error message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error de autenticación',
                text: 'No se pudo completar el inicio de sesión con Google. Por favor, intenta nuevamente.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        } else {
            alert('Error: No se pudo completar el inicio de sesión con Google. Por favor, intenta nuevamente.');
        }
    }
    
    // Add hover effects for better UX
    $('.google-login-btn').hover(
        function() {
            $(this).find('i.fab.fa-google').css('color', '#4285f4');
        },
        function() {
            $(this).find('i.fab.fa-google').css('color', '');
        }
    );
    
    // Add focus styles for accessibility
    $('.google-login-btn').on('focus', function() {
        $(this).css('outline', '2px solid #4285f4');
        $(this).css('outline-offset', '2px');
    }).on('blur', function() {
        $(this).css('outline', '');
        $(this).css('outline-offset', '');
    });
});
