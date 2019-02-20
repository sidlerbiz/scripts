# Bash script for automatic installation proxy server on OS Debian
# Information for DAXX

Script have 6 income parameters: 

1)SERVER USER;

2)SERVER_IP;

3)SERVER PASSWORD;

4)PROXY_PORT;

5)PROXY_USER_NAME;

6)PROXY_PASSWORD;

Before use, need to install utility sshpass with command "apt-get install sshpass" 
Example for use ./squid3_debian.sh root 10.10.10.10 ygW65erGDREsh0 6128 proxyuser proxypass  
