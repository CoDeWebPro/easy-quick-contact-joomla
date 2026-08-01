# Easy Quick Contact Module for Joomla!

A customizable contact form module for Joomla! with popup modal support and built-in spam protection.

## Overview

Easy Quick Contact is a lightweight, user-friendly contact form module that allows visitors to quickly get in touch with you. The module features customizable colors, popup modal support, and a simple math-based CAPTCHA to prevent spam.

**Important Notice:** This module is **not an official JoomBoost product**. It is a community remake originally based on a JoomBoost module and has been reworked and modernized by Sergey Shcherbakov to support modern Joomla! versions using current best practices and coding standards.

## Features

- **Dual Display Modes**: Choose between inline form display or popup modal
- **Customizable Design**: Full color customization for buttons, inputs, and borders
- **Spam Protection**: Built-in math CAPTCHA system
- **Responsive Layout**: Mobile-friendly design
- **Field Validation**: Client-side and server-side validation
- **Email Notifications**: Sends form submissions to configured recipient
- **Multi-language Support**: Includes English, Russian (Русский), and Ukrainian (Українська) translations
- **Modern Architecture**: Built with Joomla! 4+ namespace structure and dependency injection

## Supported Joomla! Versions

- **Joomla! 4.x** ✅
- **Joomla! 5.x** ✅
- **Joomla! 6.x** ✅

This module has been modernized to work with Joomla! 4.0+ and utilizes modern PHP practices including:
- PSR-4 autoloading and namespaces
- Dependency injection
- Web Asset Manager
- Modern Joomla! API patterns

## Installation

1. Download the latest release from the [Releases](../../releases) page
2. Log in to your Joomla! administrator panel
3. Navigate to **System** → **Extensions** → **Install**
4. Upload the module package file
5. After installation, go to **Content** → **Site Modules**
6. Find "Easy Quick Contact" and click to configure

## Configuration

### Basic Settings

- **Display Mode**: Choose between normal inline display or popup modal
- **Popup Button Text**: Customize the button text for popup mode
- **Intro Text**: Introductory message displayed above the form
- **Field Labels**: Customize labels for Name, Email, Phone, Message fields
- **CAPTCHA**: Enable/disable simple math CAPTCHA
- **Submit Button Text**: Customize the submit button text
- **Recipient Email**: Email address where form submissions will be sent (required)
- **Email Subject**: Subject line for notification emails

### Color Customization

- **Button Background**: Primary button color
- **Button Hover Background**: Button color on hover
- **Button Text**: Button text color
- **Input Background**: Form field background color
- **Input Border**: Form field border color
- **Input Text**: Form field text color

### Advanced Settings

- **Layout**: Choose alternative layouts if available
- **Module Class Suffix**: Add custom CSS classes
- **Caching**: Configure module caching options

## Usage

After installation and configuration:

1. Assign the module to a position in your template
2. Set the module to display on desired menu items
3. Configure the recipient email address (required)
4. Customize colors and labels to match your site design
5. Choose between inline or popup display mode

### Display Modes

**Inline Mode**: The form displays directly in the module position.

**Popup Mode**: A button is displayed that opens the form in a modal overlay when clicked.

## File Structure

```
mod_easyquickcontact/
├── css/
│   ├── modal.css           # Popup modal styles
│   └── style.css           # Main module styles
├── js/
│   └── main.js             # Modal functionality
├── language/
│   ├── en-GB/              # English
│   │   ├── mod_easyquickcontact.ini
│   │   └── mod_easyquickcontact.sys.ini
│   ├── ru-RU/              # Russian
│   │   ├── mod_easyquickcontact.ini
│   │   └── mod_easyquickcontact.sys.ini
│   └── uk-UA/              # Ukrainian
│       ├── mod_easyquickcontact.ini
│       └── mod_easyquickcontact.sys.ini
├── services/
│   └── provider.php        # Dependency injection container
├── src/
│   ├── Dispatcher/
│   │   └── Dispatcher.php  # Module dispatcher
│   └── Helper/
│       └── EasyQuickContactHelper.php  # Core functionality
├── tmpl/
│   └── default.php         # Module template
├── index.html              # Security file
└── mod_easyquickcontact.xml  # Module manifest
```

## Technical Details

### Form Fields

- **Name** (required): Text input
- **Email** (required): Email input with validation
- **Phone** (optional): Text input
- **Message** (required): Textarea
- **CAPTCHA** (optional): Simple math question (e.g., "3 + 5 = ?")

### Security Features

- CSRF token validation
- Session-based CAPTCHA verification
- Email validation
- XSS protection through proper output escaping
- Input sanitization

### Email Handling

The module uses Joomla!'s built-in mailer with:
- Configurable sender from global configuration
- Reply-to set to submitter's email
- Plain text email format
- Error logging for debugging

## Development

### Requirements

- PHP 7.4 or higher
- Joomla! 4.0 or higher
- Modern web browser with JavaScript enabled

### Modernization Changes

This community version includes:

- **Namespace structure**: Moved to `JoomBoost\Module\EasyQuickContact` namespace
- **Service provider**: Implements proper dependency injection
- **Web Asset Manager**: Modern asset registration and loading
- **Coding standards**: Follows Joomla! and PSR coding standards
- **Security improvements**: Enhanced validation and sanitization
- **Modern PHP**: Uses type declarations, null coalescing, and other modern features

## Contributing

Contributions are welcome! If you'd like to improve this module:

1. Fork the repository
2. Create a feature branch
3. Make your changes following Joomla! coding standards
4. Test thoroughly on Joomla! 4.x and 5.x
5. Submit a pull request

## License

GNU General Public License v2.0 or later

## Credits

- **Original Module**: JoomBoost (https://www.joomboost.com)
- **Remake / Rework**: Sergey Shcherbakov (https://sergeyscherbakov.ru, me@sergeyscherbakov.ru, GitHub: https://github.com/CoDeWebPro)
- **Module Repository**: https://github.com/CoDeWebPro/easy-quick-contact-joomla
- **Community Modernization**: Reworked for Joomla! 4+ compatibility

## Support

This is a community-maintained module. For issues and feature requests, please use the [GitHub Issues](../../issues) page.

## Changelog

### Version 2.0.0 (2026-08)
- Complete modernization for Joomla! 4.x/5.x/6.x compatibility
- Implemented namespace structure and PSR-4 autoloading
- Added dependency injection container
- Migrated to Web Asset Manager
- Enhanced security with modern validation
- Improved code quality and standards compliance
- Updated HTML5 form validation
- Responsive design improvements
- **Added Russian (ru-RU) language support**
- **Added Ukrainian (uk-UA) language support**

---

**Note**: This module is provided as-is without warranty. Always test in a development environment before deploying to production.
