# Google OAuth Integration for EstiloSPA

This document describes the Google OAuth integration that has been added to the EstiloSPA registration and login system.

## Overview

The Google OAuth integration allows users to register and login using their Google accounts, providing a seamless authentication experience.

## Files Modified/Created

### New Files
- `oauth2callback.php` - Google OAuth callback handler
- `js/site/google-oauth.js` - JavaScript for Google login button interactions
- `sql/2025/20250115_google_oauth.sql` - Database migration for Google OAuth
- `GOOGLE_OAUTH_README.md` - This documentation file

### Modified Files
- `site/views/registro.php` - Added Google login button to registration form
- `site/views/login.php` - Added Google login button to login form
- `classes/User.php` - Added Google OAuth methods
- `ajax/site/users.php` - Added Google OAuth AJAX handler
- `site/controllers/registro.php` - Added Google OAuth JavaScript
- `site/controllers/login.php` - Added Google OAuth JavaScript
- `src/css/styles.styl` - Added Google login button styles

## Database Changes

The following SQL migration needs to be run:

```sql
-- Add google_id column to users table for Google OAuth integration
ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL AFTER mail;

-- Add index for better performance when searching by google_id
CREATE INDEX idx_users_google_id ON users(google_id);

-- Add unique constraint to prevent duplicate google_id entries
ALTER TABLE users ADD UNIQUE KEY unique_google_id (google_id);
```

## Configuration

The Google OAuth integration uses the existing Google API configuration from `calendar/client_secret.json`. The configuration includes:

- Client ID: `995346835833.apps.googleusercontent.com`
- Redirect URI: `https://www.estilospa.com/oauth2callback.php`
- Scopes: `email` and `profile`

## Features

### Registration Flow
1. User clicks "Continuar con Google" button
2. User is redirected to Google OAuth consent screen
3. After authorization, user is redirected to `oauth2callback.php`
4. If user doesn't exist, a new account is created automatically
5. User is logged in and redirected to home page

### Login Flow
1. User clicks "Continuar con Google" button
2. User is redirected to Google OAuth consent screen
3. After authorization, user is redirected to `oauth2callback.php`
4. If user exists, they are logged in
5. If user doesn't exist, a new account is created
6. User is redirected to home page

### User Experience Features
- Loading states during OAuth process
- Error handling with user-friendly messages
- Automatic account activation for OAuth users
- Profile picture import from Google
- Seamless linking of existing accounts

## Security Features

- Secure OAuth 2.0 flow
- Unique Google ID constraint in database
- Password generation for OAuth users
- Session management integration
- Error handling for failed OAuth attempts

## User Interface

### Registration Page
- Google login button prominently displayed at the top
- Clear separation between OAuth and traditional registration
- Responsive design for mobile devices

### Login Page
- Google login button alongside traditional login form
- Consistent styling with existing design
- Accessible focus states and hover effects

## JavaScript Features

- Loading states during OAuth process
- Error message handling
- Hover effects for better UX
- Accessibility improvements
- Integration with existing SweetAlert2 for notifications

## CSS Styling

The Google login button follows Google's design guidelines:
- White background with gray border
- Google's signature colors on hover
- Consistent with existing button styles
- Responsive design

## Error Handling

- OAuth failures are caught and displayed to users
- Database errors are handled gracefully
- Network issues are communicated clearly
- Fallback to traditional registration/login

## Future Enhancements

Potential improvements for future versions:
- Facebook OAuth integration
- Apple Sign-In integration
- Two-factor authentication
- Account linking/unlinking
- Social login analytics

## Testing

To test the integration:
1. Run the database migration
2. Ensure Google API credentials are valid
3. Test registration flow with new Google accounts
4. Test login flow with existing Google accounts
5. Test error scenarios (invalid credentials, network issues)
6. Test on different devices and browsers

## Support

For issues or questions about the Google OAuth integration, please refer to:
- Google OAuth 2.0 documentation
- EstiloSPA development team
- Google Cloud Console for API configuration
