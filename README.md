# Metasploit Pro Trial Grabber

### Attention

THIS SCRIPT WONT WORK ANY MORE !

### Introduction

This is a PHP command-line script to auto-grab the Metasploit's [14-DAYs pro trial key].

Really tired to submit the trial request to Metasploit manually to get the pro key every weeks just because of its ~~[unfriendly price]~~ ?

You have a new choice today !

Just run this script and wait a minute, it will generate a new pro trial key for you.

It's using [Fake Name Generator] and [Fake Mail Generator] to fetch the contact information to register, all are fake and disposable.

All the register processes are in automation, so enjoy it now !

### Donation

if you like this script, buy me a coffee ?

[![Bitcoin](https://blockchain.info//Resources/buttons/donate_64.png)](http://goo.gl/f8ShlA)
[![Paypal](https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif)](http://goo.gl/tRt4zN)
![Alipay](https://i.imgur.com/MFwEQSp.png)

### Donators

2014-07-23: Anonymous - [0.01615 BTC](https://blockchain.info/tx/be809956bf74307cd60cfdc6adba6d026acc51816cceb6a9c297be4374a85eb0) (~ 300TWD)

### Environment

* Linux:

    * [PHP]: 5.4.4-14+deb7u12
    * [cURL]: 7.26.0
    * [Kali Linux]: 1.0.8 x86

* Windows:

    * [XAMPP]: 1.8.2
    * Windows XP Professional Service Pack 3 x86

* OS X:

    * [PHP]: 5.5.14
    * [cURL]: 7.37.1
    * [OS X]: Yosemite 10.10

### Installation

* Linux:

```sh
# clone the script by using git directly.
root@kali-linux:~# git clone https://github.com/kulisu/metasploit-pro-trial-grabber.git
# install the project dependencies
root@kali-linux:~# cd metasploit-pro-trial-grabber && php composer.phar install

# OPTIONAL: execute the command below if you get the exception message 'cURL library is not loaded'.
root@kali-linux:~# apt-get install php5-curl
```

* Windows:

```batch
# download the script and unzip it by yourself, path `C:\` for example.
C:\Documents and Settings\Chris>cd C:\metasploit-pro-trial-grabber-master && C:\xampp\php\php.exe composer.phar install
```

* OS X:

```sh
Chris-MBPR:~ Chris$ git clone https://github.com/kulisu/metasploit-pro-trial-grabber.git
Chris-MBPR:~ Chris$ cd metasploit-pro-trial-grabber && php composer.phar install
```

### Execution

* Linux:

```sh
# the environment mentioned above is tested by author and work functionally.
root@kali-linux:~# php execute.php
```

* Windows:

```batch
C:\metasploit-pro-trial-grabber-master>C:\xampp\php\php.exe execute.php
```

* OS X:

```sh
Chris-MBPR:~ Chris$ php execute.php
```

### Output

    [+] defining the pre-required constants .. DONE !
    [+] loading the 3rd-party libraries .. DONE !
    [+] creating all classes' instance .. DONE !
    [+] checking all mail domains from provider: Spambog are valid or not ..
      [-] parsing all the available domains from response .. DONE !
        [*] parsing the profile fields from response .. DONE !
        [*] parsing the additional info from response .. DONE !
      [-] extracting from all the available domains .. 
        [*] checking the mail address: habing@spambog.com is valid or not .. ILLEGAL
        [*] checking the mail address: habing@spambog.de is valid or not .. ILLEGAL
        [*] checking the mail address: habing@discardmail.com is valid or not .. ILLEGAL
        [*] checking the mail address: habing@discardmail.de is valid or not .. ILLEGAL
        [*] checking the mail address: habing@spambog.ru is valid or not .. ILLEGAL
        [*] checking the mail address: habing@0815.ru is valid or not .. ILLEGAL
        [*] checking the mail address: habing@s0ny.net is valid or not .. ILLEGAL
        [*] checking the mail address: habing@hulapla.de is valid or not .. ILLEGAL
        [*] checking the mail address: habing@bund.us is valid or not .. VALID !
        [*] checking the mail address: habing@teewars.org is valid or not .. ILLEGAL
        [*] checking the mail address: habing@brennendesreich.de is valid or not .. VALID !
        [*] checking the mail address: habing@ama-trans.de is valid or not .. ILLEGAL
        [*] checking the mail address: habing@ama-trade.de is valid or not .. ILLEGAL
        [*] checking the mail address: habing@malahov.de is valid or not .. ILLEGAL
        [*] checking the mail address: habing@e-postkasten.eu is valid or not .. VALID !
        [*] checking the mail address: habing@lags.us is valid or not .. VALID !
        [*] checking the mail address: habing@6ip.us is valid or not .. VALID !
        [*] checking the mail address: habing@e-postkasten.info is valid or not .. VALID !
        [*] checking the mail address: habing@e-postkasten.de is valid or not .. VALID !
        [*] checking the mail address: habing@e-postkasten.com is valid or not .. VALID !
      [-] ALL DONE !
    [+] ALL DONE !
    [+] choosing a valid domain and generating an email address .. DONE !

    [+] READY TO FIRE !

    [+] submitting the trial request to metasploit ..
      [-] parsing the hidden filds' value from response ..
        [*] field: custparamleadsource has value: 443597
        [*] field: submitted has value: 
        [*] field: custparamreturnpath has value: https://localhost:3790/setup/activation
        [*] field: custparamproduct_key has value: 
        [*] field: custparamproductaxscode has value: msY5CIoVGr
        [*] field: custparamthisIP has value: 103.245.222.133
      [-] ALL DONE !
      [-] preparing the registration payload .. DONE !
      [-] sending the registration data to online form .. DONE !
    [+] ALL DONE !
    [+] looping to retrieve the trial mail content ..
      [-] checking the trial confirmation mail has delivered to inbox or not ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] waiting for trial mail delivered to inbox: http://www.spambog.com/rss/habing@brennendesreich.de ..
        [*] BINGO ! the mail just delivered, parsing it .. DONE !
      [-] ALL DONE !
    [+] ALL DONE !

    Your 7-days pro trial key: CRDJ-09P8-EG4K-EQS4

![Output](http://i.imgur.com/e1yv2Pz.png)

### Credits:

* [PHP Curl Class]
* [PHP Simple HTML DOM Parser]
* [Random USERAGENT in CuRL and PHP]

[7-DAYs pro trial key]:https://www.rapid7.com/register/metasploit-trial.jsp?product
[unfriendly price]:https://community.rapid7.com/docs/DOC-2287
[Fake Name Generator]:http://www.fakenamegenerator.com
[Fake Mail Generator]:http://www.fakemailgenerator.com
[PHP]:http://www.php.net/manual/en/install.unix.debian.php
[cURL]:http://www.php.net/manual/en/curl.installation.php
[PHP Curl Class]:https://github.com/php-curl-class/php-curl-class
[PHP Simple HTML DOM Parser]:http://simplehtmldom.sourceforge.net
[Random USERAGENT in CuRL and PHP]:http://www.danmorgan.net/programming/php-programming/random-useragent-in-curl-and-php
[Kali Linux]:http://www.kali.org/downloads
[XAMPP]:http://downloads.sourceforge.net/project/xampp/XAMPP%20Windows/1.8.2/xampp-win32-1.8.2-5-VC9-installer.exe
[30-DAYS key]:http://www.rapid7.com/products/metasploit/metasploit-pro-registration-corelan.jsp
[14-DAYs trial form]:http://www.rapid7.com/products/metasploit/metasploit-pro-registration.jsp
[OS X]:http://www.apple.com/osx/
