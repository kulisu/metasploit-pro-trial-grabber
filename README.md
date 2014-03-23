# Metasploit Pro Trial Grabber

##### This is a PHP command-line script to auto-grab the Metasploit's **[7-DAYs pro trial key]**.

Really tired to submit the trial request to Metasploit manually to get the pro key every weeks just because of its ~~[unfriendly price]~~ ?

You have a new choice today !

Just run this script and wait a minute, it will generate a new pro trial key for you.

It's using [Fake Name Generator] and [Fake Mail Generator] to fetch the contact information to register, all are fake and disposable.

All the register processes are in automation, so enjoy it now !

### Donation

if you like this script, buy me a coffee ?

[![Alipay](https://img.alipay.com/sys/personalprod/style/mc/top-logo.png)](https://me.alipay.com/skiddie)
[![Bitcoin](https://blockchain.info//Resources/buttons/donate_64.png)](http://goo.gl/f8ShlA)
[![Paypal](https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif)](http://goo.gl/tRt4zN)

### Environment

* [PHP]: 5.4.4-14+deb7u7
* [cURL]: 7.26.0
* [Kali Linux]: 1.0.6 x86

### Installation

```sh
# clone the script by using git directly.
root@kali-linux:~# git clone https://github.com/skiddie/metasploit-pro-trial-grabber.git
# no git installed ? you can use wget instead :)
root@kali-linux:~# wget https://github.com/skiddie/metasploit-pro-trial-grabber/archive/master.zip

# OPTIONAL: execute the command below if you get the exception message 'cURL library is not loaded'.
root@kali-linux:~# apt-get install php5-curl
```

### Execution

```sh
# the environment mentioned above is tested by author and work functionally.
root@kali-linux:~# php execute.php
```

### Output

    [+] loading the 3rd-party libraries .. DONE !
    [+] creating all classes' instance .. DONE !
    [+] checking all mail domains are valid or not .. 
      [-] parsing all the available domains from response .. DONE !
        [*] parsing the name info from response .. DONE !
        [*] parsing the additional info from response .. DONE !
      [-] extracting from all the available domains .. 
        [*] checking the mail address: stagazen@armyspy.com is valid or not .. ILLEGAL !
        [*] checking the mail address: stagazen@cuvox.de is valid or not .. ILLEGAL !
        [*] checking the mail address: stagazen@dayrep.com is valid or not .. ILLEGAL !
        [*] checking the mail address: stagazen@einrot.com is valid or not .. VALID !
        [*] checking the mail address: stagazen@fleckens.hu is valid or not .. VALID !
        [*] checking the mail address: stagazen@gustr.com is valid or not .. VALID !
        [*] checking the mail address: stagazen@jourrapide.com is valid or not .. ILLEGAL !
        [*] checking the mail address: stagazen@rhyta.com is valid or not .. ILLEGAL !
        [*] checking the mail address: stagazen@superrito.com is valid or not .. ILLEGAL !
        [*] checking the mail address: stagazen@teleworm.us is valid or not .. ILLEGAL !
      [-] ALL DONE !
    [+] ALL DONE !
    [+] choosing a valid domain and generating an email address .. DONE !

    [+] READY TO FIRE !

    [+] submitting the trial request to metasploit ..
      [-] parsing the hidden filds' value from response ..
        [*] field: custparamleadsource has value: 443597
        [*] field: custparamreturnpath has value: https://localhost:3790/setup/activation
        [*] field: custparamproductaxscode has value: msY5CIoVGr
      [-] ALL DONE !
    [+] preparing the registration payload .. DONE !
    [+] configuring the CURL options .. DONE !
    [+] sending the registration data to online form .. DONE !
    [+] ALL DONE !
    [+] looping to retrieve the trial mail content ..
      [-] checking the trial confirmation mail has delivered to inbox or not ..
        [*] waiting for trial mail delivered to inbox: http://www.fakemailgenerator.com/inbox/einrot.com/stagazen/ ..
        [*] waiting for trial mail delivered to inbox: http://www.fakemailgenerator.com/inbox/einrot.com/stagazen/ ..
        [*] waiting for trial mail delivered to inbox: http://www.fakemailgenerator.com/inbox/einrot.com/stagazen/ ..
        [*] waiting for trial mail delivered to inbox: http://www.fakemailgenerator.com/inbox/einrot.com/stagazen/ ..
        [*] waiting for trial mail delivered to inbox: http://www.fakemailgenerator.com/inbox/einrot.com/stagazen/ ..
        [*] BINGO ! the mail just delivered, parsing it .. DONE !
      [-] ALL DONE !

    Your 7-days pro trial key: CC97-GP66-X8TT-2K19
    
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
