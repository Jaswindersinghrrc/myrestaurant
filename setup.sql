CREATE TABLE `members` (
`id` int(4) NOT NULL auto_increment,
`username` varchar(200) NOT NULL default '',
`password` varchar(200) NOT NULL default '',
PRIMARY KEY (`id`)
) AUTO_INCREMENT=2 ;
-- 
-- Dumping data for table `members`
-- 
INSERT INTO `members` VALUES (1, 'john', '1234');

-- Set Permissions
-- Create user login_user, and allow him to select only on username and password:
CREATE USER 'login_user'@'localhost' IDENTIFIED BY '1234';
GRANT SELECT ON `blog`.`members` TO 'login_user'@'localhost';

-- Create user register_user and let him insert records into database:
CREATE USER 'register_user'@'localhost' IDENTIFIED BY '1234';
GRANT INSERT ON `blog`.`members` TO 'register_user'@'localhost';

-- Create a 3rd user that will have normal access to the system:
CREATE USER 'normal_user'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT , INSERT , UPDATE , DELETE , REFERENCES ON `blog`.* TO 'normal_user'@'%';

-- Update privileges in database. RUN AGAINST LOCALHOST, NOT ON DATABASE blog.
FLUSH PRIVILEGES;
