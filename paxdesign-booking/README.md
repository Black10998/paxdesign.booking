# PAXdesign Booking System v2.2

Professional WordPress appointment booking plugin with minimal, enterprise-style interface.

**Note:** This is NOT a live chat system. The floating button visually resembles a support/chat widget, but its purpose is **appointment booking and contact scheduling**.

## Features

✅ **Floating button** (looks like support chat, but for booking appointments)  
✅ **Professional booking dialog** (no animations, no hover effects)  
✅ **4-step appointment booking process**:
  1. Select who to contact (team member)
  2. Choose service (for Adam only - 10 pricing options)
  3. Choose appointment date & time
  4. Fill contact details & purpose

✅ **Service selection** for Adam (Website, Web App, Android, iOS, etc.)  
✅ **Email notifications** to info@paxdesign.at with service info  
✅ **Customer confirmations**  
✅ **Admin dashboard** to view bookings  
✅ **Settings page** for email configuration  
✅ **Database storage** with service tracking  
✅ **Fully responsive**  
✅ **No external dependencies**

## Design Philosophy

- ❌ No animations
- ❌ No hover effects
- ❌ No motion or transitions
- ✅ Static, calm, professional
- ✅ Clean spacing
- ✅ Modern typography
- ✅ Enterprise-style UI

## Installation

1. Download `paxdesign-booking.zip`
2. Go to **WordPress Admin → Plugins → Add New**
3. Click **"Upload Plugin"**
4. Choose the ZIP file
5. Click **"Install Now"**
6. Click **"Activate Plugin"**

Done! The floating button appears automatically on all pages.

## Configuration

After activation:

1. Go to **Booking System → Einstellungen**
2. Set email addresses (default: info@paxdesign.at)
3. Save changes

## Usage

**Frontend:**
- Small floating button appears in bottom-right corner (looks like support chat)
- Click to open appointment booking dialog
- **Step 1:** Choose who you want to contact (team member)
- **Step 2:** Select appointment date & time slot
- **Step 3:** Fill contact details & select purpose (consultation, support, project discussion, etc.)
- Submit appointment request

**Admin:**
- View all bookings in **Booking System** menu
- Configure emails in **Einstellungen**

## Email Notifications

**Admin receives:**
- Team member selected
- Date & time
- Customer details
- Purpose & message

**Customer receives:**
- Booking confirmation
- Team member info
- Contact information

## Technical Details

- **Version**: 2.2.0
- **Requires**: WordPress 5.0+
- **PHP**: 7.0+
- **Database**: Auto-creates table on activation with service column
- **Dependencies**: jQuery (included in WordPress)

## File Structure

```
paxdesign-booking/
├── paxdesign-booking.php
├── assets/
│   ├── css/booking-styles.css
│   └── js/booking-script.js
├── templates/
│   ├── booking-widget.php
│   ├── admin-page.php
│   └── settings-page.php
└── README.md
```

## Support

- Email: info@paxdesign.at
- Phone: +43 681 20543638
- Website: https://paxdesign.at

## License

GPL v2 or later

## Changelog

### 2.2.0 (2026-01-02)
- Added service selection for Adam (10 pricing options)
- Service cards with icons, prices, and descriptions
- Popular and Premium badges for highlighted services
- Service info in email notifications
- Service column in database
- 4-step booking process (team → service → date/time → details)
- Updated UI with service selection grid

### 2.0.0 (2025-12-31)
- Complete redesign: minimal, professional, enterprise-style
- Floating chat-style button (bottom-right)
- Small support dialog (not full-screen)
- Removed all animations and hover effects
- Static, calm, trustworthy UI
- Clean spacing and modern typography

### 1.0.0 (2025-12-31)
- Initial release
