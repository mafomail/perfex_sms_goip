# GoIP SMS Module for Perfex CRM
GoIP SMS Module for Perfex CRM
This module allows you to integrate GoIP GSM Gateway with Perfex CRM, enabling you to send SMS notifications directly from your CRM system using your own GSM hardware.
Features

- Direct GoIP Integration - Connect your GoIP GSM Gateway directly to Perfex CRM
- Standard SMS Interface - Integrates seamlessly with Perfex CRM's built-in SMS system
- All SMS Triggers - Supports all standard Perfex CRM SMS triggers (invoice notifications, payment confirmations, etc.)
- Cost Effective - Use your own SIM cards instead of expensive SMS services
- Easy Configuration - Simple setup through Perfex CRM admin interface
- Multi-language Support - Works with international characters
- Activity Logging - All SMS activities are logged in Perfex CRM

Supported GoIP Models
This module has been tested with:
- GoIP-1 (Single port GSM Gateway)
Other GoIP models should work with minor adjustments

Tested with firmware: GHSFVT-1.1-67 and newer

Requirements:
- Perfex CRM version 2.3.0 or higher
- GoIP GSM Gateway device
- PHP with cURL extension enabled
- Gateway IP must be whitelisted
- SIM card with SMS capability

Installation
Method 1: Download and Install
Download the latest release
Go to the Releases page
Download the latest goip_sms.zip file


Upload the module
Log in to your Perfex CRM admin panel
Navigate to Setup → Modules
Click on Upload Module button
Upload the goip_sms.zip file


Activate the module
Find the module in the list of available modules
Click on Activate button next to the module


Method 2: Manual Installation
Download or clone this repository
git clone https://github.com/mafomail/perfex_sms_goip.git

Copy files to your Perfex CRM installation
cp -r goip_sms modules/

Activate the module
Go to Setup → Modules in your Perfex CRM admin
Find "GoIP SMS" and click Activate



Configuration
1. GoIP Gateway Setup
First, ensure your GoIP device is properly configured:

Access GoIP web interface

Connect to your GoIP device via web browser (e.g., http://192.168.1.100)
Default login: admin / admin


Configure SIM card

Go to Configuration → SIM
Set PIN code if required
Turn on GSM module (click the N link in the main status table)


Test SMS functionality

Use Tools → Send SMS to test manual SMS sending
Ensure your SIM card has SMS credit/plan



2. Perfex CRM Configuration

Access SMS settings

Navigate to Setup → Settings → SMS
You should see "GoIP GSM Gateway" in the available gateways


Configure GoIP settings

Host/IP Address: Enter your GoIP device IP (e.g., 192.168.1.100)
Port: Usually 80 (default)
Username: GoIP admin username (default: admin)
Password: GoIP admin password


Activate the gateway

Check the "Active" checkbox for GoIP GSM Gateway
Click "Save Settings"


Test the configuration

Use the built-in SMS test feature in Perfex CRM
Check Utilities → Activity Log for any errors



Usage
Once configured, the GoIP SMS gateway will work automatically with all Perfex CRM SMS features:
Automatic SMS Triggers

Invoice Overdue Notice - Sent when invoice becomes overdue
Invoice Payment Recorded - Sent when payment is received
Staff Reminders - Custom staff reminder notifications
Contract Expiration - Contract expiration reminders
And many more...

Manual SMS Sending
SMS notifications will be sent automatically based on your configured triggers, or you can send manual SMS through any Perfex CRM feature that supports SMS.
Troubleshooting
Common Issues
1. SMS not being sent

Check GoIP device connectivity (ping the IP address)
Verify SIM card has SMS credit
Check GSM signal strength on GoIP device
Review Activity Log for error messages

2. Empty SMS messages

Check character encoding (module converts UTF-8 to ASCII)
Verify message parameter compatibility with your GoIP model

3. Module not appearing in SMS gateways

Ensure module is activated in Setup → Modules
Check PHP error logs for any syntax errors
Verify file permissions are correct

Debug Mode
Enable debug logging by checking Activity Log:

Go to Utilities → Activity Log
Look for entries starting with "GoIP SMS"
These will show request/response details

Log Files
Check these locations for additional debugging:

application/logs/ - Perfex CRM logs
Server error logs (location depends on your hosting)

API Reference
GoIP API Endpoints
The module uses standard GoIP HTTP API:
POST http://GOIP_IP/default/en_US/send.html
Parameters:
- u: username
- p: password  
- l: line number (1-8)
- n: phone number
- m: message content
Response Format
Successful response: OK or Sending,L1 Send SMS to:PHONE_NUMBER; ID:MESSAGE_ID
Error responses: ERROR with error description
Contributing
Contributions are welcome! Please feel free to submit a Pull Request.
Development Setup

Fork this repository
Create a feature branch: git checkout -b feature/new-feature
Make your changes
Test thoroughly with your GoIP device
Submit a pull request

Reporting Issues
If you encounter any issues:

Check the troubleshooting section above
Search existing issues on GitHub
Create a new issue with:

Perfex CRM version
GoIP model and firmware version
Error messages from Activity Log
Steps to reproduce the issue


License
This project is licensed under the GNU General Public License v3.0 - see the LICENSE file for details.

Support
GitHub Issues: Report bugs or request features
Documentation: Check this README and Perfex CRM documentation

Changelog
Version 1.0.0

Initial release
Support for GoIP-1 GSM Gateway
Basic SMS sending functionality
Integration with Perfex CRM SMS system
Character encoding support
Activity logging

Credits
Perfex CRM - Amazing CRM system
GoIP/Hybertone - GSM Gateway hardware
Community - Thanks to all contributors and testers


⭐ If this module helped you, please star this repository!
💝 Consider supporting the development by buying me a coffee or contributing to the project.
