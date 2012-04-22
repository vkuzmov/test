#!/bin/bash

working_dir_path=$(echo "/Users/sdipchikov/Documents/Projects/");                                                    #set this to your projects folder
username=$(echo "sdipchikov");                                                                                       #set this to your username

echo " ";
echo " ";
echo "-=Live Long and Prosper=-";
echo " ";
echo "Please enter name for the new project: ";

read project_name;

project_name_striped=$(echo $project_name | tr " " "_");                                                             #remove the spaces

project_name_formated=$(echo $project_name_striped | tr '[:upper:]' '[:lower:]');                                    #convert to lower case


new_dir_name=$project_name_formated;                                                                                 #form the new directory name
link_name=$project_name_formated;                                                                                    #form the name for the link to the project

mkdir "$working_dir_path$project_name";                                                                              #create the project dir


#Create the sub directories tree
mkdir "$working_dir_path$project_name/repo";
sudo chown -R $username:staff "$working_dir_path$project_name/";


sudo -u $username git clone git@despark.beanstalkapp.com:/despark-php-projects-file-skeleton.git "$working_dir_path$project_name/repo"

# <-- Create Virtual Host for the Project --> 
#Don't forget to create the vhosts.conf file and add "Include /Applications/MAMP/conf/apache/vhosts.conf" at the end of http.conf of your appache

echo "127.0.0.1 $link_name" >> /etc/hosts;
echo "" >> /etc/hosts;

echo "" >> /Applications/MAMP/conf/apache/vhosts.conf;                                                                 
echo "<VirtualHost *:80>" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "  ServerName $link_name" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "  DocumentRoot \"$working_dir_path$project_name/repo/public/\"" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "  <Directory \"$working_dir_path$project_name/repo/public/\">" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "    Options Indexes FollowSymLinks MultiViews" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "    AllowOverride All" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "    Order allow,deny" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "    allow from all" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "  </Directory>" >> /Applications/MAMP/conf/apache/vhosts.conf;
echo "</VirtualHost>" >> /Applications/MAMP/conf/apache/vhosts.conf;

/Applications/MAMP/bin/apache2/bin/apachectl restart;

echo " ";
echo "Folders are created captain! You can access your project on http://$link_name/. Enjoy the codding!";
echo " ";

exit
