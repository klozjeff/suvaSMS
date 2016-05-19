-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2016 at 02:30 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bulksms`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE IF NOT EXISTS `campaign` (
  `campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_uid` char(13) NOT NULL,
  `customer_id` varchar(110) NOT NULL,
  `list_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `type` char(15) NOT NULL DEFAULT 'regular',
  `name` varchar(255) NOT NULL,
  `temp_id` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `send_at` datetime DEFAULT NULL,
  `delivery_logs_archived` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` char(15) NOT NULL DEFAULT 'draft',
  `date_added` varchar(250) NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`campaign_id`),
  UNIQUE KEY `campaign_uid_UNIQUE` (`campaign_uid`),
  KEY `fk_campaign_list1_idx` (`list_id`),
  KEY `fk_campaign_customer1_idx` (`customer_id`),
  KEY `fk_campaign_campaign_group1_idx` (`group_id`),
  KEY `type` (`type`),
  KEY `status_delivery_logs_archived_campaign_id` (`status`,`delivery_logs_archived`,`campaign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` (`campaign_id`, `campaign_uid`, `customer_id`, `list_id`, `group_id`, `type`, `name`, `temp_id`, `subject`, `send_at`, `delivery_logs_archived`, `status`, `date_added`, `last_updated`) VALUES
(1, '25507', 'XK5678', 2, 1, 'regular', 'Untitled', '1', NULL, NULL, 'no', 'Ready', '1458476419', '0000-00-00 00:00:00'),
(2, '33518', 'XK5678', 1, 2, 'regular', 'Mid Month Campaigns', '1', NULL, NULL, 'no', 'Ready', '1458478158', '0000-00-00 00:00:00'),
(5, '11231', 'XK5678', 2, 1, 'regular', 'Mid Month Campaigns2', '1', NULL, NULL, 'no', 'Ready', '1458479533', '0000-00-00 00:00:00'),
(6, '17014', 'XK5678', 2, 2, 'regular', 'Easter Greeting', '2', NULL, NULL, 'no', 'Ready', '1458763225', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_group`
--

