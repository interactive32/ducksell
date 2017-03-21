# DuckSell - Laravel based PHP script

## Quick Introduction

[DuckSell](http://www.ducksell.com) allows you to sell your digital products on your own website and deliver them securely to your customers. You can easily sell various types of downloads. For example: pdf, ebook, program, template, photo, music, video, audio, mp3 etc.  

The script automatically generates a unique, secure download URL for every purchase which do not show the location of the download files on the server. For every purchase new license key is generated and it can be set to expire if required.  Unlimited products and multiple products per order are supported.

Tracking cookies are seamlessly used so you can know exactly where your customers are coming from and how they interact and convert on your website. Check referrals in your reports and optimize your marketing efforts for best conversion rates.

Note: there is no frontend here, you must create your own website first (using templates or wordpress). Once you have your website you can create "buy now" buttons in DuckSell to link those two together.

Demo:
 - [frontend](http://www.ducksell.com/dummy/) - this represents your website
 - [backend](http://www.ducksell.com/demo/) - the actual DuckSell script


## Main Features

 - Simple to setup 
 - Easy to manage 
 - No software to install or learn
 - Add products and you’re ready to start accepting orders
 - Real-time analytics
 - Use with your existing domain name
 - Works in almost any currency 
 - Invoicing supported
 - Detailed reports and export data
 - Multiple users with administrator or manager roles
 - Responsive and mobile friendly design
 - Multiple languages supported
 - With its pluggable architecture, additional functionality can be dynamically added to the application at runtime.


## Plugins

For more info about plugins see [http://www.ducksell.com/plugins.php](http://www.ducksell.com/plugins.php)

- 2Checkout intergration
- PayPal intergration
- BitPay integration
- API plugin
- Mailchimp intergration
- Free product
- Download info
- Customer support
- Blue customer area
- Custom invoice
- Product emails
- Customer files


## System Requirements

 - Apache web server
 - MySQL database
 - PHP 5.4 or above
 - mysqli, PDO, pdo_mysql, mcrypt, zip, openssl, mbstring and tokenizer PHP Extensions installed. Note that all of these are enabled in PHP by default on most servers.
 - Read and Write permissions for all files inside main folder.
 - As of PHP 5.5, some OS distributions may require you to manually install the PHP JSON extension. 


## Installation Instructions

 - Copy all files to your web server using your favorite FTP client
 - Create a database for DuckSell on your web server, as well as one MySQL user who has all privileges for accessing and modifying it. 
 - Import database from **database/database.sql** file using phpMyAdmin or similar database administration tool.
 - Configure database by opening **inc/.env-sample** in a text editor, fill in your information, and save it as **inc/.env**
 - Open terminal and go to **inc/** folder. Run `composer install` to install Laravel and all dependencies
 - Open the app in your browser and you should be able to see login screen
 - Login with admin@example.com / admin123


## Technology Used

 - PHP 5.4+, with Apache2 (see requirements)
 - Laravel 5.0 Framework
 - Bootstrap v3 frontend
 - PDO Database Driver
 - SMTP or PHP mail() email adapters
 - reCaptcha support
 - Database-Driven Session
 - Plugin system for additional functionality
 - This product includes GeoLite data created by MaxMind, available from [http://www.maxmind.com](http://www.maxmind.com)

## Contributing

You can contribute by sending pull requests or opening an issue on GitHub.

### License

DuckSell is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
