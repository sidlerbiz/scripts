#!/bin/bash
USER="$1"
IP=$2
PASSWORD="$3"
PORT=$4
SQUIDUSER="$5"
SQUIDPASS="$6"


sshpass -p $PASSWORD ssh -o "StrictHostKeyChecking no" $USER@$IP 'mv /etc/apt/sources.list /etc/apt/sources.list.orig;
 echo -e "deb http://security.debian.org/ jessie/updates main contrib\ndeb-src http://security.debian.org/ jessie/updates main contrib\ndeb http://httpredir.debian.org/debian jessie main contrib\ndeb-src http://httpredir.debian.org/debian jessie main contrib\ndeb http://httpredir.debian.org/debian jessie-updates main contrib\ndeb-src http://httpredir.debian.org/debian jessie-updates main contrib" >> /etc/apt/sources.list;
 apt-get update;
 apt-get -y upgrade;
 apt-get -y install squid3 apache2-utils;
 touch /etc/squid3/squid.conf;
 mv /etc/squid3/squid.conf /etc/squid3/squid.conf.orig;
 echo -e "auth_param basic program /usr/lib/squid3/basic_ncsa_auth /etc/squid3/.htpasswd\nauth_param basic children 1\nauth_param basic credentialsttl 1 minute\nauth_param basic casesensitive off\nauth_param basic realm Squid proxy-caching web server\nacl password proxy_auth REQUIRED\nhttp_access allow password\nhttp_port '$PORT'\nacl lan src 10.0.0.0/24\nacl auth proxy_auth REQUIRED\nhttp_access allow lan\nhttp_access allow auth\nhttp_access allow localhost\nhttp_access deny all\ncache deny all\nforwarded_for delete\nrequest_header_access Via deny all\nrequest_header_access Referer deny all\nrequest_header_access X-Forwarded-For deny all\nrequest_header_access Cache-Control deny all\nvisible_hostname prox.server.world" >> /etc/squid3/squid.conf;
 htpasswd -b -c /etc/squid3/.htpasswd '$SQUIDUSER' '$SQUIDPASS';
 /etc/init.d/squid3 restart;'