CREATE TABLE IF NOT EXISTS `campaign_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_uid` char(13) NOT NULL,
  `customer_id` varchar(110) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(2500) DEFAULT NULL,
  `date_added` varchar(250) NOT NULL,
  `last_updated` varchar(250) NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_uid` (`group_uid`),
  KEY `fk_campaign_group_customer1_idx` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `campaign_group`
--

INSERT INTO `campaign_group` (`group_id`, `group_uid`, `customer_id`, `name`, `description`, `date_added`, `last_updated`) VALUES
(1, '53430', 'XK5678', 'Debt Recovery', 'Debt Alert', '1458419932', '0000-00-00 00:00:00'),
(2, '7065', 'XK5678', 'Cold Sales', ' Cold calling +Sms\r\n', '1458474397', '');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `last_message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user1`, `user2`, `time`, `last_activity`, `last_message`) VALUES
(1, 1, 1, 1458405564, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `fields_customized`
--

CREATE TABLE IF NOT EXISTS `fields_customized` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `custom_field1` varchar(100) NOT NULL,
  `custom_field2` varchar(100) NOT NULL,
  `custom_field3` varchar(100) NOT NULL,
  `custom_field4` varchar(100) NOT NULL,
  `custom_field5` varchar(100) NOT NULL,
  `status` char(50) NOT NULL DEFAULT 'Active',
  `date_added` varchar(100) NOT NULL,
  `last_updated` varchar(100) NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `fields_customized`
--

INSERT INTO `fields_customized` (`field_id`, `list_id`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `custom_field5`, `status`, `date_added`, `last_updated`) VALUES
(1, 3, 'Initial Loan', 'Loan Paid', 'Balance', 'Due Date', 'Category', 'Active', '1458854475', ''),
(2, 2, 'Initial Loan', 'Loan Paid', 'Balance', 'Due Date', 'Category', 'Active', '1458855421', '');

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE IF NOT EXISTS `friend_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `accepted` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gifts`
--

CREATE TABLE IF NOT EXISTS `gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `path` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE IF NOT EXISTS `list` (
  `list_id` int(11) NOT NULL AUTO_INCREMENT,
  `list_uid` char(13) NOT NULL,
  `customer_id` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `source` enum('Web','API','Import') NOT NULL DEFAULT 'Web',
  `visibility` char(15) NOT NULL DEFAULT 'public',
  `status` char(15) NOT NULL DEFAULT 'active',
  `date_added` varchar(250) NOT NULL,
  `last_updated` varchar(250) NOT NULL,
  PRIMARY KEY (`list_id`),
  UNIQUE KEY `unique_id_UNIQUE` (`list_uid`),
  KEY `fk_list_customer1_idx` (`customer_id`),
  KEY `status_visibility` (`status`,`visibility`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `list`
--

INSERT INTO `list` (`list_id`, `list_uid`, `customer_id`, `name`, `description`, `source`, `visibility`, `status`, `date_added`, `last_updated`) VALUES
(1, '53183', 'XK5678', 'Specific List', ' dfdfdfdff\r\n', 'Web', 'public', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '55313', 'XK5678', 'Broad List', 'Testing', 'Web', 'public', 'active', '1458421207', ''),
(3, '82873', 'XK5678', 'Debt Recovery', 'Balance to Recover \r\n', 'Import', 'public', 'active', '1458474231', '');

-- --------------------------------------------------------

--
-- Table structure for table `list_leads`
--

CREATE TABLE IF NOT EXISTS `list_leads` (
  `lead_id` int(11) NOT NULL AUTO_INCREMENT,
  `lead_uid` char(13) NOT NULL,
  `list_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `second_name` varchar(100) NOT NULL,
  `phone_no` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `custom_field1` varchar(100) NOT NULL,
  `custom_field2` varchar(100) NOT NULL,
  `custom_field3` varchar(100) NOT NULL,
  `custom_field4` varchar(100) NOT NULL,
  `custom_field5` varchar(100) NOT NULL,
  `status` char(50) NOT NULL DEFAULT 'Active',
  `date_added` varchar(100) NOT NULL,
  `last_updated` varchar(100) NOT NULL,
  PRIMARY KEY (`lead_id`),
  UNIQUE KEY `unique_id_UNIQUE` (`lead_uid`),
  KEY `fk_list_subscriber_list1_idx` (`list_id`),
  KEY `list_email` (`list_id`,`email`),
  KEY `status_last_updated` (`status`,`last_updated`),
  KEY `list_id_status` (`list_id`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `list_leads`
--

INSERT INTO `list_leads` (`lead_id`, `lead_uid`, `list_id`, `first_name`, `second_name`, `phone_no`, `email`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `custom_field5`, `status`, `date_added`, `last_updated`) VALUES
(1, '87722', 2, 'Geoffrey', 'Sammy', '254711646779', 'klozjeff@gmail.com', '20005', '25000', '20005', '20005', '20005', 'Active', '1458459515', ''),
(9, '46771', 2, 'Duncan', 'Gitonga', '254710513413', 'dungates.gates198@gmail.com', '2000', '2500', '2000', '2000', '2000', 'Active', '1458632584', ''),
(16, '80612', 3, '', '', '', '', '', '', '', '', '', 'Active', '1458856233', '');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `path` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `user_id`, `path`, `time`) VALUES
(1, 20, 'b2fe91aaf7ad824431b5be8e3b4913fcda2110065c30125f3a76deda2528166610985983_846035978787438_675843452_n.jpg', 1443815076);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `convers_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sender_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver_id` int(11) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `content` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `is_read` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_no` varchar(250) NOT NULL,
  `customer_id` varchar(400) NOT NULL,
  `order_type` varchar(100) NOT NULL,
  `order_topic` varchar(100) NOT NULL,
  `order_pages` varchar(100) NOT NULL,
  `deadline_date` varchar(100) NOT NULL,
  `deadline_time` varchar(100) NOT NULL,
  `order_deadline` varchar(250) NOT NULL,
  `order_discipline` varchar(100) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `academic_level` varchar(100) NOT NULL,
  `citation_style` varchar(100) NOT NULL,
  `order_details` varchar(2500) NOT NULL,
  `paper_details` text NOT NULL,
  `discount_code` varchar(2500) NOT NULL,
  `powerpoint` varchar(2500) NOT NULL,
  `writer_preference` varchar(2500) NOT NULL,
  `status` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `SavedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_files`
--

CREATE TABLE IF NOT EXISTS `order_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(250) NOT NULL,
  `order_no` varchar(250) NOT NULL,
  `file_name` varchar(400) NOT NULL,
  `file_size` varchar(100) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `originator` varchar(250) NOT NULL,
  `SavedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_price`
--

CREATE TABLE IF NOT EXISTS `order_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(250) NOT NULL,
  `order_no` varchar(250) NOT NULL,
  `total_cost` varchar(400) NOT NULL,
  `paid` varchar(100) NOT NULL,
  `balance` varchar(100) NOT NULL,
  `order_discount` varchar(2500) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `SavedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` text NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_title`, `content`) VALUES
(1, 'Privacy Policy', '<h1 class="page-title">Privacy Policy</h1>\r\n<section class="section">\r\n<div class="content ptb8">\r\n<div class="legal-doc center">\r\n<div class="col span2of3 mid-span1of1 normalize align-left">\r\n<p>Thanks for using MailChimp. This policy explains the what, how, and why of the information we collect when you use our Services. It also explains the specific ways we use and disclose that information. We take your privacy extremely seriously, and we never sell lists or email addresses.</p>\r\n<h1 id="section-the-basics">THE BASICS</h1>\r\n<h2>1. Definitions</h2>\r\n<p>We&rsquo;ll start by getting a few definitions out of the way that should help you understand this policy. When we say &ldquo;we,&rdquo; &ldquo;us,&rdquo; and &ldquo;MailChimp,&rdquo; we&rsquo;re referring to The Rocket Science Group, LLC d/b/a MailChimp, a State of Georgia limited liability company. We provide online platforms that you may use to create, send, and manage emails (the &ldquo;Services&rdquo;). When we say &ldquo;you&rdquo; or &ldquo;Member,&rdquo; we&rsquo;re referring to the person or entity that&rsquo;s registered with us to use the Services. A &ldquo;Subscriber&rdquo; is a person you contact through our Services. A &ldquo;Distribution List&rdquo; is a list of email addresses that one of our Members has sent, or intends to send, emails to, and all information relating to those email addresses. We may combine information about you or your Subscribers with information provided by other Members or third parties to create &rdquo;Aggregate Information,&rdquo; which may include, but isn&rsquo;t limited to, names, email addresses, demographic information, IP addresses and location.</p>\r\n<p>We offer the Services on our websites <a href="http://www.mailchimp.com/">http://www.mailchimp.com</a>, <a href="http://www.tinyletter.com">http://www.tinyletter.com</a>, and <a href="http://www.mandrill.com">http://www.mandrill.com</a> (each a &ldquo;Website&rdquo; and together the &ldquo;Websites&rdquo;). While providing the Services, we may collect Personal Information, which means information about a Member or Subscriber.</p>\r\n<div id="d95032c6-7dd5-4200-84b1-cf6aaf10347c"><a title="TRUSTe European Safe Harbor certification" href="http://privacy.truste.com/privacy-seal/The-Rocket-Science-Group,-LLC/validation?rid=11c02a59-2371-4802-97d5-395c0bb180b4" target="_blank"><img style="border: none;" src="http://privacy-policy.truste.com/privacy-seal/The-Rocket-Science-Group,-LLC/seal?rid=11c02a59-2371-4802-97d5-395c0bb180b4" alt="TRUSTe European Safe Harbor certification" /></a></div>\r\n<p>The TRUSTe program covers our Websites, <a href="http://www.mailchimp.com">http://www.mailchimp.com</a>, <a href="http://www.tinyletter.com">http://www.tinyletter.com</a>, <a href="http://www.mandrill.com">http://www.mandrill.com</a>, as well as the MailChimp online and mobile app. To learn more about our relationship with TRUSTe, please click on the TRUSTe seal to see our validation page. You may also <a href="https://feedback-form.truste.com/watchdog/request">contact TRUSTe</a> directly.</p>\r\n<h2>2. Changes</h2>\r\n<p>If there are any material changes to this Privacy Policy, we&rsquo;ll post them on the Website and send them to the last email address you gave us. Any changes will be effective as of the date we post them on the Website or send the email (whichever date is later). You may object to any changes within 20 days after they&rsquo;re posted on our Website or delivered to you, in which case none of the proposed changes will be effective with respect to information that we&rsquo;ve already collected from you, but will apply only to information we collect in the future. We won&rsquo;t treat information of any open account differently from any other open account. If you object to changes in our Privacy Policy, we&rsquo;ll have to terminate your account.</p>\r\n<h2>3. Scope</h2>\r\n<p>This Privacy Policy is effective with respect to any data that we&rsquo;ve collected, or collect, about and/or from you, according to our <a href="http://mailchimp.com/legal/terms">Terms of Use</a>.</p>\r\n<h2>4. Questions &amp; Concerns</h2>\r\n<p>If you have any questions or comments, or if you want to update, delete, or change any Personal Information we hold, or you have a concern about the way in which we have handled any privacy matter please use our <a href="http://mailchimp.com/contact">contact form</a> to get in touch. You may also contact us by postal mail or email at:</p>\r\n<p>MailChimp<br /> Attn. Privacy Officer<br /> <a href="mailto:privacy@mailchimp.com">privacy@mailchimp.com</a><br /> 675 Ponce de Leon Ave NE, Suite 5000<br /> Atlanta, GA 30308</p>\r\n<h1 id="section-your-information">YOUR INFORMATION</h1>\r\n<h2>5. Information We Collect</h2>\r\n<ol class="list markers">\r\n<li>\r\n<p><strong>Information You Provide to Us:</strong> When you use the Services, consult with our customer service team, send us an email, post on our blog, or communicate with us in any way, you&rsquo;re giving us information that we collect. That information may include your and your Subscribers&rsquo; IP address, name, physical address, email address, phone number, credit card information, operating system, as well as details like gender, occupation, location, birth date, purchase history, and other demographic information. By giving us this information, you consent to your information being collected, used, disclosed, transferred to the United States and stored by us, as described in our <a href="http://mailchimp.com/legal/terms">Terms of Use</a> and Privacy Policy.</p>\r\n</li>\r\n<li>\r\n<p><strong>List and Email Information</strong>: When you add an email Distribution List or create an email with the Services, we have and may access the data on your list and the information in your email.</p>\r\n</li>\r\n<li>\r\n<p><strong>Information from your Use of the Service</strong>: We may get information about how and when you use the Services, store it in log files associated with your account, and link it to other information we collect about you. This information may include, for example, your IP address, time, date, browser used, and actions you&rsquo;ve taken within the application.</p>\r\n</li>\r\n<li>\r\n<p><strong>Cookies and Tracking</strong>: When you use MailChimp, we may store &ldquo;cookies,&rdquo; &ldquo;tags,&rdquo; or &ldquo;scripts,&rdquo; which are strings of code, on your computer. We and our analytics Service Providers (like <a href="http://www.google.com/policies/privacy/partners/">Google</a>) use those cookies to collect information about your visit and your use of our Website or Services. You may turn off cookies that have been placed on your computer by following the instructions on your browser, but if you block cookies, it may be more difficult (and maybe even impossible) to use some aspects of the Services.</p>\r\n</li>\r\n<li>\r\n<p><strong>Web Beacons</strong>: When we send emails to Members, we may track behavior such as who opened the emails and who clicked the links. We do that to measure the performance of our email campaigns and to improve our features for specific segments of Members. To do this, we include single pixel gifs, also called web beacons, in emails we send. Web beacons allow us to collect information about when you open the email, your IP address, your browser or email client type, and other similar details. We also include Web Beacons in the emails we deliver for you. We use the data from those Web Beacons to create the reports about how your email campaign performed and what actions your Subscribers took. Reports are also available to us when we send email to you, so we may collect and review that information.</p>\r\n</li>\r\n<li>\r\n<p><strong>Information from Other Sources</strong>: We may get more information about you or your Subscribers, like name, age, and use of social media websites, by searching the internet or querying third parties (we&rsquo;ll refer to that information as Supplemental Information). We use Supplemental Information to develop features like Social Profiles, a tool that helps you learn about your Subscribers and send them more relevant content.</p>\r\n</li>\r\n<li>\r\n<p><strong>Information from the use of our Mobile Apps</strong>: When you use our mobile apps, we may collect information about the type of device and operating system you use. We may ask you if you want to receive push notifications about activity in your account. If you opt in to these notifications and no longer want to receive them, you may turn them off through your operating system. We don&rsquo;t access or track any location-based information from your mobile device unless you&rsquo;ve given us permission. We may use mobile analytics software (like <a href="https://fabric.io/privacy">Fabric.io</a>) to help us better understand how people use our application. We may collect information about how often you use the application and other performance data. We don&rsquo;t collect or link that data with any personally identifiable information.</p>\r\n</li>\r\n</ol>\r\n<h2>6. Use and Disclosure of Your Personal Information</h2>\r\n<p>We may use and disclose your Personal Information only as follows:</p>\r\n<ol class="list markers">\r\n<li>\r\n<p><strong>To promote use of our Services.</strong> For example, if you leave your Personal Information when you visit our Website and don&rsquo;t sign up for any of the Services, we may send you an email asking if you want to sign up. And if you use any of our Services and we think you might benefit from using another Service we offer, we may send you an email about it. You can stop receiving our promotional emails by following the unsubscribe instructions included in every email we send.</p>\r\n</li>\r\n<li>\r\n<p><strong>To send you informational and promotional content that you may choose (or &ldquo;opt in&rdquo;) to receive.</strong> You can stop receiving our promotional emails by following the unsubscribe instructions included in every email.</p>\r\n</li>\r\n<li>\r\n<p><strong>To bill and collect money owed to us.</strong> This includes sending you emails, invoices, receipts, notices of delinquency, and alerting you if we need a different credit card number. We use third parties for secure credit card transaction processing, and we send billing information to those third parties to process your orders and credit card payments. To learn more about the steps we take to safeguard that data, see Section 12 below.</p>\r\n</li>\r\n<li>\r\n<p><strong>To send you System Alert Messages.</strong> For example, we may let you know about temporary or permanent changes to our Services, like planned outages, new features, version updates, releases, abuse warnings, and changes to our Privacy Policy.</p>\r\n</li>\r\n<li>\r\n<p><strong>To communicate with you about your account and provide customer support.</strong></p>\r\n</li>\r\n<li>\r\n<p><strong>To enforce compliance with our Terms of Use and applicable law.</strong> This may include developing tools and algorithms that help us prevent violations.</p>\r\n</li>\r\n<li>\r\n<p><strong>To protect the rights and safety of our Members and third parties, as well as our own.</strong></p>\r\n</li>\r\n<li>\r\n<p><strong>To meet legal requirements</strong> like complying with court orders, valid discovery requests, valid subpoenas, and other appropriate legal mechanisms.</p>\r\n</li>\r\n<li>\r\n<p><strong>To provide information to representatives and advisors</strong>, like attorneys and accountants, to help us comply with legal, accounting, or security requirements.</p>\r\n</li>\r\n<li>\r\n<p><strong>To prosecute and defend a court, arbitration, or similar legal proceeding.</strong></p>\r\n</li>\r\n<li>\r\n<p><strong>To provide, support, and improve the Services we offer.</strong> This includes aggregating information from your use of the Services and sharing such Aggregated Information with third parties.</p>\r\n</li>\r\n<li>\r\n<p><strong>To provide suggestions to you.</strong> This includes adding features that compare Members&rsquo; email campaigns, or using data to suggest other publishers your Subscribers may be interested in.</p>\r\n</li>\r\n<li>\r\n<p><strong>To transfer your information</strong> in the case of a sale, merger, consolidation, liquidation, reorganization, or acquisition. In that event, any acquirer will be subject to our obligations under this Privacy Policy, including your rights to access and choice. We&rsquo;ll notify you of the change either by sending you an email or posting a notice on our Website.</p>\r\n</li>\r\n</ol>\r\n<h2>7. Data Collected for and by our Users.</h2>\r\n<p>As you use our Services, you may import into our system personal information you&rsquo;ve collected from your Subscribers. We have no direct relationship with your Subscribers, and you&rsquo;re responsible for making sure you have the appropriate permission for us to collect and process information about those individuals. We may transfer personal information to companies that help us provide our Services (&ldquo;Service Providers.&rdquo;) All Service Providers enter into a contract with us that protects personal data and restricts their use of any personal data in line with this policy. As part of our Services, we may use and incorporate into features information you&rsquo;ve provided or we&rsquo;ve collected about Subscribers as Aggregate Information. We may share this Aggregate Information, including Subscriber email addresses, with third parties in line with the approved uses in Section 6.</p>\r\n<p>If you&rsquo;re a Subscriber and no longer want to be contacted by one of our Members, please unsubscribe directly from that Member&rsquo;s newsletter or contact the Member directly to update or delete your data. If you contact us, we may remove or update your information within a reasonable time and after providing notice to the Member of your request.</p>\r\n<p>We&rsquo;ll retain personal data we process on behalf of our Members for as long as needed to provide services to our Members or to comply with our legal obligations, resolve disputes, prevent abuse, and enforce our agreements.</p>\r\n<h2>8. Public Information and Third Parties</h2>\r\n<ol class="list markers">\r\n<li>\r\n<p>Blog. We have public blogs on our Websites. Any information you include in a comment on our blog may be read, collected, and used by anyone. If your Personal Information appears on our blogs and you want it removed, contact us <a href="http://mailchimp.com/contact">here</a>. If we&rsquo;re unable to remove your information, we&rsquo;ll let you know why.</p>\r\n</li>\r\n<li>\r\n<p>Social Media Platforms and Widgets. Our Websites include social media features, like the Facebook Like button. These features may collect information about your IP address and which page you&rsquo;re visiting on our site, and they may set a cookie to make sure the feature functions properly. Social media features and widgets are either hosted by a third party or hosted directly on our site. We also maintain presences on social media platforms like Facebook, Twitter, and Instagram. Any information, communications, or materials you submit to us via a social media platform is done at your own risk without any expectation of privacy. We cannot control the actions of other users of these platforms or the actions of the platforms themselves. Your interactions with those features and platforms are governed by the privacy policies of the companies that provide them.</p>\r\n</li>\r\n<li>\r\n<p>Links to Third-Party Sites. Our Websites include links to other websites, whose privacy practices may be different from MailChimp&rsquo;s. If you submit Personal Information to any of those sites, your information is governed by their privacy policies. We encourage you to read carefully the privacy policy of any Website you visit.</p>\r\n</li>\r\n<li>\r\n<p>Service Providers. If it&rsquo;s necessary to provide you something you&rsquo;ve requested, like send you a T-shirt or enable a feature like Social Profiles, then we may share your personal information to a Service Provider for that purpose. We&rsquo;ll tell you we&rsquo;re working with a Service Provider whenever reasonably possible, and you may request at any time the name of our Service Providers.</p>\r\n</li>\r\n</ol>\r\n<h2>9. Contest and Sweepstakes</h2>\r\n<p>We may, from time to time, offer surveys, contests, sweepstakes, or other promotions on our Websites or through social media (collectively &ldquo;Our Promotions&rdquo;). Participation in our Promotions is completely voluntary. Information requested for entry may include personal contact information like your name, address, date of birth, phone number, email address, username, and similar details. We use the information you provide to administer Our Promotions. We also may, unless prohibited by the Promotion&rsquo;s rules or law, use the information provided to communicate with you, or other people you select, about our Services. We may share this information with our affiliates and other organizations or Service Providers in line with this policy and the rules posted for the Promotion.</p>\r\n<h2>10. Content of Email Campaigns</h2>\r\n<p>When you send an email marketing campaign, it bounces around from server to server as it crosses the Internet. Along the way, server administrators can read what you send. Email wasn&rsquo;t built for confidential information. If you have something confidential to send, please don&rsquo;t use MailChimp.</p>\r\n<p>Sometimes we review the content of our Members&rsquo; email campaigns to make sure they comply with our Terms of Use. To improve that process, we have software that helps us find email campaigns that may violate our Terms. Our employees or independent contractors may review those particular email campaigns. This benefits all Members who comply with our Terms of Use because, among other things, it reduces the amount of spam being sent through our servers and helps to maintain high deliverability.</p>\r\n<h1 id="section-your-lists">YOUR LISTS</h1>\r\n<h2>11. Your Distribution Lists</h2>\r\n<p>Your Distribution Lists are stored on a secure MailChimp server. We don&rsquo;t, under any circumstances, sell your Distribution Lists. If someone on your Distribution List complains or contacts us, we might then contact that person. Only authorized employees have access to view Distribution Lists. You may export (download) your Distribution Lists from MailChimp at any time.</p>\r\n<p>We&rsquo;ll use and disclose the information in your Distribution Lists only for the reasons listed under Use of Your Personal Information. We will not use and disclose the information in your Distribution Lists to:</p>\r\n<ul class="list markers">\r\n<li>bill or collect money owed to us;</li>\r\n<li>send you system alert messages;</li>\r\n<li>communicate with you about your account; or</li>\r\n<li>send you informational and promotional content.</li>\r\n</ul>\r\n<p>We may derive Aggregate Information from your Distribution List and will use that information as described in Section 7. If we detect abusive or illegal behavior related to your Distribution List, we may share your Distribution List or portions of it with affected ISPs or anti-spam organizations.</p>\r\n<h1 id="section-security">SECURITY</h1>\r\n<h2>12. Notice of Breach of Security</h2>\r\n<p>Nobody is safe from hackers. If a security breach causes an unauthorized intrusion into our system that materially affects you or people on your Distribution Lists, then MailChimp will notify you as soon as possible and later report the action we took in response.</p>\r\n<h2>13. Safeguarding Your Information</h2>\r\n<p>Our credit card processing vendor uses security measures to protect your information both during the transaction and after it&rsquo;s complete. Our vendor is certified as compliant with card association security initiatives, like the Visa Cardholder Information Security and Compliance (CISP), MasterCard&reg; (SDP), and Discovery Information Security and Compliance (DISC). We also perform annual SOC II audits. If you have any questions about the security of your personal information, you may contact us at <a href="mailto:privacy@mailchimp.com">privacy@mailchimp.com</a>.</p>\r\n<p>MailChimp accounts require a username and password to log in. You must keep your username and password secure, and never disclose it to a third party. Because the information in your Distribution Lists is so sensitive, account passwords are encrypted, which means we can&rsquo;t see your passwords. We can&rsquo;t resend forgotten passwords either. We&rsquo;ll only reset them.</p>\r\n<h1 id="section-compliance">COMPLIANCE</h1>\r\n<h2>14. We Operate in the United States</h2>\r\n<p>Our servers and offices are located in the United States, so your information may be transferred to, stored, or processed in the United States. While the data protection, privacy, and other laws of the United States might not be as comprehensive as those in your country, we take many steps to protect your privacy, including offering a <a href="http://mailchimp.com/legal/forms/data-processing-agreement/">data processing agreement</a>. By using our Websites, you understand and consent to the collection, storage, processing, and transfer of your information to our facilities in the United States and those third parties with whom we share it as described in this policy.</p>\r\n<h2>15. Data Transfers from the EU to the United States</h2>\r\n<p>Previously MailChimp has <a href="https://safeharbor.export.gov/companyinfo.aspx?id=29139">certified</a> our compliance with the <a href="http://www.export.gov/safeharbor">U.S.&ndash;E.U. and U.S.&ndash;Swiss Safe Harbor Framework</a>. In light of a recent <a href="http://static.ow.ly/docs/schrems_3OHQ.pdf">European Court of Justice ruling</a>, we no longer rely on the Safe Harbor Framework to justify the transfer of the personal data of European and Swiss residents to the United States. Instead Members located in the EU or Switzerland must request our updated data processing agreement which incorporates the <a href="http://eur-lex.europa.eu/LexUriServ/LexUriServ.do?uri=OJ:L:2010:039:0005:0018:EN:PDF">Standard Contractual Clauses</a> <a href="http://mailchimp.com/legal/forms/data-processing-agreement/">here</a>.</p>\r\n<h2>16. Members located in Australia</h2>\r\n<p>If you are a Member who lives in Australia then this section applies to you. We are subject to the operation of the <em>Privacy Act 1988</em> (&ldquo;<strong>Australian Privacy Act</strong>&rdquo;). We have some specific points to make you aware of.</p>\r\n<p>Where we say we assume an obligation about Personal Information then we are also requiring our subcontractors to undertake a similar obligation, where relevant.</p>\r\n<p>We will not use or disclose personal information for the purpose of our direct marketing to you unless: you have consented to receive direct marketing; you would reasonably expect us to use your personal details for the marketing; or we believe you may be interested in the material but it is impractical for us to obtain your consent. You may opt out of any marketing materials we send to you through an unsubscribe mechanism or by contacting us directly. If you have requested not to receive further direct marketing messages, we may nevertheless continue to provide you with messages that are not regarded as &ldquo;direct marketing&rdquo; under the Australian Privacy Act, including changes to our terms, system alerts and other information related to your account.</p>\r\n<p>Our servers are primarily located in the United States. In addition, we, or our subcontractors, may utilise cloud technology to store or process personal information, which may result in storage of data outside Australia. It is not practicable for us to specify in advance which country will have jurisdiction over such off-shore activities. All of our subcontractors, however, are required to comply with the Australian Privacy Act in relation to the transfer or storage of Personal Information overseas.</p>\r\n<p>If you think the information we hold about you is inaccurate, out of date, incomplete, irrelevant or misleading, we will take reasonable steps, consistent with our obligations under the Australian Privacy Act, to correct that information if you so request.</p>\r\n<p>If you are unsatisfied with our response to a privacy matter then you may consult either an independent advisor or contact the Office of the Australian Information Commissioner for additional help. We will provide our full cooperation if you pursue this course of action.</p>\r\n<h2>17. Accuracy of Data, Transparency, and Choice</h2>\r\n<p>We do our best to keep your data accurate and up to date, to the extent that you provide us with the information we need to do that. If your data changes (like a new email address), then you&rsquo;re responsible for notifying us of those changes.</p>\r\n<p>We&rsquo;ll retain your information for as long as your account is active or as long as needed to provide you services. We may also retain and use your information in order to comply with our legal obligations, resolve disputes, prevent abuse, and enforce our Agreements.</p>\r\n<p>We&rsquo;ll give an individual, either you or a Subscriber, access to any Personal Information we hold about them within 30 days of any request for that information. Individuals may request this information from us by <a href="http://mailchimp.com/contact?department=legal">contacting us here</a>. Unless it&rsquo;s prohibited by law, we&rsquo;ll remove any Personal Information about an individual, be it you or a Subscriber, from our servers at their request. There is no charge for an individual to access or update his or her personal information.</p>\r\n<h2>18. Do Not Track Disclosure</h2>\r\n<p>&ldquo;Do Not Track&rdquo; is a standard that&rsquo;s currently under development. Because it&rsquo;s not yet finalized, MailChimp adheres to the standards in this policy and does not monitor or follow any Do Not Track browser requests. That said, some of our features may.</p>\r\n<h2>19. Enforcement</h2>\r\n<p>We and TRUSTe regularly review our compliance with this Privacy Policy. If we receive a written complaint, we&rsquo;ll respond to the person who made it. You may contact our Privacy Officer at <a href="mailto:privacy@mailchimp.com">privacy@mailchimp.com</a> or by postal mail at our address listed above. We work with <a href="http://www.truste.com/">TRUSTe</a> to resolve any complaints that we can&rsquo;t resolve with our Members directly.</p>\r\n<p>Thanks for taking the time to learn about The Rocket Science Group&rsquo;s Privacy Policy, and thanks for trusting us to handle your email.</p>\r\n<p><em>Updated February 24, 2016</em></p>\r\n</div>\r\n</div>\r\n</div>\r\n</section>'),
(2, 'Terms of Use', '<h1 class="page-title">Legal</h1>\r\n<div class="page-summary normalize">\r\n<p>Thanks for taking the time to learn about SuvaSMS''s legal policies. It''s important stuff. This is where you''ll find information about how we protect your privacy, what you can and can''t do with SuvaSMS, and how we handle user accounts. If you still have questions after reading them, <a href="http://suvasms.com/contact">drop&nbsp;us&nbsp;a&nbsp;line</a>.</p>\r\n<p>&nbsp;</p>\r\n</div>');

-- --------------------------------------------------------

--
-- Table structure for table `profile_likes`
--

CREATE TABLE IF NOT EXISTS `profile_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `viewer_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `profile_likes`
--

INSERT INTO `profile_likes` (`id`, `profile_id`, `viewer_id`, `time`) VALUES
(4, 12, 20, 1439602861),
(8, 19, 12, 1439679321),
(11, 20, 12, 1439679327),
(13, 20, 19, 1439759091),
(14, 20, 21, 1452942459);

-- --------------------------------------------------------

--
-- Table structure for table `profile_views`
--

CREATE TABLE IF NOT EXISTS `profile_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `viewer_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=526 ;

--
-- Dumping data for table `profile_views`
--

INSERT INTO `profile_views` (`id`, `profile_id`, `viewer_id`, `time`) VALUES
(1, 12, 20, 1439602874),
(2, 20, 20, 1439605419),
(3, 19, 20, 1439605665),
(4, 20, 12, 1439678519),
(5, 12, 12, 1439678680),
(6, 19, 12, 1439678688),
(7, 12, 19, 1439759062),
(8, 20, 19, 1439759066),
(9, 19, 21, 1451751853),
(10, 12, 21, 1451752011),
(11, 20, 21, 1451752045),
(12, 51, 20, 1452514191),
(13, 24, 20, 1452514616),
(14, 38, 20, 1452517313),
(15, 29, 20, 1452517962),
(16, 44, 20, 1452940146),
(17, 21, 20, 1452940279),
(18, 25, 20, 1453205337),
(19, 22, 20, 1453581496),
(20, 43, 20, 1453586072),
(21, 4, 20, 1453586774),
(22, 4, 20, 1453586841),
(23, 4, 20, 1453586887),
(24, 4, 20, 1453587251),
(25, 4, 20, 1453587377),
(26, 4, 20, 1453587385),
(27, 4, 20, 1453587388),
(28, 4, 20, 1453587392),
(29, 4, 20, 1453587423),
(30, 4, 20, 1453587428),
(31, 4, 20, 1453587438),
(32, 4, 20, 1453587545),
(33, 4, 20, 1453587593),
(34, 4, 20, 1453587665),
(35, 4, 20, 1453587761),
(36, 4, 20, 1453587795),
(37, 4, 20, 1453587810),
(38, 4, 20, 1453587832),
(39, 4, 20, 1453587875),
(40, 4, 20, 1453587923),
(41, 4, 20, 1453587941),
(42, 4, 20, 1453587959),
(43, 4, 20, 1453588008),
(44, 4, 20, 1453588034),
(45, 4, 20, 1453588071),
(46, 9, 66, 1453616117),
(47, 9, 66, 1453616716),
(48, 3, 20, 1453616769),
(49, 3, 20, 1453617316),
(50, 3, 20, 1453617360),
(51, 3, 20, 1453617412),
(52, 3, 20, 1453617419),
(53, 3, 20, 1453617447),
(54, 3, 20, 1453617458),
(55, 3, 20, 1453617461),
(56, 3, 20, 1453617471),
(57, 3, 20, 1453617486),
(58, 3, 20, 1453617496),
(59, 3, 20, 1453617505),
(60, 3, 20, 1453617512),
(61, 3, 20, 1453617522),
(62, 3, 20, 1453617532),
(63, 3, 20, 1453617608),
(64, 3, 20, 1453617637),
(65, 3, 20, 1453617659),
(66, 3, 20, 1453617711),
(67, 3, 20, 1453617743),
(68, 3, 20, 1453617751),
(69, 3, 20, 1453617774),
(70, 3, 20, 1453617999),
(71, 3, 20, 1453618000),
(72, 3, 20, 1453618012),
(73, 3, 20, 1453618025),
(74, 3, 20, 1453619552),
(75, 3, 20, 1453619572),
(76, 3, 20, 1453619619),
(77, 3, 20, 1453619629),
(78, 3, 20, 1453619698),
(79, 3, 20, 1453619700),
(80, 3, 20, 1453619708),
(81, 3, 20, 1453619717),
(82, 3, 20, 1453619840),
(83, 3, 20, 1453619883),
(84, 3, 20, 1453619896),
(85, 3, 20, 1453619906),
(86, 3, 20, 1453619970),
(87, 3, 20, 1453619972),
(88, 3, 20, 1453620013),
(89, 3, 20, 1453620039),
(90, 3, 20, 1453620040),
(91, 3, 20, 1453620053),
(92, 3, 20, 1453620066),
(93, 3, 20, 1453620080),
(94, 9, 20, 1453620236),
(95, 1, 20, 1453620243),
(96, 2, 20, 1453620247),
(97, 2, 20, 1453620251),
(98, 2, 20, 1453620258),
(99, 7, 20, 1453620262),
(100, 3, 20, 1453620265),
(101, 2, 20, 1453620310),
(102, 2, 20, 1453620313),
(103, 2, 20, 1453620316),
(104, 3, 20, 1453620320),
(105, 6, 20, 1453620323),
(106, 2, 20, 1453620327),
(107, 4, 20, 1453620333),
(108, 9, 20, 1453620376),
(109, 9, 20, 1453620513),
(110, 9, 20, 1453620563),
(111, 9, 20, 1453620660),
(112, 9, 20, 1453621733),
(113, 9, 20, 1453621761),
(114, 9, 20, 1453621762),
(115, 9, 20, 1453621781),
(116, 9, 20, 1453621798),
(117, 9, 20, 1453621817),
(118, 9, 20, 1453621822),
(119, 9, 20, 1453621828),
(120, 9, 20, 1453621840),
(121, 9, 20, 1453621844),
(122, 9, 20, 1453621852),
(123, 9, 20, 1453621877),
(124, 9, 20, 1453621891),
(125, 9, 20, 1453621917),
(126, 9, 20, 1453621943),
(127, 9, 20, 1453621952),
(128, 9, 20, 1453621963),
(129, 9, 20, 1453621970),
(130, 9, 20, 1453621980),
(131, 9, 20, 1453622005),
(132, 9, 20, 1453622012),
(133, 9, 20, 1453622059),
(134, 9, 20, 1453622072),
(135, 9, 20, 1453622078),
(136, 9, 20, 1453622088),
(137, 9, 20, 1453622106),
(138, 9, 20, 1453622124),
(139, 9, 20, 1453622206),
(140, 9, 20, 1453624949),
(141, 9, 20, 1453624964),
(142, 4, 20, 1453626076),
(143, 4, 20, 1453626101),
(144, 4, 20, 1453626204),
(145, 4, 20, 1453626216),
(146, 9, 20, 1453626227),
(147, 9, 20, 1453626276),
(148, 9, 20, 1453627198),
(149, 9, 20, 1453627336),
(150, 9, 20, 1453627366),
(151, 9, 20, 1453627463),
(152, 9, 20, 1453627524),
(153, 9, 20, 1453627572),
(154, 9, 20, 1453627620),
(155, 9, 20, 1453627645),
(156, 9, 20, 1453628570),
(157, 9, 20, 1453629013),
(158, 9, 20, 1453630027),
(159, 9, 20, 1453630140),
(160, 9, 20, 1453630236),
(161, 9, 20, 1453630279),
(162, 9, 20, 1453630368),
(163, 9, 20, 1453630537),
(164, 4, 20, 1453630551),
(165, 3, 20, 1453630561),
(166, 1, 20, 1453630566),
(167, 9, 20, 1453630574),
(168, 9, 20, 1453630613),
(169, 9, 20, 1453630619),
(170, 9, 20, 1453630699),
(171, 2, 20, 1453630734),
(172, 3, 20, 1453630751),
(173, 3, 20, 1453630784),
(174, 3, 20, 1453631090),
(175, 3, 20, 1453631145),
(176, 3, 20, 1453631155),
(177, 3, 20, 1453631168),
(178, 3, 20, 1453631701),
(179, 3, 20, 1453631752),
(180, 3, 20, 1453631854),
(181, 3, 20, 1453631918),
(182, 3, 20, 1453631932),
(183, 3, 20, 1453631982),
(184, 3, 20, 1453632086),
(185, 3, 20, 1453632132),
(186, 3, 20, 1453632201),
(187, 3, 20, 1453632452),
(188, 3, 20, 1453632653),
(189, 3, 20, 1453632869),
(190, 3, 20, 1453632908),
(191, 3, 20, 1453632927),
(192, 3, 20, 1453633034),
(193, 3, 20, 1453633116),
(194, 3, 20, 1453633143),
(195, 3, 20, 1453633169),
(196, 3, 20, 1453633197),
(197, 3, 20, 1453633251),
(198, 3, 20, 1453633338),
(199, 3, 20, 1453633688),
(200, 3, 20, 1453633730),
(201, 3, 20, 1453633803),
(202, 3, 20, 1453634326),
(203, 9, 20, 1453635494),
(204, 9, 20, 1453635523),
(205, 9, 20, 1453635586),
(206, 9, 20, 1453635647),
(207, 9, 20, 1453635716),
(208, 4, 20, 1453635961),
(209, 4, 20, 1453636040),
(210, 4, 20, 1453636178),
(211, 4, 20, 1453636463),
(212, 4, 20, 1453636761),
(213, 7, 20, 1453636832),
(214, 7, 20, 1453636908),
(215, 36, 20, 1453636993),
(216, 9, 20, 1453637528),
(217, 9, 20, 1453637611),
(218, 9, 20, 1453637629),
(219, 9, 20, 1453637673),
(220, 9, 20, 1453637685),
(221, 9, 20, 1453637694),
(222, 9, 20, 1453637733),
(223, 9, 20, 1453637760),
(224, 9, 20, 1453637844),
(225, 9, 20, 1453637998),
(226, 9, 20, 1453638050),
(227, 9, 20, 1453638070),
(228, 9, 20, 1453638136),
(229, 9, 20, 1453638199),
(230, 9, 20, 1453638358),
(231, 9, 20, 1453638595),
(232, 9, 20, 1453638981),
(233, 9, 20, 1453639014),
(234, 9, 20, 1453639146),
(235, 9, 20, 1453639199),
(236, 9, 20, 1453639204),
(237, 9, 20, 1453639293),
(238, 9, 20, 1453639298),
(239, 9, 20, 1453639301),
(240, 9, 20, 1453639311),
(241, 9, 20, 1453639536),
(242, 9, 20, 1453639548),
(243, 9, 20, 1453639556),
(244, 9, 20, 1453639588),
(245, 9, 20, 1453639601),
(246, 9, 20, 1453639687),
(247, 9, 20, 1453639714),
(248, 9, 20, 1453639744),
(249, 9, 20, 1453639856),
(250, 9, 20, 1453639864),
(251, 9, 20, 1453639892),
(252, 9, 20, 1453639905),
(253, 9, 20, 1453639932),
(254, 9, 20, 1453639948),
(255, 9, 20, 1453640006),
(256, 9, 20, 1453640029),
(257, 9, 20, 1453640049),
(258, 9, 20, 1453640095),
(259, 9, 20, 1453640115),
(260, 9, 20, 1453640129),
(261, 9, 20, 1453640153),
(262, 9, 20, 1453640171),
(263, 66, 20, 1453654921),
(264, 4, 20, 1453661216),
(265, 9, 66, 1453661363),
(266, 9, 66, 1453661393),
(267, 10, 20, 1453701695),
(268, 10, 20, 1453701900),
(269, 10, 20, 1453701934),
(270, 10, 20, 1453701943),
(271, 10, 20, 1453701974),
(272, 10, 20, 1453702043),
(273, 23, 20, 1453702080),
(274, 10, 20, 1453702125),
(275, 10, 20, 1453702158),
(276, 10, 20, 1453705741),
(277, 61, 20, 1453705833),
(278, 10, 20, 1453705916),
(279, 10, 20, 1453705925),
(280, 1, 20, 1453750045),
(281, 2, 20, 1453750069),
(282, 2, 20, 1453751235),
(283, 2, 20, 1453751240),
(284, 2, 20, 1453751292),
(285, 2, 20, 1453751292),
(286, 2, 20, 1453751338),
(287, 7, 20, 1453751404),
(288, 7, 20, 1453751486),
(289, 7, 20, 1453751630),
(290, 12, 69, 1453803474),
(291, 63, 69, 1453807796),
(292, 12, 69, 1453807804),
(293, 66, 69, 1453807867),
(294, 68, 69, 1453807942),
(295, 12, 20, 1453816265),
(296, 11, 20, 1453824450),
(297, 12, 20, 1453824772),
(298, 11, 20, 1453917243),
(299, 1, 20, 1453920216),
(300, 70, 20, 1453922402),
(301, 1, 20, 1454006813),
(302, 1, 20, 1454007150),
(303, 1, 20, 1454007299),
(304, 1, 20, 1454007300),
(305, 1, 20, 1454007349),
(306, 1, 20, 1454007368),
(307, 1, 20, 1454007482),
(308, 1, 20, 1454007609),
(309, 1, 20, 1454007747),
(310, 1, 20, 1454007750),
(311, 1, 20, 1454008853),
(312, 1, 20, 1454008999),
(313, 1, 20, 1454009278),
(314, 1, 20, 1454009936),
(315, 1, 20, 1454009943),
(316, 1, 20, 1454009949),
(317, 1, 20, 1454010014),
(318, 1, 20, 1454010115),
(319, 1, 20, 1454010271),
(320, 1, 20, 1454010377),
(321, 1, 20, 1454010521),
(322, 1, 20, 1454010574),
(323, 1, 20, 1454010855),
(324, 1, 20, 1454010871),
(325, 1, 20, 1454010896),
(326, 1, 20, 1454010915),
(327, 1, 20, 1454010948),
(328, 1, 20, 1454010958),
(329, 1, 20, 1454011009),
(330, 1, 20, 1454011020),
(331, 1, 20, 1454011036),
(332, 1, 20, 1454011051),
(333, 1, 20, 1454011215),
(334, 1, 71, 1454011438),
(335, 1, 20, 1454011507),
(336, 1, 71, 1454011577),
(337, 1, 71, 1454011663),
(338, 1, 71, 1454011692),
(339, 1, 71, 1454011716),
(340, 1, 71, 1454012079),
(341, 1, 71, 1454012107),
(342, 1, 71, 1454012137),
(343, 1, 71, 1454012156),
(344, 1, 71, 1454012424),
(345, 1, 71, 1454012435),
(346, 1, 71, 1454012610),
(347, 1, 71, 1454012616),
(348, 1, 20, 1454234706),
(349, 1, 20, 1454656855),
(350, 1, 20, 1454656930),
(351, 1, 20, 1454656936),
(352, 1, 20, 1454656970),
(353, 39, 20, 1454657078),
(354, 1, 20, 1455194548),
(355, 1, 20, 1455373083),
(356, 1, 20, 1455373308),
(357, 1, 20, 1455807457),
(358, 1, 20, 1455807936),
(359, 1, 20, 1455899224),
(360, 1, 20, 1455899231),
(361, 1, 20, 1455899371),
(362, 1, 20, 1456078126),
(363, 1, 20, 1456078150),
(364, 1, 20, 1456078167),
(365, 1, 20, 1456078222),
(366, 1, 20, 1456078233),
(367, 1, 20, 1456078245),
(368, 1, 20, 1456123034),
(369, 1, 20, 1456162310),
(370, 1, 20, 1456163781),
(371, 1, 20, 1456164357),
(372, 1, 20, 1456164458),
(373, 1, 20, 1456164484),
(374, 1, 20, 1456164507),
(375, 1, 20, 1456164580),
(376, 1, 20, 1456164599),
(377, 1, 20, 1456164618),
(378, 1, 20, 1456164718),
(379, 1, 20, 1456164739),
(380, 1, 20, 1456164752),
(381, 1, 20, 1456164803),
(382, 1, 20, 1456164831),
(383, 1, 20, 1456164851),
(384, 1, 20, 1456164875),
(385, 1, 20, 1456164899),
(386, 1, 20, 1456164915),
(387, 1, 20, 1456164932),
(388, 1, 20, 1456164962),
(389, 1, 20, 1456164977),
(390, 1, 20, 1456164997),
(391, 1, 20, 1456165006),
(392, 1, 20, 1456165039),
(393, 1, 20, 1456165062),
(394, 1, 20, 1456165085),
(395, 1, 20, 1456165116),
(396, 1, 20, 1456165137),
(397, 1, 20, 1456165152),
(398, 1, 20, 1456165178),
(399, 1, 20, 1456165221),
(400, 1, 20, 1456165253),
(401, 1, 20, 1456165275),
(402, 1, 20, 1456165287),
(403, 1, 20, 1456165335),
(404, 1, 20, 1456165346),
(405, 1, 20, 1456165403),
(406, 1, 20, 1456165416),
(407, 1, 20, 1456165471),
(408, 1, 20, 1456165482),
(409, 1, 20, 1456165492),
(410, 1, 20, 1456165545),
(411, 1, 20, 1456165579),
(412, 1, 20, 1456165605),
(413, 1, 20, 1456165618),
(414, 1, 20, 1456165630),
(415, 1, 20, 1456165643),
(416, 1, 20, 1456165662),
(417, 1, 20, 1456165732),
(418, 1, 20, 1456165805),
(419, 1, 20, 1456165821),
(420, 1, 20, 1456165833),
(421, 1, 20, 1456165889),
(422, 1, 20, 1456166017),
(423, 1, 20, 1456166035),
(424, 1, 20, 1456166046),
(425, 1, 20, 1456166068),
(426, 1, 20, 1456166091),
(427, 1, 20, 1456166144),
(428, 1, 20, 1456166200),
(429, 1, 20, 1456166214),
(430, 1, 20, 1456166243),
(431, 1, 20, 1456166253),
(432, 1, 20, 1456166276),
(433, 1, 20, 1456166754),
(434, 1, 20, 1456166770),
(435, 1, 20, 1456166881),
(436, 1, 20, 1456166896),
(437, 1, 20, 1456166950),
(438, 1, 20, 1456166971),
(439, 1, 20, 1456167032),
(440, 1, 20, 1456167053),
(441, 1, 20, 1456167103),
(442, 1, 20, 1456167131),
(443, 1, 20, 1456167152),
(444, 1, 20, 1456167194),
(445, 1, 20, 1456167203),
(446, 1, 20, 1456167255),
(447, 63, 20, 1456167270),
(448, 33, 20, 1456167302),
(449, 46, 20, 1456167365),
(450, 27, 20, 1456167982),
(451, 50, 20, 1456168267),
(452, 26, 20, 1456168319),
(453, 1, 20, 1456169910),
(454, 1, 20, 1456170119),
(455, 1, 20, 1456170214),
(456, 1, 75, 1456170278),
(457, 1, 75, 1456170286),
(458, 1, 75, 1456170322),
(459, 1, 75, 1456170366),
(460, 1, 75, 1456170404),
(461, 1, 75, 1456170406),
(462, 1, 75, 1456170447),
(463, 1, 75, 1456170465),
(464, 1, 75, 1456170503),
(465, 1, 75, 1456170545),
(466, 1, 75, 1456170648),
(467, 1, 75, 1456170754),
(468, 1, 75, 1456170941),
(469, 1, 75, 1456170987),
(470, 1, 75, 1456171030),
(471, 1, 75, 1456171077),
(472, 1, 75, 1456171146),
(473, 1, 75, 1456171169),
(474, 1, 75, 1456171204),
(475, 1, 75, 1456171292),
(476, 1, 75, 1456171332),
(477, 1, 75, 1456171473),
(478, 1, 75, 1456171479),
(479, 1, 75, 1456171495),
(480, 1, 75, 1456171502),
(481, 1, 75, 1456171525),
(482, 1, 75, 1456171743),
(483, 20, 75, 1456171788),
(484, 66, 75, 1456171957),
(485, 35, 75, 1456172057),
(486, 22, 75, 1456172419),
(487, 1, 75, 1456206959),
(488, 1, 0, 1456207127),
(489, 1, 20, 1456207239),
(490, 1, 20, 1456234293),
(491, 4, 20, 1456470202),
(492, 4, 20, 1456470233),
(493, 4, 20, 1456470241),
(494, 68, 20, 1456646362),
(495, 1, 20, 1456821417),
(496, 80, 20, 1456821494),
(497, 1, 20, 1457074487),
(498, 1, 20, 1457074789),
(499, 1, 20, 1457074793),
(500, 49, 20, 1457381039),
(501, 2, 20, 1457515893),
(502, 5, 86, 1457523201),
(503, 5, 86, 1457523483),
(504, 7, 88, 1457527360),
(505, 7, 88, 1457527452),
(506, 7, 88, 1457527480),
(507, 7, 88, 1457527492),
(508, 7, 88, 1457527512),
(509, 7, 88, 1457527738),
(510, 7, 88, 1457527975),
(511, 7, 88, 1457527985),
(512, 7, 88, 1457528004),
(513, 7, 88, 1457528012),
(514, 7, 88, 1457528212),
(515, 7, 88, 1457528218),
(516, 7, 88, 1457528230),
(517, 7, 88, 1457528931),
(518, 7, 88, 1457529236),
(519, 7, 88, 1457529245),
(520, 7, 88, 1457529282),
(521, 7, 88, 1457529289),
(522, 7, 88, 1457529449),
(523, 7, 88, 1457529463),
(524, 7, 88, 1457529474),
(525, 1, 1, 1458405564);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reported_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_outbox`
--

CREATE TABLE IF NOT EXISTS `sms_outbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `recipient` text NOT NULL,
  `msg_status` varchar(250) DEFAULT NULL,
  `msg_id` varchar(250) DEFAULT NULL,
  `msg_cost` varchar(250) DEFAULT NULL,
  `date_done` varchar(20) NOT NULL,
  `date-timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sms_outbox`
--

INSERT INTO `sms_outbox` (`id`, `campaign_id`, `message`, `recipient`, `msg_status`, `msg_id`, `msg_cost`, `date_done`, `date-timestamp`) VALUES
(1, '5', 'Dear Geoffrey Sammy,\nkindly pay your loan amounting 20005 to avoid disconnection.\nregard \nkplc', '+254711646779', 'Success', 'ATXid_868eefa387ec0ceaa4c7a971c78a0fed', 'KES 1.0000', '1458824152', '2016-03-24 12:55:54'),
(2, '5', 'Dear Duncan Gitonga,\nkindly pay your loan amounting 2000 to avoid disconnection.\nregard \nkplc', '+254710513413', 'Success', 'ATXid_c21c24653bcaf6c1e8dcd6265f804593', 'KES 1.0000', '1458824154', '2016-03-24 12:55:55');

-- --------------------------------------------------------

--
-- Table structure for table `sms_template`
--

CREATE TABLE IF NOT EXISTS `sms_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_uid` char(13) NOT NULL,
  `customer_id` varchar(110) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `status` varchar(250) NOT NULL DEFAULT 'Active',
  `date_added` varchar(250) NOT NULL,
  `last_updated` varchar(250) NOT NULL,
  PRIMARY KEY (`template_id`),
  KEY `fk_customer_email_template_customer1_idx` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sms_template`
--

INSERT INTO `sms_template` (`template_id`, `template_uid`, `customer_id`, `name`, `content`, `status`, `date_added`, `last_updated`) VALUES
(1, '82815', '0', 'Untitled', 'Dear @fname @lname,\nKindly pay your loan amounting @field1 to avoid disconnection.\nRegard \nKPLC', 'Active', '1458476419', ''),
(2, '83612', 'XK5678', 'Untitled', 'Hi @fname @lname, We are delighted to wish you a happy easter.Please remember to pay your outstanding balance of @field2 which is due @field4 .Thanks', 'Active', '1458763225', ''),
(3, '13782', 'XK5678', 'Welcome Aboard-New Clients invitation SMS', ' Dear @fname @lname, thanks for choosing our services.Helb Loan.\r\n', 'Active', '1458849054', '');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_amount` int(11) NOT NULL,
  `transaction_name` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(123) NOT NULL,
  `energy_to_add` int(11) NOT NULL,
  `method` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) NOT NULL,
  `profile_username` varchar(250) NOT NULL,
  `unique_id` varchar(250) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(123) NOT NULL,
  `contact_no` varchar(250) DEFAULT NULL,
  `country` varchar(199) NOT NULL,
  `city` varchar(100) NOT NULL,
  `energy` int(11) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `bio` text NOT NULL,
  `gender` varchar(20) NOT NULL,
  `sexual_interest` int(11) NOT NULL,
  `specialization` varchar(250) NOT NULL,
  `local_range` varchar(20) NOT NULL,
  `instagram_username` varchar(50) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `registered` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `last_active` int(11) NOT NULL,
  `updated_preferences` int(11) NOT NULL,
  `updated_name` int(11) NOT NULL,
  `local_dating` int(11) NOT NULL,
  `private_profile` int(11) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `is_incognito` int(11) NOT NULL,
  `is_verified` int(11) NOT NULL,
  `has_disabled_ads` int(11) NOT NULL,
  `subscription_expire` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `profile_username`, `unique_id`, `email`, `password`, `contact_no`, `country`, `city`, `energy`, `latitude`, `longitude`, `age`, `bio`, `gender`, `sexual_interest`, `specialization`, `local_range`, `instagram_username`, `profile_picture`, `ip`, `registered`, `last_login`, `last_active`, `updated_preferences`, `updated_name`, `local_dating`, `private_profile`, `is_admin`, `is_incognito`, `is_verified`, `has_disabled_ads`, `subscription_expire`) VALUES
(1, 'EssayDuck Support', 'supportteam', 'XK5678', 'support@essayduck.com', '21232f297a57a5a743894a0e4a801fc3', NULL, 'Kenya', 'Nairobi', 50, '', '', 30, '<p>My Name is Dave Franco a well established user in this game.</p>', 'Male', 3, 'Business & Accounting', '', 'davefrnco', '4954bfb5fb305e608bfc56181beb2f125.png', '::1', 1439418997, 1463660468, 1463660475, 1, 1, 0, 0, 1, 0, 1, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
