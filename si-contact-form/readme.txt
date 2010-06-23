=== Fast and Secure Contact Form ===
Contributors: Mike Challis
Author URI: http://www.642weather.com/weather/scripts.php
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8086141
Tags: Akismet, captcha, contact, contact form, form, mail, email, spam, multilingual, wpmu, buddypress
Requires at least: 2.6
Tested up to: 3.0
Stable tag: trunk

A super customizable contact form that lets your visitors send you email. Blocks all common spammer tactics. Spam is no longer a problem.

== Description ==

Fast and Secure Contact Form for WordPress. This contact form lets your visitors send you a quick E-mail message. Blocks all common spammer tactics. Spam is no longer a problem. Includes a CAPTCHA and Akismet support. Additionally, the plugin has a multi-form feature, optional extra fields, and an option to redirect visitors to any URL after the message is sent. Does not require JavaScript.

[Plugin URI]: (http://www.642weather.com/weather/scripts-wordpress-si-contact.php)

Features:
--------
 * Configure Options from Admin panel.
 * Multi-Form feature that allows you to have many different forms on your site.
 * Uses simple inline error messages.
 * Reloads form data and warns user if user forgets to fill out a field.
 * Validates syntax of E-mail address.
 * Can hide subject and message fields for use as a newsletter signup.
 * Optional redirect to any URL after message sent.
 * Valid coding for HTML, XHTML, Section 508, and WAI Accessibility.
 * JavaScript is not required.
 * Setting to hide the CAPTCHA from logged in users and or admins.
 * Multi "E-mail to" contact support.
 * Auto form fill for logged in users.
 * Customizable form field titles.
 * Customizable CSS style.
 * Optional extra fields of any type: text, textarea, checkbox, radio, select, date.
 * Sends E-mail with UTF-8 character encoding for US and International character support.
 * I18n language translation support (see FAQ)

Security:
--------
 * It has very tight security, stops spammer tricks.
 * Akismet spam protection support.
 * Spam checks E-mail address input from common spammer tactics...
prevents spammer forcing to:, cc:, bcc:, newlines, and other E-mail injection attempts to spam the world.
 * Makes sure the contact form was posted from your blog domain name only.
 * Filters all form inputs from HTML and other nasties.
 * E-mail message footer shows blog username(if logged on), Date/Time timestamp, IP address, and user agent (browser version) of user who contacted you.

Captcha Image Support:
---------------------
 * Open-source free PHP CAPTCHA library by www.phpcaptcha.org is included (can be disabled in Options)
 * Abstract background with multi colored, angled, and transparent text
 * Arched lines through text
 * Generates audible CAPTCHA files in WAV format
 * Refresh button to reload captcha if you cannot read it

Requirements/Restrictions:
-------------------------
 * Works with Wordpress 2.6+, WPMU, and BuddyPress
 * PHP 4.0.6 or above with GD2 library support.
 * PHP register_globals must be set to OFF

== Installation ==

1. Upload the `si-contact-form` folder to the `/wp-content/plugins/` directory, or download through the `Plugins` menu in WordPress

2. Activate the plugin through the `Plugins` menu in WordPress. Look for the Settings link to configure the Options. 

3. You must add the shortcode `[si-contact-form form='1']` in a Page(not a Post). That Page will become your Contact Form. Here is how: Log into your blog admin dashboard. Click `Pages`, click `Add New`, add a title to your page, enter the shortcode `[si-contact-form form='1']` in the page, uncheck `Allow Comments`, click `Publish`. 

4. Test an email from your form.

5. Updates are automatic. Click on "Upgrade Automatically" if prompted from the admin menu. If you ever have to manually upgrade, simply deactivate, uninstall, and repeat the installation steps with the new version.


= I just installed this and do not get any email from it, what could be wrong? =

1. Use the E-mail test feature in options, if you are not receiving mail, try it. It will display troubleshooting information.
 
2. Look for a warning message on the Options page for when the web host has mail() function disabled.

3. Make sure you have the correct "E-mail To:" set in options. If that is correct, then this setting in the contact form options might help you....
"E-mail From:" ... Normally you should leave this blank because the email will be from the sender. If your contact form does not send any email, then set "E-mail To:" and "E-mail From:" to an email address on the SAME domain as your web site. This fix works for web hosts that do not allow PHP to send email unless the email address is on the same web domain. They do this to help prevent spam.

4. Here is another option for you:
Get a free gmail account.
Install the plugin called [WP Mail SMTP](http://wordpress.org/extend/plugins/wp-mail-smtp/),  then set it to use gmail SMTP for mail.
Set these settings for "WP Mail SMTP":
Mailer: SMTP, 
SMTP Host: smtp.gmail.com, 
SMTP Port: 465, 
Encryption: SSL, 
Authentication: Yes, 
Username: your full gmail address, 
Password: your mail password.

Now use gmail to check for your contact form mail, or set gmail to forward the mail to your other address.


== Screenshots ==

1. screenshot-1.gif is the contact form.

2. screenshot-2.gif is the contact form showing the inline error messages.

3. screenshot-3.gif is the `Contact Form options` tab on the `Admin Plugins` page.

4. screenshot-4.gif adding the shortcode `[si-contact-form form='1']` in a Page.


== Frequently Asked Questions ==

= I just installed this and do not get any email from it, what could be wrong? =

1. Use the E-mail test feature in options, if you are not receiving mail, try it. It will display troubleshooting information.
 
2. Look for a warning message on the Options page for when the web host has mail() function disabled.

3. Make sure you have the correct "E-mail To:" set in options. If that is correct, then this setting in the contact form options might help you....
"E-mail From:" ... Normally you should leave this blank because the email will be from the sender. If your contact form does not send any email, then set "E-mail To:" and "E-mail From:" to an email address on the SAME domain as your web site. This fix works for web hosts that do not allow PHP to send email unless the email address is on the same web domain. They do this to help prevent spam.

4. Here is another option for you:
Get a free gmail account.
Install the plugin called [WP Mail SMTP](http://wordpress.org/extend/plugins/wp-mail-smtp/),  then set it to use gmail SMTP for mail.
Set these settings for "WP Mail SMTP":
Mailer: SMTP, 
SMTP Host: smtp.gmail.com, 
SMTP Port: 465, 
Encryption: SSL, 
Authentication: Yes, 
Username: your full gmail address, 
Password: your mail password.

Now use gmail to check for your contact form mail, or set gmail to forward the mail to your other address.

= I need more than 4 contact forms, how do I increase the number of forms available? =

On the plugin settings page, change "Number of available Multi-forms", then click Update Options.
For best performance, only change the number to the amount you actually need.

= I need more than 8 extra form fields, how do I increase the number available? =

On the plugin settings page, click Advanced Options, change "Number of available extra fields", then click Update Options.
For best performance, only change the number to the amount you actually need.

= What is "ERROR: Misconfigured E-mail address in options.", what could be wrong? =
First, make sure you have a valid "E-mail To:" set in options. This plugin uses an email validation check to make sure the email address has proper syntax and that a valid DNS record exists for the email domain name. If you have this error and you are sure your email address is correct, maybe your server is having trouble with the DNS check. I added a feature to the options panel to disable the DNS check during email validation. You may have to uncheck this option: "Enable checking DNS records for the domain name when checking for a valid E-mail address." Maybe the error will go away now.

= Why do I get "ERROR: Could not read CAPTCHA cookie."? =

Check your web browser settings and make sure you are not blocking cookies for your blog domain. Cookies have to be enabled in your web browser and not blocked for the blog web domain.

If you get this error, your browser is blocking cookies or you have another plugin that is conflicting (in that case I would like to help you further to determine which one). I can tell you that the plugin called "Shopp" is not compatible because it handles sessions differently causing the "ERROR: Could not read CAPTCHA cookie. Make sure you have cookies enabled".

There is a Cookie Test that can be used to test if your browser is accepting cookies from your site:
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/test/index.php`

= Does this contact form use Akismet spam protection? =
Yes, it checks the form input with Akismet, but only if Akismet plugin is also installed and activated. (Akismet is not required, it will just skip the check)

= Can it send mail using SMTP? =
Yes, when you also have this plugin installed: 
[WP Mail SMTP](http://wordpress.org/extend/plugins/wp-mail-smtp/)

= My host says a fifth parameter -f should be added to the mail function. This will set the name of the from email address. =

Your web host is being unusually restrictive. 
I am using the built in mail function of wordpress, it cannot use the 5th parameter. I will not be able to add that.
I bet you do not even get any mail from wordpress itself?  ... and most PHP programs you might install would not send any mail. 

Here is another option for you:
Get a free gmail account.
Install the plugin called [WP Mail SMTP](http://wordpress.org/extend/plugins/wp-mail-smtp/),  then set it to use gmail SMTP for mail.
Set these settings for "WP Mail SMTP":
Mailer: SMTP, 
SMTP Host: smtp.gmail.com, 
SMTP Port: 465, 
Encryption: SSL, 
Authentication: Yes, 
Username: your full gmail address, 
Password: your mail password.

Now use gmail to check for your contact form mail, or set gmail to forward the mail to your other address.


= Do I have to also install the plugin "SI CAPTCHA Anti-Spam" for the CAPTCHA to work? =

No, this plugin includes the CAPTCHA feature code for this contact form.
The "SI CAPTCHA Anti-Spam" plugin is a separate plugin for comment and registration forms spam protection.

= I use the plugin "SI CAPTCHA Anti-Spam" for my comment and registration forms, is it still needed? =

Yes, if you want protection for the comment and registration forms, the plugin "SI CAPTCHA Anti-Spam" should be installed. 
The two plugins have the same CAPTCHA library but are totally separate.

= Does this work on WPMU or BuddyPress? =
Yes, If you use WPMU or BuddyPress you can have multiple blogs with individual contact forms on each one. On WPMU you would install it in `plugins`, not `mu-plugins`. Then each blog owner can have his own settings.


= Troubleshooting if the CAPTCHA image itself is not being shown: =

By default, the admin will not see the CAPTCHA. If you click "log out", go look and it will be there.

If the image is broken and you have the CAPTCHA entry box:
This can happen if a server has too low a default permission level on new folders.
Check that the permission on all the captcha-secureimage folders are set to permission: 755

all these folders need to be 755:
- si-contact-form
  - languages
  - date
  - captcha-secureimage
     - audio
     - backgrounds
     - gdfonts
     - images
     - list
     - test
     - ttffonts
     - words

Here is a [tutorial about file permissions](http://www.stadtaus.com/en/tutorials/chmod-ftp-file-permissions.php)

This script can be used to test if your PHP installation will support the CAPTCHA:
Open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/test/index.php`
This link can be found on the `Contact Form Options` page.

= How can I add the contact form to a template manually rather than use shortcode in a page? =

Use this code: `<?php if ( isset($si_contact_form) ) echo $si_contact_form->si_contact_form_short_code( array( 'form' => '1' ) ); ?>`
                         

= Is this plugin available in other languages? =

Yes. To use a translated version, you need to obtain or make the language file for it.
At this point it would be useful to read [Installing WordPress in Your Language](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") from the Codex. You will need an .mo file for this plugin that corresponds with the "WPLANG" setting in your wp-config.php file. Translations are listed below -- if a translation for your language is available, all you need to do is place it in the `/wp-content/plugins/si-contact-form/languages` directory of your WordPress installation. If one is not available, and you also speak good English, please consider doing a translation yourself (see the next question).

The following translations are included in the download zip file:

* Albanian (sq_AL) - Translated by [Romeo Shuka](http://www.romeolab.com)
* Bulgarian (bg_BG) - Translated by [Dimitar Atanasov](http://chereshka.net)
* Chinese (zh_CN) - Translated by [Awu](http://www.awuit.cn/) 
* Danish (da_DK) - Translated by [Thomas J. Langer](http://www.ohyeah-webdesign.dk)
* Finnish (fi) - Translated by [Mikko Vahatalo](http://www.guimikko.com/) 
* French (fr_FR) - Translated by [Pierre Sudarovich](http://pierre.sudarovich.free.fr/)
* German (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)
* Greek (el) - Translated by [Ioannis](http://www.jbaron.gr/)
* Hebrew, Israel (he_IL) - Translated by [Asaf Chertkoff FreeAllWeb GUILD](http://web.freeall.org) 
* Hungarian (hu_HU) - Translated by [Jozsef Burgyan](http://dmgmedia.hu)
* Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
* Polish (pl_PL) - Translated by [Pawel Mezyk]
* Portuguese (pt_PT) - Translated by [AJBFerreira Blog](http://pws.op351.net/)
* Portuguese Brazil (pt_BR) - Translated by [Rui Alao]
* Russian (ru_RU) - Translated by [Bezraznizi](http://www.sprestij.ru/)
* Spanish (es_ES) - Translated by [Valentin Yonte Rodriguez](http://www.activosenred.com/)
* Swedish (sv_SE) - Translated by [Daniel Persson](http://walktheline.boplatsen.se/)
* Traditional Chinese, Taiwan (zh_TW) - Translated by [Cjh]
* Turkish (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)
* Ukrainian (uk_UA) - Translated by [Wordpress.Ua](http://wordpress.ua/)
* More are needed... Please help translate.

= Can I provide a translation? =

Of course! It will be very gratefully received. Use PoEdit, it makes translation easy. Please read [Translating WordPress](http://codex.wordpress.org/Translating_WordPress "Translating WordPress") first for background information on translating. Then obtain the latest [.pot file](http://svn.wp-plugins.org/si-contact-form/trunk/languages/si-contact-form.pot ".pot file") and translate it.
* There are some strings with a space in front or end -- please make sure you remember the space!
* When you have a translation ready, please send the .po and .mo files to wp-translation at 642weather dot com.
* If you have any questions, feel free to email me also. Thanks!

= Is it possible to merge the translation files I sent to you with the ones of the newest version? =

If you use PoEdit to translate, it is easy to translate for a new version. You can open your current .po file, then select from the PoEdit menu: "Catalog" > "Update from POT file". Now all you have to change are the new language strings.

= This contact form sends E-mail with UTF-8 character encoding for US and International character support. =

English-language users will experience little to no impact. Any non-English questions or messages submitted will have unicode character encoding so that when you receive the e-mail, the language will still be viewable.

If you receive an email with international characters and the characters look garbled with symbols and strange characters, your e-mail program may need to be set as follows: 

How to set incoming messages character encoding to Unicode(UTF-8) in various mail clients:

Evolution:
View > Character Encoding > Unicode

Outlook Express 6, Windows Mail:
Please check "Tools->Options->Read->International Settings". Un-check "Use default encoding format for all incoming messages" 
Now select "View->Encoding", select "Unicode(UTF-8)"

Mozilla Thunderbird:
Click on Inbox.
Select "View->Character Encoding", select "Unicode(UTF-8)"

Gmail:
No setting necessary, it just works.

== Changelog ==

- Single checkbox can have a comma in the label(as long is there is no semicolon because then it becomes a multi-checkbox).

= 2.6.4 =
- (11 Jun 2010) - Added ability to set both a name and email on the "E-mail From (optional):" field .You can enter just an email: user1@example.com
Or enter name and email: webmaster,user1@example.com 
- Fixed missing shortcode example on admin page.
- Fixed so subject prefix can be blank.
- Added more field indicator options in Advanced Options - Fields
- Added Finnish (fi) - Translated by [Mikko Vahatalo](http://www.guimikko.com/) 
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 2.6.3 =
- (30 May 2010) - Added setting to switch from wordpress mail function to PHP mail function. This setting can resolve some rare mail delivery problems.
- Fixed so checkboxes can have default selected.
- Fixed HTML5 compatible(with CAPTCHA flash audio off).
- Fixed required indicator now has proper class `<span class="required">*</span>`. 
- Fixed syntax bug on extra 'date' fields.
- Fixed for Simple Facebook Connect compatibility (fixes broken CAPTCHA caused by SFC Like and Share plugins).

= 2.6.2 =
- (21 May 2010) - Fixed major bug: All text and textarea extra fields were missing from email. Sorry for the inconvenience. There are so many who use it,  I updated it right away.

= 2.6.1 =
- (19 May 2010) - Fixed bug: all checkboxes appeared selected in the email. 
- Fixed to allow HTML in extra field labels.

= 2.6 =
- (19 May 2010) - Fix for XHTML Strict compliance.
- Improved CAPTCHA CSS code (better alignment captcha, refresh, and audio images).
- Added advanced options for date format on extra 'date' fields (mm/dd/yyyy, dd/mm/yyyy).
- Added advanced options to set checkboxes with children (Pizza Toppings:,olives;mushrooms;cheese;ham;tomatoes).
- Added advanced options to set a default selected item for select and radio fields.
- Added advanced options to make name, email, subject, or message fields (not_available, not_required, or required).
this feature can be used to make an anonymous comment form. Also can be used to disable name and email to make them reordered when using extra fields.
- Updated Spanish (es_ES) - Translated by Sergio Torres.

= 2.5.6 =
- (15 May 2010) - Made WP3 Compatible.

= 2.5.5 =
- (07 May 2010) - Fixed to be compatible with *www.com domain name.
- Added extra field type for "date", this new field can be used for a hotel registration form and uses a popup "Epoch DHTML Calendar" 1.06 by Nick Baicoianu from meanfreepath.com

= 2.5.4 =
- (01 May 2010) - Fixed small issue with "enable hidden message" option.
- Fixed small issue with "email from" option.
- Improved CAPTCHA testpage.

= 2.5.3 =
- (23 Apr 2010) - Added Dutch (nl_NL) - Translated by [Mark Visser]
- Added Swedish (sv_SE) - Translated by [Daniel Persson](http://walktheline.boplatsen.se/)


= 2.5.2 = 
- (16 Apr 2010) - Added Hungarian (hu_HU) - Translated by [Jozsef Burgyan](http://dmgmedia.hu)
- Updated Polish (pl_PL) - Translated by [Pawel Mezyk]

= 2.5.1 =
- (09 Apr 2010) - Fixed bug in reset styles feature.
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 2.5.0 =
- (05 Apr 2010) - Added setting to add optional drop down list for email subject.
- Added setting to make the CAPTCHA image smaller.
- Added settings to increase number of forms and have more extra fields (editing code is no longer needed).
- Fixed so multiple forms can be on the same page. 
- Added editable text label setting for (* denotes required field).
- Added more style settings in Advanced Options. You can use inline css, or add a class property to be used by your own stylsheet.
Acceptable Examples:
text-align:left; color:#000000; background-color:#CCCCCC;
style="text-align:left; color:#000000; background-color:#CCCCCC;"
class="input"
- Split code into 4 smaller files for better performance.
- Other bug fixes.

= 2.0.2 =
- (16 Mar 2010) - Added radio and select configuration error checking. Fix display of radio input fields to be on separate lines.
- Fixed multiple BCC feature, it was only accepting one BCC.
- Updated German (de_DE) and Bulgarian (bg_BG)

= 2.0.1 =
- (06 Feb 2010) - Fix Invalid Input error when the word "donkey" is in the input string.
- Added Polish (pl_PL) - Translated by [Pawel Mezyk]
- Fixed Greek language file name.

= 2.0 =
- (26 Jan 2010) - Added required field indicators (can be disabled in settings if you do not like them).
- Added setting to adjust redirect delay seconds(range of 1-5 recommended).
- Added setting to hide message entry, now you can hide subject and message fields for use as a newsletter signup.
- Added selectable extra field types: text, textarea, checkbox, radio, select. Note: When using select or radio field types, first enter the label and a comma. Next include the options separating with a semicolon like this example: Color:,Red;Green;Blue 

= 1.9.6 =
- (31 Dec 2009) - New setting for a few people who had problems with the text transparency "Disable CAPTCHA transparent text (only if captcha text is missing on the image, try this)".
- Added Hebrew, Israel (he_IL) - Translated by [Asaf Chertkoff FreeAllWeb GUILD](http://web.freeall.org) 

= 1.9.5 =
- (04 Dec 2009) - Fix slashes issue on some servers.
- More improvements for CAPTCHA images and fonts.

= 1.9.4 =
- (30 Nov 2009) - Fix blank CAPTCHA text issue some users were having.
- Added CAPTCHA difficulty level setting on the settings page (Low, Medium, Or High).
- Added Portuguese (pt_PT) - Translated by [AJBFerreira Blog](http://pws.op351.net/)

= 1.9.3 =
- (23 Nov 2009) - Fix completely broke CAPTCHA, sorry about that

= 1.9.2 =
- (23 Nov 2009) - Added 5 random CAPTCHA fonts.
- Added feature to increase the number of extra form fields available (see faq if you need it).
- Fixed fail over to GD Fonts on the CAPTCHA when TTF Fonts are not enabled in PHP (it was broken).

= 1.9.1 =
- (21 Nov 2009) - Fixed Flash audio was not working.
- Added Spanish (es_ES) - Translated by [Valentin Yonte Rodriguez](http://www.activosenred.com/)

= 1.9 =
- (20 Nov 2009) - Updated to SecureImage CAPTCHA library version 2.0
- New CAPTCHA features include: increased CAPTCHA difficulty using mathematical distortion, streaming MP3 audio of CAPTCHA code using Flash, random audio distortion, better distortion lines, random backgrounds and more.
- Other minor fixes.

= 1.8.4 =
- (10 Nov 2009) - Added advanced option to edit the CAPTCHA input field size.
- Other minor fixes.

= 1.8.3 =
- (09 Nov 2009) - Fix Submit button spacing.

= 1.8.2 =
- (03 Nov 2009) - Added feature to increase the number of forms available (see faq if you need it).
- Fix for settings not being deleted when plugin is deleted from admin page.
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
- Added Albanian (sq_AL) - Translated by [Romeo Shuka](http://www.romeolab.com)

= 1.8.1 =
- (02 Nov 2009) - Fixed error "Could not read CAPTCHA cookie" on some installs using version 1.8

= 1.8 =
- (02 Nov 2009) - Added Multi-Form feature that allows you to have up to four different forms on your site.
- Added Bulgarian (bg_BG) - Translated by [Dimitar Atanasov](http://chereshka.net/)

= 1.7.7 =
- (30 Oct 2009) - Fixed issue on some sites with blank css fields that caused image misalignment.
- Added advanced option to edit the CSS style for border on the contact form.

= 1.7.6 =
- (27 Oct 2009) - Added advanced option to edit the CSS style for contact drop down select on the contact form.
- HTML validation fix.

= 1.7.5 =
- (21 Oct 2009) - Added Chinese (zh_CN) - Translated by [Awu](http://www.awuit.cn/) 
- Added Greek (el) - Translated by [Ioannis](http://www.jbaron.gr/)

= 1.7.4 =
- (03 Oct 2009) - Fixed advanced setting: CSS style for form input fields. Changing background color did not work.
- Added setting to Enable hidden E-mail subject (removes subject field from contact form).  

= 1.7.3 =
- (01 Oct 2009) - Updated links to my other plugins.
- Danish (da_DK) - Updated by [Georg / Team Blogos](http://wordpress.blogos.dk)

= 1.7.2 =
- (30 Sep 2009) - Fixed settings were deleted at deactivation. Settings are now only deleted at uninstall.

= 1.7.1 =
- (29 Sep 2009) - Fix credit link position. 
- Some people wanted to change the error messages for the contact form. Advanced settings fields can be filled in to override the standard included error messages.

= 1.7 =
- (28 Sep 2009) - Added 8 optional extra fields. Some people requested extra contact form fields that could be used for phone number, company name, etc. To enable an extra field from the advanced options, just enter a label. Then check if you want the field to be required or not.

= 1.6.8 =
- (22 Sep 2009) - Fix, some sites reported a image path problem. (I think it is correct now). 

= 1.6.7 =
- (22 Sep 2009) - Fix, some sites reported a path problem with "Blog address" is different domain than "WordPress address".
- Added setting to enable upper case alphabet correction. 
- Added more fields in "advanced options".
- Minor code cleanup.

= 1.6.6 =
- (21 Sep 2009) - Fix "Invalid Input" error on installations where "Blog address" is different domain than "WordPress address".
- More sanity checks on Form DIV Width setting.
- Added ability to use dashes or underscores in shortcode: `[si-contact-form]` or `[si_contact_form]`.

= 1.6.5 =
- (18 Sep 2009) - Added proper nonce protection to options forms. 
- Added option to reset the styles to defaults (incase you tried to adjust them and did not like the results).
- Fixed typo in file name for Portuguese - Brazil language (pt_BR).
- Fixed several language files [BR, FR, NO, DE...] had word "Submit" spelled as "submit".

= 1.6.4 =
- (14 Sep 2009) - Added E-mail test feature in options, if you are not receiving mail, try it. It will display troubleshooting information.
- Added error check for wp_mail send, this is helful to troubleshoot mail delivery. 
- Added a warning message on Options page for when the web host has mail() function disabled.

= 1.6.3 =
- (13 Sep 2009) - Added new advanced options for CSS style of captcha image, audio image, reload image, and submit button.
- Fixed coding for XHTML Strict validation.
- Added Ukrainian language (uk_UA) - Translated by [Wordpress.Ua](http://wordpress.ua/)

= 1.6.2 =
- (11 Sep 2009) - Added new feature in options: "Enable checking DNS records for the domain name when checking for a
valid E-mail address." It looks for any of the following: A record, a CNAME record, or MX record.(enabled by default).
- Updated FAQ

= 1.6.1 =
- (11 Sep 2009) - Fixes error if you are upgrading from prior version: Fatal error: Call to a member function `si_contact_migrate()` on a non-object in si-contact-form.php on line 1461
- If you get this error and cannot access your WP site: the manual fix is to delete the `si-contact-form.php` file from the `plugins/si-contact-form/` directory, your site will start working again. Then you can install this new version.  
See this [forum post](http://wordpress.org/support/topic/309925)

= 1.6 =
- (10 Sep 2009) - Auto form fill is automatically skipped for any user with administrator role.
- New option: Auto form fill can be enabled/disabled in advanced options(enabled by default).
- Plugin options are now stored in a single database row instead of many. (it will auto migrate/cleanup old database rows).
- Language files are now stored in the `si-contact-form/languages` folder.
- Options are deleted when this plugin is deleted.
- Added help links on options page.
- Added Portuguese Brazil (pt_BR) - Translated by [Rui Alao]
- Updated Russian (ru_RU) - Translated by [Bezraznizi](http://www.sprestij.ru/)
- Updated Turkish (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)

= 1.5 =
- (9 Sep 2009) - New feature: I added an "advanced options" section to the options page. Some people wanted to change the text labels for the contact form.
These advanced options fields can be filled in to override the standard included field titles.
- Other minor code changes.

= 1.4.4 =
- (08 Sep 2009) - Fixed possible error: "mail could not be sent because host may have disabled email function()"

= 1.4.3 =
- (08 Sep 2009) - Fixed redirect/logout problem on admin menu reported by a user.
- Removed blog name from top of email message body.

= 1.4.2 =
- (07 Sep 2009) - Added configurable email subject prefix in options.
- Added configurable border width in options.
- Auto form fill is now disabled for admin, but still works for other logged in users.
- Other minor fixes.

= 1.4.1 =
- (06 Sep 2009) - Added feature: Auto form fill email address and name (username) on the contact form for logged in users.
- Added feature: prints "From a WordPress user: `<username>`" on email footer for logged in users.
- Added feature: Date/Time timestamp on email footer (uses Date/Time format from general options setting).
- Added Russian Language (ru_RU) - Translated by [Bezraznizi](http://www.sprestij.ru/)

= 1.4 =
- (06 Sep 2009) Now uses wp_mail function so that users who use the SMTP mail plugins will be supported.
- Now sends email encoded in the character encoding you write your blog in, (UTF-8 is recommended) see `Settings`, `Reading` admin options page.
- New feature: Now you can have multiple E-mails per contact, this is called a CC(Carbon Copy). If you need to add more than one contact, see the example: click "help" on the `Contact Form Options Page`. 
- Fixed error "Bad parameters to mail() function" reported by a couple users.
- Fixed error "Call to undefined function `mb_detect_encoding()`" reported by one user.
- Many hours were put into this free plugin. Please donate, even small amounts like $2.99 are welcome.

= 1.3 =
- (04 Sep 2009) Added Feature: This contact form sends E-mail with UTF-8 character encoding for US and International character support.(fee FAQ)
- Added Danish Language (da_DK) - Translated by [Thomas J. Langer](http://www.ohyeah-webdesign.dk)
- fixed an issue with the "Welcome introduction" field translation not translating.

= 1.2.5 =
- (02 Sep 2009) Added Norwegian language (nb_NO) - Translated by [Roger Sylte](http://roger.inro.net/)

= 1.2.4 =
- (02 Sep 2009) Added German Language (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)

= 1.2.3 =
- (01 Sep 2009) Fixed email validation on some windows servers
- Added Traditional Chinese, Taiwan Language (zh_TW) - Translated by [Cjh]
- Added French language (fr_FR) - Translated by [Pierre Sudarovich](http://pierre.sudarovich.free.fr/)

= 1.2.2 =
- (31 Aug 2009) Added Italian language (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 1.2.1 =
- (31 Aug 2009) Added more diagnostic test scripts: a Cookie Test, Captcha test, and a PHP Requirements Test.
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/test/index.php`

= 1.2 =
- (31 Aug 2009) Translations were not working

= 1.1.7 =
- (31 Aug 2009) Cookie error improvements.

= 1.1.6 =
- (30 Aug 2009) Added a Cookie Test to help diagnose if a web browser has cookies disabled.
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/test/index.php`

= 1.1.5 = 
- (30 Aug 2009) Improved Akismet function (checks for `wordpress_api_key`)
- Hide CAPTCHA for registered users is now disabled by default(configurable in Options)

= 1.1.4 =
- (29 Aug 2009) Improved `ctf_validate_email` function and fixed a bug that invalidated email address with upper case

= 1.1.3 =
- (29 Aug 2009) Added this script to be used to test if your PHP installation will support the CAPTCHA:
Open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/secureimage_test.php`

= 1.1.2 =
- (28 Aug 2009) Updated Turkish language (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)

= 1.1.1 =
- (28 Aug 2009) Added Turkish language (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)
- CAPTCHA fix - Added Automatic fail over from TTF Fonts to GD Fonts if the PHP installation is configured without "--with-ttf".
  Some users were reporting there was no error indicating this TTF Fonts not supported condition and the captcha was not working.

= 1.1 =
- (28 Aug 2009) Added multi "email to" contact feature. Add as many contacts as you need in Options. The drop down list on the contact form will be made automatically.

= 1.0.3 =
- (28 Aug 2009) fix options permission bug introduced by last update, sorry

= 1.0.2 =
- (27 Aug 2009) Added Akismet spam protection. Checks the form input with Akismet, but only if Akismet plugin is also installed.
- added settings link to the plugin action links.

= 1.0.1 =
- (26 Aug 2009) fixed deprecated ereg_replace and eregi functions for PHP 5.3+ compatibility when error warnings are on

= 1.0 =
- (26 Aug 2009) Initial Release



