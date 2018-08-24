CREATE DATABASE IF NOT EXISTS `wc2018` CHARACTER SET utf8 COLLATE utf8_general_ci;

grant usage on wc2018.* to wc2018@localhost identified by '9PymqtdoHOubQ0DxbnDnMxjy';
grant all privileges on wc2018.* to wc2018@localhost;

-- GRANT ALL ON `wc2018`.* TO `wc2018`@localhost IDENTIFIED BY '9PymqtdoHOubQ0DxbnDnMxjy';

-- Secure DB: How can I make my XAMPP installation more secure?
-- https://www.apachefriends.org/faq_linux.html
-- https://www.apachefriends.org/faq_windows.html
-- https://www.apachefriends.org/faq_osx.html