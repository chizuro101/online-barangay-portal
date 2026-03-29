# Barangay Portal File Organization Plan

## Current Issues
- Files are scattered in root directory
- No clear separation of concerns
- Enhanced files mixed with original files
- No proper version control structure

## Proposed Organization Structure

### 1. Core System Files (Root Directory)
```
├── index.php                    # Main landing page
├── dbConfig.php                 # Database configuration
├── functions.php                # Core functions
├── admin_Actions.php           # Main admin actions (merged)
├── config.php                   # System configuration (new)
├── constants.php                # System constants (new)
└── README.md                    # System documentation
```

### 2. Admin Panel (admin/)
```
admin/
├── dashboard.php                # Main admin dashboard
├── residents.php                # Resident management
├── officials.php                 # Officials management
├── announcements.php            # Announcement management
├── documents.php                # Document management
├── appointments.php             # Appointment management
├── inventory.php                # Inventory management
├── financial.php                 # Financial management
├── projects.php                 # Project management
├── complaints.php               # Complaint management
├── meetings.php                 # Meeting management
├── notifications.php            # Notification management
├── settings.php                 # System settings
└── includes/
    ├── header.php               # Admin header
    ├── sidebar.php              # Admin sidebar
    ├── footer.php               # Admin footer
    └── modals.php               # All admin modals
```

### 3. User Panel (user/)
```
user/
├── dashboard.php                # User dashboard
├── announcements.php            # View announcements
├── documents.php                # View documents
├── officials.php                # View officials
├── appointments.php             # User appointments
├── requests.php                 # Service requests
├── complaints.php               # User complaints
├── profile.php                  # User profile
└── includes/
    ├── header.php               # User header
    ├── sidebar.php              # User sidebar
    ├── footer.php               # User footer
    └── modals.php               # User modals
```

### 4. API/Services (api/)
```
api/
├── auth.php                     # Authentication endpoints
├── residents.php                # Resident API
├── officials.php                # Officials API
├── appointments.php             # Appointment API
├── inventory.php                # Inventory API
├── financial.php                # Financial API
├── projects.php                 # Project API
├── notifications.php            # Notification API
└── middleware/
    ├── auth.php                 # Authentication middleware
    ├── cors.php                 # CORS handling
    └── validation.php           # Input validation
```

### 5. Database (database/)
```
database/
├── migrations/
│   ├── 001_initial_schema.sql  # Initial database
│   ├── 002_enhancements.sql    # System enhancements
│   └── 003_updates.sql         # Future updates
├── seeds/
│   ├── positions.sql           # Default positions
│   ├── settings.sql            # Default settings
│   └── sample_data.sql         # Sample data
└── backups/
    └── .gitkeep               # Database backups
```

### 6. Assets (assets/)
```
assets/
├── css/
│   ├── admin/
│   │   ├── dashboard.css       # Admin dashboard styles
│   │   ├── residents.css       # Resident management styles
│   │   └── components.css      # Admin components
│   ├── user/
│   │   ├── dashboard.css       # User dashboard styles
│   │   └── components.css      # User components
│   ├── global.css              # Global styles
│   └── responsive.css          # Responsive styles
├── js/
│   ├── admin/
│   │   ├── dashboard.js        # Admin dashboard scripts
│   │   ├── residents.js        # Resident management scripts
│   │   └── components.js       # Admin components
│   ├── user/
│   │   ├── dashboard.js        # User dashboard scripts
│   │   └── components.js       # User components
│   ├── global.js               # Global scripts
│   └── utils.js                # Utility functions
├── images/
│   ├── logos/
│   ├── icons/
│   ├── banners/
│   └── uploads/
├── fonts/
└── vendor/
    ├── bootstrap/
    ├── jquery/
    ├── fontawesome/
    └── chart.js/
```

### 7. Classes/Models (classes/)
```
classes/
├── User.php                     # User class
├── Resident.php                 # Resident model
├── Official.php                 # Official model
├── Appointment.php              # Appointment model
├── Inventory.php                # Inventory model
├── Financial.php                # Financial model
├── Project.php                  # Project model
├── Complaint.php                # Complaint model
├── Meeting.php                  # Meeting model
├── Notification.php             # Notification model
├── Database.php                 # Database wrapper
├── Auth.php                     # Authentication class
├── Logger.php                   # Activity logger
└── Utils.php                    # Utility functions
```

### 8. Templates (templates/)
```
templates/
├── emails/
│   ├── welcome.php              # Welcome email
│   ├── appointment.php         # Appointment reminder
│   └── notification.php        # System notification
├── pdf/
│   ├── certificate.php          # Certificate template
│   ├── report.php               # Report template
│   └── receipt.php              # Receipt template
└── layouts/
    ├── admin.php                # Admin layout
    ├── user.php                 # User layout
    └── auth.php                 # Auth layout
```

### 9. Logs (logs/)
```
logs/
├── access.log                   # Access logs
├── error.log                    # Error logs
├── activity.log                 # Activity logs
├── security.log                 # Security logs
└── .gitkeep                     # Keep directory
```

### 10. Config (config/)
```
config/
├── database.php                 # Database config
├── app.php                      # Application config
├── auth.php                     # Authentication config
├── email.php                    # Email config
├── upload.php                   # Upload config
└── security.php                 # Security config
```

### 11. Storage (storage/)
```
storage/
├── uploads/
│   ├── documents/               # Document uploads
│   ├── images/                  # Image uploads
│   ├── avatars/                 # User avatars
│   └── temp/                    # Temporary files
├── cache/
│   ├── views/                   # View cache
│   └── data/                    # Data cache
└── logs/                        # Application logs
```

## Migration Steps

### Phase 1: Create Directory Structure
1. Create all necessary directories
2. Set proper permissions
3. Create .htaccess files for security

### Phase 2: Move Core Files
1. Move admin files to admin/ directory
2. Move user files to user/ directory
3. Update all file references

### Phase 3: Refactor Code
1. Extract common components
2. Create reusable includes
3. Implement proper MVC pattern

### Phase 4: Update Configuration
1. Create config files
2. Update paths and includes
3. Test all functionality

### Phase 5: Clean Up
1. Remove duplicate files
2. Update documentation
3. Create deployment guide

## Benefits of This Organization

✅ **Separation of Concerns** - Each directory has specific purpose  
✅ **Maintainability** - Easier to find and update files  
✅ **Scalability** - Easy to add new features  
✅ **Security** - Better access control  
✅ **Collaboration** - Team members can work on different areas  
✅ **Version Control** - Better git history and conflicts  
✅ **Performance** - Optimized file loading  
✅ **Testing** - Easier to unit test individual components  

## Implementation Priority

1. **High Priority**: Core structure, admin panel, database
2. **Medium Priority**: User panel, API endpoints, assets
3. **Low Priority**: Templates, logs, advanced features

Would you like me to start implementing this organization structure?
