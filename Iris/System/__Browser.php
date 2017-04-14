<?php

namespace Iris\System;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * A pseudo class serving as a documentation for UserAgent strings
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class __Browser extends Browser {

    public static $UserAgent = [
// IX
// A small Active X Windows only browser - no javascript and CSS support we think. Trial version downloadable. No visible price information.
        '1X' => 'Science Traveller International 1X/1.0',
        // Explanation: 1X on Windows something - pretty descriptive string. String from Jonathan McCormack - thanks.
// -----------------------------------------------------------------------------------------------------------------------------
// Act 10
// A tiny - we're talking 750K download - browser for Windows. Supports Style sheets but no JS we suspect. Freeware. 
        'Act 10' => 'Mozilla/3.0 (compatible)',
        //  Explanation: Act 10 on something? String from Anita O'Brien - thanks. 
//  -----------------------------------------------------------------------------------------------------------------------------
//  Amaya
//  The W3C's own hosted browser and authoring tool project, another Open Source project. Runs on Windows and Linux/Unix. Pretty quirky the last time we downloaded it (long time ago). */
        'Amaya-952' => 'amaya/9.52 libwww/5.4.0',
        // Explanation: Amaya 9.52 on SUSE 10. String from Ted King - thanks.
        'Amaya-951' => 'amaya/9.51 libwww/5.4.0',
        // Explanation: Amaya 9.51 on linux 2.6 k7 SMP, Gtk+ 2 interface (April 2006) String from Lucas Lommer - and yeah its still quirky - thanks.
        'Amaya-901' => 'amaya/9.1 libwww/5.4.0',
        // Explanation: Amaya 9.1 on something? String from Peter Booth who tells us its still pretty quirky - thanks.
        'Amaya-602' => 'amaya/6.2 libwww/5.3.1',
        //  Explanation: Amaya 6.2 (current version) on something? String from Jens Tønnesen - thanks.
// -----------------------------------------------------------------------------------------------------------------------------
// Amiga Voyager
// Amiga browser - or should we say - the Amiga browser (apparently not these guys have got choices including Aweb and iBrowse). Shareware browser for all you Amiga/Morphos fans.
        'AmigaVoyager' => 'AmigaVoyager/3.4.4 (MorphOS/PPC native)',
        // Explanation: AmigaVoyager 3.4.4 on a PowerPC? String from poeml ? - thanks.
        /*
          // -----------------------------------------------------------------------------------------------------------------------------
          // apt-get
          // Advanced Package Tool (APT). This should only appear on FTP sites and is a Linux software update tool used on many distributions. Originally exclusive to Debian - now widely available and used on multiple distributions. No link is provided since each distribution has its own version which is bundled with the release.
          'apt-get'=> 'Debian APT-HTTP/1.3 (0.9.7.5ubuntu5.1)',
          // Explanation: Ap-get 0.9.7 on Ubuntu 12.10. String from Saikrishna Arcot - thanks.

          Ubuntu APT-HTTP/1.3 (0.7.23.1ubuntu2)

          Explanation: Ap-get 0.7.23 on Linux Mint 8. String from Jake Wasdin - thanks.

          Ubuntu APT-HTTP/1.3

          Explanation: Ap-get 1.3 on Ubuntu Edgy Eft's. String from Jake Wasdin - thanks. */

// -----------------------------------------------------------------------------------------------------------------------------
// Arachne
// This a browser for DOS (honest) that apparently even plays movies. Gotta love it.
        'arachne' => 'xChaos_Arachne/5.1.89;GPL,386+',
        // Explanation: A web browser for DOS. From Ryan Jones - thanks.
//  -----------------------------------------------------------------------------------------------------------------------------
// Arora
// A QT based minimal browser based on WebKit. Windows and *nix.
        'arora-3' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)',
        //  Explanation: Arora 0.3 on XP. String from Jake Wasdin - thanks.
        'arora-2' => 'Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.2 (Change: 0 )',
        // Explanation: Arora 0.2 on kubuntu Hardy. String from Jake Wasdin - thanks.
        /*
          // -----------------------------------------------------------------------------------------------------------------------------
          Avant Browser

          A fast (they say) kinda tabbed version of MSIE. Free forever (they say).

          Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser;
          Avant Browser; .NET CLR 1.0.3705; .NET CLR 1.1.4322;
          Media Center PC 4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)

          Explanation:Avant Browser on MS Media Center PC (XP with SP2) and multiple .NET frameworks. String from R. Tinker - thanks.

          Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322; FDM)

          Explanation:Avant Browser (MSIE 6 clone) on XP with SP2 and .NET framework. FDM is a free download manager. String from Suluh Legowo - thanks.

          Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; Avant Browser [avantbrowser.com]; Hotbar 4.4.5.0)

          Explanation:Avant Browser (MSIE 6 clone) on Win 2K. String from Dean Stringer - thanks.
         */
// -----------------------------------------------------------------------------------------------------------------------------
// Amiga Aweb
// AWeb has been around for a while but was Open Sourced in 2002 (hence the Beta references on the site) Originally released in mid nineties - now being overhauled. We suspect this project (Amiga) generally is seeing more action now that when it was at in its heyday..
        'Amiga-Aweb-35' => 'Amiga-AWeb/3.5.07 beta',
        // Explanation: Sames as string below - but in native mode. String from Scott W - thanks.
        'Amiga-Aweb-35b' => 'Mozilla/6.0; (Spoofed by Amiga-AWeb/3.5.07 beta)',
        // Explanation: Mozilla/6.0 no less - supercharged Firefox like features! Current Amiga AWebPPC build, PPC native, running on AmigaOS4 (PPC/G4). Available for classic 68k systems as well as PPC classics plus the new AmigaOS4. String from Scott W - thanks.
        'Amiga-Aweb-34' => 'MSIE/6.0; (Spoofed by Amiga-AWeb/3.4APL)',
        // Explanation: No fooling around with this one - straight to the point. String from pomel? - thanks.

        /*
          // -----------------------------------------------------------------------------------------------------------------------------
          Bluefish

          Not strictly a browser but an HTML editor and part of the openoffice suite. Or does someone know better?

          gnome-vfs/2.12.0 neon/0.24.7

          Explanation: Bluefish 1.0.7 on SUSE 10 - it uses the Gnome Virtual File System as a grabber. String from Ted King - thanks.

          bluefish 0.6 HTML editor

          Explanation: Bluefish 0.6 (Free HTML editor) on Linux Mandrake 8.0
         */
// -----------------------------------------------------------------------------------------------------------------------------
//Browsex
//
        // An Open Source browser for Linux and cross-compiled to Windows (Mingw32). Uses C and Tcl/Tk. Not a Gecko clone.
        'Browsex' => 'Mozilla/4.61 [en] (X11; U; ) - BrowseX (2.0.0 Windows)',
        // Explanation: BrowseX on Linux we assume from the X11? String from Jonathan McCormack - thanks.

        /*
          // -----------------------------------------------------------------------------------------------------------------------------
          Camino

          Mozilla's own MAC OS X lightweight browser project. Version 0.6 reflects the name Chimera which was the original name of this project. Mozilla do like to change browser names a lot (well in this case it turns out there is another browser named Chimera). Does appear to be a bit of a Cinderella project within Mozilla.

          Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.4; en; rv:1.9.0.19) Gecko/2011091218 Camino/2.0.9 (like Firefox/3.0.19)

          Explanation: Camino 2.0.9 on a PPC Mac OS X. String from Otis Maclay - thanks.

          Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en; rv:1.9.0.19) Gecko/2011032020 Camino/2.0.7 (like Firefox/3.0.19)

          Explanation: Camino 2.0.7 on a Intel Mac OS X. String from Peter Versteegen - thanks.

          Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.14) Gecko/20080409 Camino/1.6 (like Firefox/2.0.0.14)

          Explanation: Camino 1.6 on a Intel Mac OS X. Now adding the 'like Firefox' string. String from Paul B - thanks.

          Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.6) Gecko/20070809 Camino/1.5.1

          Explanation: Camino 1.5.1 on a PC Mac OS X. Gecko version is the same as Firefox 2.x. String from Ty Hatch - thanks.

          Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.1) Gecko/20060118 Camino/1.0b2+

          Explanation: Camino nightly build on a Mac OS X. We guess the trailing plus indicates the nightly - are we samrt or what. String from Tim Johnsen - thanks.

          Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.5b) Gecko/20030917 Camino/0.7+

          Explanation: Camino (ex Chimera) 0.7 on a MAC. Hot from the nighly builds and its got the aqua look by embedding in Cocoa (not a skin we are told) (String courtesy of Robert Johnson).

          Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US; rv:1.0.1) Gecko/20021104 Chimera/0.6

          Explanation: Chimera 0.6 on a MAC??. We think it only runs on OS X. But has it got an aqua skin!! (String courtesy of John Reid).
          // -----------------------------------------------------------------------------------------------------------------------------
          //Charon
          //Charon runs on the Infero OS which is related to Plan 9 which is from the original Unix guys. Apparently the only way you get the browser is to download the Inferno OS as well. */
        'Charon' => 'Mozilla/4.08 (Charon; Inferno)',
        //Explanation: Charon on Inferno OS. String from Chris Barts - thanks.
// -----------------------------------------------------------------------------------------------------------------------------
//Check&Get
// Browse, monitor for page changes and download webs - mmmmmmmmmmm. Next time you hear a giant sucking sound it may be this guy.
        'Check&Get' => 'Mozilla/2.0 compatible; Check&Get 1.14 (Windows NT)',
//Explanation: Check&Get Version 1.14 on NT 4.0. Sloppy browser string no parentheses. String from Erik Inge Bolsø - thanks.
// -----------------------------------------------------------------------------------------------------------------------------
//Chimera
//X based browser for the Unix world - seems very old and not updated for some considerable time - this upgrade boasts HTML 3.2 compatability - which makes it as feature rich as MSIE 5 (just joking - honest).
        'Chimera' => 'Chimera/2.0alpha',
//Explanation: Informative browser string. String from Jonathan McCormack - thanks.
// -----------------------------------------------------------------------------------------------------------------------------
//Chrome and ChromeOS
//New Webkit based browser from that search company with the funny name and which generated a lot of 'end of the world as we know it' discussion when initially released. World still seems to be revolving last time we checked. Chromium is the Open Source base from which Chrome is derived and Cross-over chromium is the Linux and MAC OS X port of Chromium - whew!. Other google strings. And the Google Nexus Phones.
        'Chrome46' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36',
//Explanation:Chrome 46.0.2490.80 on Windows 7. String from us - Thanks - you're welcome.
        'Chrome44' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36',
//Explanation:Chrome 44.0.2403.157 on Mac OSX 10.10.5. String from Paul Cheshire - Thanks.
        'Chrome43' => 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36',
//Explanation: Chrome 43.0.2357.130 on Windows 8. String from Billy McKinney - Thanks.
        'Chrome43a' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/43.0.2357.81 Chrome/43.0.2357.81 Safari/537.36',
//Explanation: Chromium 43.0.2357.8 on Linux Mint. String from Joe Staton - Thanks.
        'Chrome43b' => 'Mozilla/5.0 (Linux; Android 5.1; SHIELD Android TV Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.93 Mobile Safari/537.36',
//Explanation: Chrome 43.0.2357.93 on an Nvidia shield TV (honest). String from Neel Gupta - Thanks.
        'Chrome43c' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36',
//Explanation: Chrome 43.0.2357.132 on Windows 7. String from Kumaresan Arumugam - Thanks.
        'Chrome43d' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36',
//Explanation: Chrome 43.0.2357.124 on Windows 8.1. String from William Field - Thanks.
        'Chrome43e' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36',
//Explanation: Chrome 43.0.2357.124 on Windows 7. String from Samuel Marshall - Thanks.
        'Chrome43f' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36',
//Explanation: Chrome 43.0.2357.124 on a Mac with OSX 10.10.2. String from Eliana Lopez - Thanks.
//Versions Pre-43
        'Chrome27' => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.32 (KHTML, like Gecko) Chrome/27.0.1421.0 Safari/537.32',
//Explanation: Chrome 27.0.1421.0 on Linux Puppy 5.2x (Lucid Puppy 5.25). String from Mr Lapin (right!) - Thanks.
        'Chrome41' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',
//Explanation: Chrome 41.0.2272.118 on Windows 7. String from Imran Khan - Thanks.
        'Chrome41a' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
//Explanation: Chrome 41.0.2272.89 Windows 7. String from Sami Gounder - Thanks.
        'Chrome40' => 'Mozilla/5.0 (X11; CrOS x86_64 6457.94.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.114 Safari/537.36',
//Explanation: Chrome 40.0.2214.114 on Chrome OS. String from Thu Win - Thanks.
        'Chrome40a' => 'Mozilla/5.0 (Linux; Android 4.4.2; 7040N Build/KVT49L) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.109 Mobile Safari/537.36',
//Explanation: Chrome 40.0.2214.109 on Alcatel 7040N (a.k.a. One Touch Fierce 2) with Android 4.4.2. String from Mikey Ingram - Thanks.
        'Chrome38' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36',
//Explanation: Chrome 38.0.2125.122 on windows XP. String from Gotam Kshtriy - Thanks.
        'Chrome36' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36',
//Explanation: Chrome 36.0.1985.143 on OS 10.9.4. String from Vincent Mitchell - Thanks.
        'Chrome35' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36',
//Explanation: Chrome 35.0.1916.153 on Windows 7. String from Leah Yates - Thanks.
        'Chrome34' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36',
//Explanation: Chrome 34.0.1847.116 on Windows 7. String from Jamie Gladson - Thanks.
        'Chrome33' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36',
//Explanation: Chrome 33.0.1750.146 on Windows 7. String from Florian Nourrisse - Thanks.
        'Chrome27' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36',
//Explanation: Chrome 27.0.1453.93 on Linux un X11. String from M.W (you know who you are) - Thanks.
        'Chrome32' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36',
//Explanation: Chrome 32.0.1700.107 on Windows 7. String from Ingmar Lambrechtse - Thanks.
        'Chrome30' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',
//Explanation: Chrome 30.0.1599.101 on Windows XP. String from süleyman s?rr? aybar - Thanks.
        'Chrome30a' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',
//Explanation: Chrome 30.0.1599.101 (32-bit) on Windows 7. String from Us - Thanks - you are welcome.
        'Chrome29' => 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36',
//Explanation: Chrome 29.0.1547.57 (32-bit) on Windows 8. String from Ivan Yeung - Thanks.
        'Chrome28' => 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36',
//Explanation: Chrome 28.0.1500.71 (32-bit) on Windows 8. String from Luis Larrea - Thanks.
        'Chrome27' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36',
//Explanation: Chrome 27.0.1453.94 (32-bit) on Windows 7 (64-bit). String from Us - Thanks - you are welcome.
        'Chrome26' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31',
//Explanation: Chrome 26.0.1410.64 on Windows 7. String from Rob Lyons - Thanks.
        'Chrome26a' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_3) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31',
//Explanation: Chrome 26.0.1410 on iMac under OS X 10.8.3. String from Bob Walsh - Thanks.
        'Chrome25' => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.22 (KHTML, like Gecko) Ubuntu Chromium/25.0.1364.160 Chrome/25.0.1364.160 Safari/537.22',
//Explanation: Chrome 25.0.1364.160 on Ubuntu 10.04 (Streaky Bacon or whatever its code name is). String from Frank Hum - Thanks.
        'Chrome25a' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22',
//Explanation: Chrome 25.0.1364 on Windows XP. String from Thu Win - Thanks.
        'Chrome24' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',
//Explanation: Chrome 24.0.1312 on Windows 7. String from Elisa Ingra - Thanks.
        'Chrome23' => 'Mozilla/5.0 (X11; CrOS armv7l 2913.260.0) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.99 Safari/537.11',
//Explanation: ChromeOS on a Samsung 303C Chromebook. String from Jonathan McCormack - Thanks.
        'Chrome26b' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.27 (KHTML, like Gecko) Chrome/26.0.1389.0 Safari/537.27',
//Explanation: Chromium 26.0.1389.0 on Ub untu 12.10. String from Saikrishna Arcot - Thanks.
        'Chrome24a' => 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.12 (KHTML, like Gecko) Chrome/24.0.1273.0 Safari/537.12',
//Explanation: Google Chrome 24.0.1273.0 (nightly build) on Windows 8. String from Maverik Gately - Thanks.
        'Chrome23a' => ' Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11',
//Explanation: Google Chrome 23.0.1271.95 on Windows 7. String from Ed Baxter - Thanks.
        'Chrome22' => ' Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4',
//Explanation: Google Chrome 22.0.1229.79 on Windows 7. String from Jan van Beukering - Thanks.
        'Chrome20' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11',
//Explanation: Google Chrome 20.0.1132.57 on OS 10.7 (Lion). String from Nicole Radman - Thanks.
        'Chrome21' => 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1',
//Explanation: Google Chrome 21.0.1180.75 on Windows Vista. String from Alexander Codrutzenberg - Thanks.
        'Chrome20a' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11',
//Explanation: Google Chrome 20.0.1132.47 on OSX 10.6.8. String from Rohit Iyer - Thanks.
        'Chrome20b' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11',
//Explanation: Google Chrome 20.0.1132.47 on Win 7. String from anonymous - Thanks.
        'Chrome19' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5',
//Explanation: Google Chrome 19.0.1084.56 on XP. String from Thu Win - Thanks.
        'Chrome19a' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5',
//Explanation: Google Chrome 19.0.1084.56 on OSX 10.7.2. String from Daniel Gardner - Thanks.
        'Chrome19b' => 'Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5',
//Explanation: Google Chrome 19.0.1084.52 on Windows Vista. String from Nimrod Flores - Thanks.
        'Chrome19c' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5',
//Explanation: Google Chrome 19.0.1084.52 on Windows 7. String from asdf (well you know who you are), Bruce Axtens - Thanks.
        'Chrome18' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19',
//Explanation: Google Chrome 18.0.1025.168 on Linux. String from David Cassidy - Thanks.
        'Chrome18a' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.152 Safari/535.19',
//Explanation: Google Chrome 18.0.1025.152 on Windows 7. String from Momo Qiu - Thanks.
        'Chrome17' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11',
//Explanation: Google Chrome 17.0.963.79 on Apple Mac. String from Ivan Wang - Thanks.
        'Chrome17a' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.66 Safari/535.11',
//Explanation: Google Chrome 17.0.963.66 on Windows XP - release version - see note below. String from Thu Win - Thanks.
        'Chrome17b' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.0 Safari/535.11',
//Explanation: Google Chrome 17.0.963.0 on Windows 7. From the development channel. Not yet (Dec 2011) released. String from Johannes Mißbach - Thanks.
        'Chrome16' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7',
//Explanation: Google Chrome 16.0.912.77 on Windows 7. String Paulita Abou-Aly from - Thanks.
        'Chrome16a' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7',
//Explanation: Google Chrome 16.0.912.75 on Windows 7. String Paulita Abou-Aly from - Thanks.
        'Chrome16c' => 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.63 Safari/535.7',
//Explanation: Google Chrome 16.0.912.63 on Windows Vista. Strings from Athena Snell (also from Thu Win) - Thanks.
        'Chrome15' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2',
//Explanation: Google Chrome 15.0.874.121 on Windows XP. String from Paul H, Michael Lykke, Dennis Luken and Sisi Santos - Thanks.
        'Chrome14' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1',
//Explanation: Google Chrome 14.0.835.202 on Windows 7. String from Alexandre Fernades - Thanks.
        'Chrome14a' => 'Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.186 Safari/535.1',
//Explanation: Google Chrome 14.0.835.186 on Windows Vista 64. String from Raphael diSanto and Shelly Evans - Thanks.
        'Chrome14b' => 'Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.126 Safari/535.1',
//Explanation: Google Chrome 14.0.835.126 on Windows Vista 64. String from Will Fuhrman - Thanks.
        'Chrome13' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_3) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.220 Safari/535.1',
//Explanation: Google Chrome 13.0.782.220 on iThingy. String from Harish Yagain - Thanks.
        'Chrome13a' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.220 Safari/535.1',
//Explanation: Google Chrome 13.0.782.220 on Windows XP. String from Luis Castillo - Thanks.
        'Chrome13c' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.215 Safari/535.1',
//Explanation: Google Chrome 13.0.782.215 on Windows 7. String from Michele Trovero - Thanks.
        'Chrome12' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.60 Safari/534.30',
//Explanation: Google Chrome 12.0.742.60 on Windows 7. String from M C (right) - Thanks.
        'Chrome11' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24',
//Explanation: Google Chrome 11.0.696.71 on Windows XP. String from Melody Barrueta - Thanks.
        'Chrome11a' => 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.60 Safari/534.24',
//Explanation: Google Chrome 11.0.696.60 on Windows Vista Ultimate. String from Garman Liu - Thanks.
        'Chrome11b' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.57 Safari/534.24',
//Explanation: Google Chrome 11.0.696.57 on Windows XP. String from Qui Cheng - Thanks.
        'Chrome10' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.205 Safari/534.16',
//Explanation: Google Chrome 10.0.648.205 on Windows XP. String from Thu Win - Thanks.
        'Chrome10a' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.204 Safari/534.16',
//Explanation: Google Chrome 10.0.648.204 on Windows 7 running on MacBook Air 13 runing Parallels virtual machine software - phew. String from Trotter Hardy - Thanks.
        'Chrome10b' => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.133 Safari/534.16',
//Explanation: Google Chrome 10.0.648.133 on Windows Vista (unlucky for some). String from Diego Dahm - Thanks.
        'Chrome10c' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.133 Safari/534.16',
//Explanation: Google Chrome 10.0.648.133 on MacBook under X 10.6.6. String from Skip M - Thanks.
        'Chrome10d' => 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.127 Safari/534.16',
//Explanation: Google Chrome 10.0.648.127 on Fedora 14. String from David Strickland - Thanks.
        'Chrome9' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.98 Safari/534.13',
//Explanation: Google Chrome 9.0.597.98 on Windows XP. String from Adriano Cavalcante - Thanks.
        'Chrome8' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.237 Safari/534.10',
//Explanation: Google Chrome 8.0.552.237 on Windows Vista. String from Scott Stanlick - Thanks.
        'Chrome6' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.63 Safari/534.3',
//Explanation: Google Chrome 6.0.472.63 on Windows XP. String from Harold Bratcher - Thanks.
        'Chrome5' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.127 Safari/533.4',
//Explanation: Google Chrome 5.0.375.127 on Mac OS X. String from Matt Greek - thanks.
        'Chrome5a' => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4',
//Explanation: Google Chrome 5.0.375.125 on Windows 7 (x64). String from Lucas Ze - thanks.
        'Chrome5b' => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4',
//Explanation: Google Chrome 5.0.375.99 on Windows Vista Home Premium with SP1. String from David Boteen and Lucas Ze and Aaron- thanks.
        'Chrome5c' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.99 Safari/533.4',
//Explanation: Google Chrome 5.0.375.99 on Windows XP. String from Giovanni Rimoli - thanks.
        'Chrome5d' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.1 (KHTML, like Gecko) Chrome/5.0.322.2 Safari/533.1',
//Explanation: Google Chrome 5.0.322.2 (my what a lot of dots in the release version) on Windows 7. String from Arijit Banerjee - thanks.
        'Chrome5e' => ' Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Chrome/5.0.307.9 Safari/532.9',
//Explanation: Google Chrome 5.0.307.9 on Linux Gentoo - custom build for use with Squid. String from Stephen Christman - thanks.
        'Chrome5f' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Chrome/5.0.307.11 Safari/532.9',
//Explanation: Google Chrome 5.0.307 on Apple MacBook Pro. String from Jim Dukarm - thanks.
        'Chrome4' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.5 (KHTML, like Gecko) Chrome/4.0.249.78 Safari/532.5',
//Explanation: Google Chrome 4.0.249 running on Windows 7 (64 bits - trust us on this one). String from Thu Win - thanks.
        'Chrome4a' => 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/532.4 (KHTML, like Gecko) Chrome/4.0.233.0 Safari/532.4',
//Explanation: Chromium 4.0.233.0 running on Ubuntu build 30813. String from Ron Rossman - thanks.
        'Chrome3' => 'Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.198.0 Safari/532.0',
//Explanation: Chromium 3.0.198.0 (22605) running on Ubuntu 9.10 (Karmic). String from Barry van Oudtshoom - thanks.
        'Chrome3a' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; Valve Steam GameOverlay; ) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/3.0.195.24 Safari/532.1',
//Explanation: Chrome 3.0.195.24 running on Windows 7. String from Matthew Green - thanks.
        'Chrome3b' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13',
//Explanation: Chromium Dev. Build 21 (CrossOver RPM) on SUSE 10. String from Ted King - thanks.
        'Chrome3c' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.33 Safari/532.0',
//Explanation: Chrome 3.0.195.33 running on Windows XP SP423 (just kidding - it is SP3). String from Ross Ritchey - thanks.
        'Chrome1' => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19',
        'Chrome3d' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; en-US) AppleWebKit/531.0 (KHTML, like Gecko) Chrome/3.0.183 Safari/531.0',
//Explanation: Chrome on OS X (pre-release). String from Bryce Cogswell - thanks.
        'Chrome1a' => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19',
//Explanation: Chrome on Windows Vista Ultimate. String from Percy McCoy - thanks.
        'Chrome1b' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.36 Safari/525.19',
//Explanation: First Google Chrome out of beta (12/13/2008). String from Jake Wasdin - thanks.
        'Chrome02' => 'Mozilla/5.0 (Linux; U; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13',
//Explanation: Deyan writes 'Google Chromium Test Shell for Linux (on openSUSE 11.1), compiled from source. This is a minimal GUI shell to test Chromium's rendering on Linux. Right clicking a page gives no result, there is no top menu, plugins don't seem to work yet, some web sites cause problems, but it runs well enough for example to read Gmail or to post this message. Instructions are available at http://code.google.com/p/chromium/wiki/LinuxBuildInstructions'. String from Deyan Mavrov - thanks.
        'Chrome3.1' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13',
//Explanation: Cross-over Chromium - and just how do we know that - well Jake told us is how (apparently the help/about screen gives the real skinny). String from Jake Wasdin - thanks.
        'Chrome02a' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13',
//Explanation: Google Chrome browser in Beta release. This string is on XP but we also got it on Vista as well. String from Dirk Heyne, Alex Williams, Jake Wasdin, Jonathan McCormack, Don Hansen, Jeremey Jarratt, Carl Karlsson - thanks.
// -----------------------------------------------------------------------------------------------------------------------------
            /* ChromePlus

              Chrome clone (gotta love the alliteration) with a number of extra features. Seems to be windows only at this time. Also a number of the user tracking features have either been removed or are now configurable. Browse in Peace.

              Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.122 Safari/534.30 ChromePlus/1.6.3.1

              Explanation: Google ChromePlus 1.6.3.1 browser on Windows 7 Professional (64 bit). String from Brad Johnson - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Comodo Dragon

              All the way from the Galápagos Islands. Well almost. A browser based on chromium with enhanced security features. Wait a minute, was that not what chromium boasted about.

              Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.5 (KHTML, like Gecko) Comodo_Dragon/4.0.1.6 Chrome/4.0.249.78 Safari/532.5

              Explanation: Comodo Dragon on XP. String from Hashan Gayasri - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Contiki

              Browser claimed to be on the oldest hardware ever to browse the web - unless you know better that is! Browser for the venerable Commodore 64.

              Contiki/1.0 (Commodore 64; http://dunkels.com/adam/contiki/)

              Explanation: Contiki - the original traveled across the Atlantic (or was that Kontiki) - this one travels the internet. String from Erik Inge Bolsø - thanks.

              Contiki/1.0 (Commodore 64; http://dunkels.com/adam/contiki/)

              Explanation: Contiki (a very small footprint Open Source OS) with built in browser which even tells you where to get it - is that helpful or what! From Ryan Jones - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              cURL

              cURL (yeah that's the way they want it spelled) is a command line tool (similar to wget) for accessing web based stuff. Runs on Linux, BSD and MAC OS X and Windows under MinGW (GNU for Windows).

              curl/7.19.5 (i586-pc-mingw32msvc) libcurl/7.19.5 OpenSSL/0.9.8l zlib/1.2.3

              Explanation: cURL 7.19.5 on a Windows under MinGW. String from Aaron - thanks.

              curl/7.7.2 (powerpc-apple-darwin6.0) libcurl 7.7.2 (OpenSSL 0.9.6b)

              Explanation: The current version of cURL is 7.10.5 (built on libcurl). On a Mac OS X 10.2.6 system, with Darwin kernel version 6.6. String from Stephen Paulsen - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Democracy

              Not strictly a browser but a TV viewer developed by the not-for-profit participatoryculture.org who are dedicated (according to their web site and we have reason to doubt them) to keeping internet TV free (open source) and open (standards based). Project anticipates developing the viewer, a movie sharing service, a channel guide and a method of for RSS distribution for video. Pretty comprehensive. Runs on Windows.

              Democracy/0.8.1 (http://www.participatoryculture.org)

              Explanation: First in-the-wild sighting for this new TV viewer. String from Jeremy Hannon - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Dillo

              A very (like < 500K dowload) very lightweight FLTK-based browser. Seems to run only on *nix (and the out-of-box DragonflyBSD and DSL - Damn Small Linux - choice). Now five years old (in 2005). Great stuff - keep rocking. We like to give 'em a hard time over their lack of Moz info in the string but since there is nothing else in the string it does seem a little unfair (but see note below).

              Dillo/3.0.4.1

              Explanation: Dillo 3.0.4.1 running on FreeBSD (Hurrah!). String from Anon User (ok. ok, you don't trust us) - thanks.

              Dillo/3.0.2

              Explanation: Dillo 3.0.2 running on something, somewhere. String from Zaniyah - thanks.

              Mozilla/4.0 (compatible; Dillo 2.2)

              Explanation: Dillo 2.2 running on - wait for it - Windows XP SP3 (available from sourceforge. And with Mozilla in the string. We almost had a heart attack. String from Benjamin Johnson (and he should know since he's the developer) - thanks.

              Dillo/2.2

              Explanation: Dillo 2.2 running under Puppy Linux 4.3 (not that you would pick that up from the string!) This version includes CSS support and tabs and finally renders the zytrax site - perfectly. Always the sign of a great browser we think. String from Jake Wasdin - thanks.

              Dillo/0.8.6

              Explanation: Throwback to the old long string days - on SUSE 10. But how do they manage to pack so much info into such a short string? String from Ted King - thanks.

              Dillo/2.0

              Explanation: From the acknowledged world leaders in short Browser strings comes this nosebleedingly long version 2.0 string (on Mandriva 2008.1). String from Jake Wasdin - thanks.

              Dillo/0.8.5-i18n-misc

              Explanation: This one from DSL linux distro with QEMU (an emulator which has also been ported to windows). String from anonymous - thanks anyway.

              Dillo/0.8.5-pre

              Explanation: Extra long and informative string from latest version of Dillo. String from Andrew Preater - thanks.

              Dillo/0.8.3

              Explanation: Dillo under Mandrake 10.1 with kernel 2.6.7. As Andrew remarked "These Dillo strings don't get any more exciting, do they? Still a nice light-and-fast browser though." String from Andrew Preater - thanks.

              Dillo/0.8.2

              Explanation: Dillo under NetBSD on Transmeta Crusoe (and just how do we know that - 'cos Alex told us that's how). String from Alex Poylisher - thanks.

              Dillo/0.8.2

              Explanation: Dillo on Linux Mandrake 10.0 (with a 2.6 kernel - gotta read between the chars!). String from Andrew Preater - thanks.

              Dillo/0.6.6

              Explanation: Dillo 0.6.6 on ?. Dumb lack of Mozilla compatibility version see our rants.
              // -----------------------------------------------------------------------------------------------------------------------------
              DocZilla

              Proprietary SGML and XML parsers built on top of the standard Gecko engine. Win32 only. Theory is you can directly view HTML, SGML and XML pages. Clever or what. Free non-commercial use otherwise they stiff you for money - the devils!.

              DocZilla/1.0 (Windows; U; WinNT4.0; en-US; rv:1.0.0) Gecko/20020804

              Explanation: DocZilla 1.0 RC1. No Mozilla compatability number - dumb. Could find no menu option to change the string.
              // -----------------------------------------------------------------------------------------------------------------------------
              edbrowse

              Text mode browser, plus editor plus mail client targetted at blind users - for *nix systems.

              edbrowse/2.2.10

              Explanation: edbrowse on something! String from Erik Inge Bolsø - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Elinks

              Enhanced Links - a development fork - text only browser. There are a lot of folks who (a) only want the data - forget all the fancy graphic stuff and (b) are visually challenged and need control.

              ELinks/0.10.4-7.1-debian (textmode; Linux 2.4.27-amiga m68k; 80x32-3)

              Explanation: Elinks on Debian. String from Lex Landa - thanks.

              ELinks/0.12~pre2.dfsg0-1ubuntu1-lite (textmode; Debian; Linux 2.6.32-4-jolicloud i686; 143x37-2)

              Explanation: Elinks Light on JoliCloud (netbook OS based on Ubuntu which is based on Debian - go figure). String from Jake Wasdin - thanks.

              ELinks/0.12pre5.GIT (textmode; CYGWIN_NT-6.1 1.7.1(0.218/5/3) i686; 80x24-2)

              Explanation: ELinks-lite 0.12pre5 under CYWIN on Windows 7 (honest). String from Jake Wasdin - thanks.

              ELinks/0.11.3-5ubuntu2-lite (textmode; Debian; Linux 2.6.24-19-generic i686; 126x37-2)

              Explanation: ELinks-lite 0.11.3 (interesting concept - a lite version of a text browser) on Xubuntu Hardy. String from Jake Wasdin - thanks.

              ELinks/0.11.4-2 (textmode; Debian; GNU/kFreeBSD 6.3-1-486 i686; 141x21-2)

              Explanation: ELinks 0.11.4 on Debian/kFreeBSD. String from Jake Wasdin - thanks.

              ELinks (0.4.3; NetBSD 3.0.2_PATCH sparc64; 141x19)

              Explanation: ELinks 0.4.3 on www.freeeshells.ch a service providing free unix shell account access under NETBSD. String from Jake Wasdin - thanks.

              ELinks/0.10.4-7ubuntu1-debian (textmode; Linux 2.6.12-10-k7-smp i686; 80x24-2)

              Explanation: ELinks 0.10.4 on ubuntu oldstable, linux 2.6 k7 SMP, standard terminal. The last digits are terminal window size (July 2006). String from Lucas Lommer - thanks.

              ELinks/0.10.5 (textmode; CYGWIN_NT-5.0 1.5.18(0.132/4/2) i686; 143x51-2)

              Explanation: Elinks 0.10.5 on Windows 2000 (SP4) using CYGWIN. Apparently with full CSS, javascript and frames support. The last digits are terminal window size. String from Andrew Preater - thanks.

              ELinks (0.4.2; Linux; )

              Explanation: Elinks 0.4.2 (older version - latest is 0.9.x). String from Erik Inge Bolsø - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Emacs/w3s

              Super new eww browser with emacs (we're speaking as vi users here so believe it if you want). And this is the now the world's short (and least descriptive) browser string.

              URL/Emacs

              Explanation: That's it. The new eww browser with emacs24+. String from Jake Wasdin - thanks.

              Emacs-W3/4.0pre.46 URL/p4.0pre.46 (i686-pc-linux; X11)

              Explanation: Emacs/W3 on X-windows Linux. String from Erik Inge Bolsø - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Epiphany

              Another lightweight GNOME-based browser now with either a Firefox (Gecko) or a webkit back-end. *nix's only.
              Epiphany 3.x

              Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.22+ (KHTML, like Gecko) Chromium/17.0.963.56 Chrome/17.0.963.56 Safari/535.22+ Debian/7.0 (3.4.2-2.1) Epiphany/3.4.2

              Explanation: Epiphany 3.4.2 - the WebKit version - on a debia 7.x base. String from Vlatko Georgievski - thanks
              Epiphany 2.x

              Mozilla/5.0 (X11; U; Linux i686; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Safari/531.2+ Epiphany/2.29.5

              Explanation: Epiphany 2.29.5 - the WebKit version - on HP Pavilion ze5607wm running Sidux Linux with Gnome display manager and XFCE4 window manager. String from Kevin Miller - thanks

              Mozilla/5.0 (X11; U; Linux i686; en; rv:1.9.0.11) Gecko/20080528 Epiphany/2.22 Firefox/3.0

              Explanation: Epiphany 2.22 - the Gecko version - on Ubuntu Jaunty Jackalope 9.04. String from Jake Wasdin - thanks

              Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/420+ (KHTML, like Gecko)

              Explanation: Epiphany 2.20.1 (webkit version) on Xubuntu Hardy. Very early version - no Epiphany identification string which probably reflects the early release status. String from Jake Wasdin - thanks

              Mozilla/5.0 (X11; U; Linux x86_64; c) AppleWebKit/525.1+ (KHTML, like Gecko, Safari/525.1+) epiphany

              Explanation: Epiphany 2.22 (?) on Gentoo for x86_64. They appear to be no longer describing the release number in the string - strange. String from Billy Dorminy - thanks
              Epiphany Pre 2.x

              Mozilla/5.0 (X11; U; Linux x86_64; en; rv:1.8.1.4) Gecko/20061201 Epiphany/2.18 Firefox/2.0.0.4 (Ubuntu-feisty)

              Explanation: Epiphany 2.18.1 on AMD Turion 64 on Ubuntu Linux Feisty Fawn. String from Jon J - thanks

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.5) Gecko/20060731 Epiphany/2.14 Firefox/1.5.0.5

              Explanation: Epiphany 2.14 on Gentoo LiveCD on a very modestly powered PC. String from Richard Steiner - thanks

              Mozilla/5.0 (X11; U; Linux i686; en; rv:1.8.1) Gecko/20061203 Epiphany/2.16 Firefox/2.0

              Explanation: Epiphany 2.16 on a Linux distro of some sort. String from Patrick Ohearn - thanks
              Epiphany 1.x

              Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.8.0.1) Gecko/Debian-1.8.0.1-5 Epiphany/1.8.5

              Explanation: Epiphany 1.8.5 on Debian on AMD 64 bit machine. String from Andrew Preater - thanks

              Mozilla/5.0 (X11; U; Linux i686; cs-CZ; rv:1.7.13) Gecko/20060418 Epiphany/1.8.2 (Ubuntu) (Ubuntu package 1.0.8)

              Explanation: Epiphany 1.8.2 on Ubuntu oldstable, linux 2.6 K7 SMP. String from Lucas Lommer - thanks

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.3) Gecko/20040924 Epiphany/1.4.4 (Ubuntu)

              Explanation: Epiphany 1.4.4 on (Ubuntu a new 'Linux for Human Beings' distro. Current release is called Hoary Hedgehog but wait for it - the previous one was called Warty Warthog - we talking catchy or what - are these guys in alliteration big time. String from Dave Wood - thanks

              Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.7) Gecko/20040628 Epiphany/1.2.6

              Explanation: Epiphany 1.2.6 on FreeBSD (Hurrah). String from Edwin Chambers - thanks

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4.1) Gecko/20031030 Epiphany/1.0.8

              Explanation: Epiphany 1.0.8 on Red Hat Enterprise Linux AS release 3. String from Frank Toth - thanks

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4.1) Gecko/20031114 Epiphany/1.0.4

              Explanation: Epiphany 1.0.4 on Linux. String from Eric Bowman - thanks
              Epiphany Pre 1.x

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4) Gecko/20030704 Epiphany/0.9.2

              Explanation: Epiphany 0.9.2 on Linux. String from Adam Hauner - thanks

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4) Gecko/20030703 Epiphany/0.8.4

              Explanation: Epiphany version 0.8.4 on Linux. (String from Adam Hauner - thanks)
              // -----------------------------------------------------------------------------------------------------------------------------
              fetch

              Not strictly a browser - this is the tool used by FreeBSD to download software when you do that automagical 'make install clean' in the ports collection. Great system - we love it to death.

              fetch libfetch/2.0

              Explanation: FreeBSD's 'ports collection' download tool. Should only appear on FTP sites. String from Steven Heumann - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Firefox

              Phoenix is dead - long live Firebird. Firebird is dead - long live Firefox. Firebird/Phoenix has morph'd into Firefox. The Mozilla roadmap shows Firefox as the browser for the next generation of Mozilla. Essentially (if we can paraphrase a very big page) the mozilla design team are saying that they will develop a set of components (browser, mail client, editor (composer) and others) which will work well together but will have their own development priorities and timeframes, rather than the current monolithic Mozilla structure. The historic Phoenix and Firebird strings are still on the main page 'cos we like to remind 'em of their humble beginnings. Note: For Firefox 3 and perhaps after, Minefield will remain the development version and always have 'pre' in the string, whereas GranParadiso is the release leg and will not have 'pre' in the string - so there you go. Shiretoko named for the Japanese Peninsula and a UN world heritage site (aren't we the smart ones) seems to be the development release leg for 3.5 - replacing GranParadiso? While Namoroka (a unique nature reserve in northeastern Madagascar) seems to be the 3.6 development release leg.

              The 'mobile Firefox' is being developed under the current codename of Fennec (a small noctunal fox found in the Sahara desert) - man, who comes up with these names. But don't worry they'll change the name 427 times before release. Multi-platform - Linux and Windows Mobile so far.

              Don't forget the Mozilla SeaMonkey project about which which J. Reynolds writes:

              The SeaMonkey project is a community effort to deliver production-quality releases of code derived from the application formerly known as "Mozilla Application Suite". Whereas the main focus of the Mozilla Foundation is on Mozilla Firefox and Mozilla Thunderbird, our group of dedicated volunteers works to ensure that you can have "everything but the kitchen sink" and have it stable enough for corporate use.

              Note: The seamonkey strings are under Mozilla.

              Nathan Lineback supplied this reference to a bunch of screen shots showing the OS's to which Firefox (1.5 mostly) has been ported.

              Windows FF users may want to check out palemoon which claims to be up to 25% faster than the moz port by optimizing the FF code base for Windows.

              This represents the current and major historic release versions of the strings - well in our unbalanced judgment. We maintain a separate page with a complete list of Firefox strings that we have ever found or received (including some really bizarre stuff) - includes those on this page.
              Firefox OS with Firefox Browser

              Mozilla/5.0 (Mobile; ZTEOPEN; rv:18.1) Gecko/18.1 Firefox/18.1

              Explanation:Firefox 18.1 on ZTE Open running Firefox OS. String from Jonathan McCormack - thanks.
              Curious and Clones (or even Curious Clones)

              Mostly not supported by Mozilla

              Mozilla/5.0 (Windows NT 5.1; rv:2.0) Gecko/20100101 Firefox/4.0

              Explanation: Firefox Portable (FF/4) on XP (optimized for USB load). String from Thu Win - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4 (Splashtop-v1.0.5.0)

              Explanation:Firefox (3.0.4) branded for the HP splashtop platform. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8) Gecko/20051111 Firefox/1.5 BAVM/1.0.0

              Explanation: The UA string from Firefox inside VMWare's Browser Appliance. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8

              Explanation: No idea what to do with this one since it's the UA string from Thunderbird nightly build of Version 3 (codenamed Shredder) with an embedded browser add-in called ThunderBrowse. String from Jake Wasdin - thanks.
              Mobile Versions

              Mozilla/5.0 (X11; U; Linux armv61; en-US; rv:1.9.1b2pre) Gecko/20081015 Fennec/1.0a1

              Explanation: Alpha version of Mozilla Fennec (mobile Firefox) on Nokia N800. String from Jake Wasdin - thanks.
              Development Versions

              Mozilla/5.0 (Windows NT 7.0; Win64; x64; rv:3.0b2pre) Gecko/20110203 Firefox/4.0b12pre

              Explanation: Development version of FF4 running on Windows 8. String from Shawn Long - thanks.
              Releases - v41 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0

              Explanation: Firefox 41 on Windows 7. String from us - thanks - you're welcome.
              Releases - v39 Series

              Mozilla/5.0 (Windows NT 6.1; rv:39.0) Gecko/20100101 Firefox/39.0

              Explanation: Firefox 38 on Windows 7. String from Heather Benjamin - thanks.
              Releases - v38 Series

              Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0

              Explanation: Firefox 38 on Windows 8.1. String from Harvey Payne - thanks.
              Releases - v37 Series

              Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:37.0) Gecko/20100101 Firefox/37.0

              Explanation: Firefox 37 on Ubuntu (crazy-as-fox or whatever version name it is). String from Pat Crabb - thanks.
              Releases - v32 Series

              Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:32.0) Gecko/20100101 Firefox/32.0

              Explanation: Firefox 32 on 12.04. String from Frank Hum - thanks.

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0

              Explanation: Firefox 32 on 12.04. String from Annemarie du Berger - thanks.
              Releases - v31 Series

              Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:31.0) Gecko/20100101 Firefox/31.0

              Explanation: Firefox 31 on OS X 10.9.4 (gotta look between the dots for the OS version number. String from Mark V (yeah, right) - but thanks whoever you are.
              Releases - v25 Series

              Mozilla/5.0 (Windows NT 6.2; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0

              Explanation: Firefox 25 (32-bit) on Window 8 (64-bit). String from teste testing (yeah, right) - but thanks whoever you are.
              Releases - v24 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0

              Explanation: Firefox 24 (32-bit) on Window 7 (64-bit). String from Us - thanks - you're welcome.
              Releases - v21 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0

              Explanation: Firefox 21 (32-bit) on Window 7 (64-bit). String from Us - thanks - you're welcome.
              Releases - v20 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0

              Explanation: Firefox 20 on Window 7. String from Us - thanks - you're welcome.
              Releases - v19 Series

              Mozilla/5.0 (X11; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0

              Explanation: Firefox 19 on 64 bit Linux distribution. String from Deepak Pareek - thanks.
              Releases - v18 Series

              Mozilla/5.0 (X11; NetBSD amd64; rv:18.0) Gecko/20130120 Firefox/18.0

              Explanation: Firefox 18 on NetBSD 6.99.16. String from Alex Poylisher - thanks.
              Releases - v17 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0

              Explanation: Firefox 17 on Windows 7. String from Maciej Kowalczyk - thanks.
              Releases - v16 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0

              Explanation: Firefox 16 on Windows 7 (SP1). String from Emily Jackson - thanks.
              Releases - v15 Series

              Mozilla/5.0 (Windows NT 5.1; rv:15.0) Gecko/20100101 Firefox/15.0.1

              Explanation: Firefox 15 on Windows XP. String from Enrique Fernandez Lopez - thanks.
              Releases - v14 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:14.0) Gecko/20100101 Firefox/14.0.1

              Explanation: Firefox 14.0.1 on Windows Windows 7. String from G Diddit - thanks.
              Releases - v13 Series

              Mozilla/5.0 (Windows NT 5.1; rv:13.0) Gecko/20100101 Firefox/13.0

              Explanation: Firefox 13 on Windows XP. String from Vas Vaskel, Simon Finch, Gregg Luhring - thanks.
              Releases - v12 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0

              Explanation: Firefox 12 on Windows 7. String from Bigo Aybudako - thanks.
              Releases - v11 Series

              Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0

              Explanation: Firefox 11 on Windows 7. String from Shane Turner, Richard Wickens, Don Emmons and Daniel Beltran - thanks.
              Releases - v10 Series

              Mozilla/5.0 (Macintosh; PPC Mac OS X 10.4; rv:10.0.10) Gecko/20121024 Firefox/10.0.10 TenFourFox/G3

              Explanation: Firefox 10 on MAC PowerBook (from the TenFourFox project - bless 'em!). String from Justin Parsin - thanks.
              Releases - v9 Series

              Mozilla/5.0 (Ubuntu; X11; Linux x86_64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1

              Explanation: Firefox 9 on Ubuntu 11.04. String from Arnold Schiller - thanks.
              Releases - v8 Series

              Mozilla/5.0 (Windows NT 5.1; rv:8.0) Gecko/20100101 Firefox/8.0

              Explanation: Firefox 8 on XP. String from Anonymous - but thanks anyway.
              Releases - v7 Series

              Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1

              Explanation: Firefox 7 on XP Pro with SP3. String from Bruce Le Bane - thanks.
              Releases - v6 Series

              Mozilla/5.0 (X11; Linux i686; rv:6.0.2) Gecko/20100101 Firefox/6.0.2

              Explanation: Firefox 6 on some Linux distribution. String from P Curtis - thanks.
              Releases - v5 Series

              Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0

              Explanation: Firefox 5 on Windows 7 64-bit. String from Florian Nourrisse - thanks.
              Releases - v4 Series

              Mozilla/5.0 (X11; Linux x86_64; rv:2.0.1) Gecko/20110506 Firefox/4.0.1

              Explanation: Firefox 4 on Linux 64 bit mode. String from Shawn Long - thanks.
              Releases - v3.6 Series

              Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16

              Explanation: Firefox 3.6.16 on Windows 7. String from Ravelino Dsouza - thanks.
              Releases - v3.5 Series

              Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1.8) Gecko/20100215 Solaris/10.1 (GNU) Superswan/3.5.8 (Byte/me)

              Explanation: Firefox 3.5.8 on Solaris. Superswan seems to be a IPSEC client - or something. String from Daniel McDicken - thanks.
              Releases - v3.0 Series

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.5; en-US; rv:1.9.0.3) Gecko/2008092414 Firefox/3.0.3

              Explanation: Firefox 3.0.3 on Mac OSX using PPC. String from David Charlap - thanks.
              Releases - v2 Series

              Mozilla/5.0 (X11; U; OpenBSD i386; en-US; rv:1.8.1.14) Gecko/20080821 Firefox/2.0.0.14

              Explanation: Firefox 2.0.0.14 on BSD Anywhere 4.3 (OpenBSD live CD). String from Jake Wasdin - thanks.
              Releases - v1.5 Series

              Mozilla/5.0 (X11; U; Darwin Power Macintosh; en-US; rv:1.8.0.12) Gecko/20070803 Firefox/1.5.0.12 Fink Community Edition

              Explanation: Firefox 1.5.0.12 on Darwin 8.10.0 with with the Fink Community version. String from Tyler Stobbe - thanks.
              Releases - v1 Series

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.13) Gecko/20060410 Firefox/1.0.8

              Explanation: Firefox 1.0.8 on XP as part of the portableapps suite. String from Rafael Holt - thanks.
              Releases - Pre v1 (and Firebird and Phoenix)

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.3) Gecko/20041002 Firefox/0.10.1

              Explanation: Latest Win-32 Firefox on XP Pro. This is the one that fixes the security problem. String from Anders Pedersen - thanks.

              Mozilla/5.0 (X11; U; SunOS sun4m; en-US; rv:1.4b) Gecko/20030517 Mozilla Firebird/0.6

              Explanation: Firebird 0.6 running on a SPARCstation 20 (note sun4m) under Solaris 8. String from Michael Doyle - thanks.

              Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.3a) Gecko/20021207 Phoenix/0.5

              Explanation: Phoenix (Mozilla lite) version 0.5 on Windows NT 4.0

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.2b) Gecko/20020923 Phoenix/0.1

              Explanation: Phoenix (Mozilla lite) version 0.1 on Windows XP
              // -----------------------------------------------------------------------------------------------------------------------------
              Flock

              New Firefox based clone - with lots of added stuff (don't you just love our depth of understanding) including RSS feeds, blogging tools and others. Runs on MAC, Win and *nix's. The web site, and its brothers and sisters, are interesting in themselves. BTW: make sure you are not tired when you pronounce this one.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008100716 Firefox/3.0.3 Flock/2.0

              Explanation: Flock 2.0 on Linux. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.4) Gecko/20060612 Firefox/1.5.0.4 Flock/0.7.0.17.1

              Explanation: Flock Beta 1 (0.7) on Linux. String from Asbjørn Pedersen - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8b5) Gecko/20051019 Flock/0.4 Firefox/1.0+

              Explanation: First beta release of the flock code base on Win XP. String from Johnathan McCormack - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Galeon

              A lightweight GNOME-based browser built on top of the Mozilla rendering engine. *nix's only

              Mozilla/5.0 (X11; U; OpenBSD i386; en-US; rv:1.8.1.19) Gecko/20090701 Galeon/2.0.7

              Explanation: Galeon 2.0.7 Under OpenBSD 4.6 i386. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.13) Gecko/20080208 Galeon/2.0.4 (2008.1) Firefox/2.0.0.13

              Explanation: Galeon 2.0.4 Mandriva Linux 2008.1. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.8.1.4) Gecko/20061201 Galeon/2.0.2 (Ubuntu package 2.0.2-4ubuntu1)

              Explanation: Galeon 2.0.2 on AMD Turion 64 on Ubuntu Linux "Feisty Fawn". Now is that a string or what. You could wait days on a cell phone for this one to arrive. String from Jon J - thanks.

              Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.7.12) Gecko/20051105 Galeon/1.3.21

              Explanation: Galeon 1.3.21 on FreeBSD 5.4 on the Intel platform. String from Edwin Chambers - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.3) Gecko/20040913 Galeon/1.3.18

              Explanation: Galeon 1.3.18 on Mandrake Linux 10.1 with kernel 2.6.7 Intel platform. String from Andrew Praeter - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.3) Gecko/20041007 Galeon/1.3.17 (Debian package 1.3.17-2)

              Explanation: Galeon 1.3.17 on Debian (not we think 1.3.17!) Intel platform. String from Liam Morland - thanks.

              Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.6) Gecko/20040406 Galeon/1.3.15

              Explanation: Galeon 1.3.15 on FreeBSD on Intel platform. String from Edwin Chanbers - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.6) Gecko/20040115 Galeon/1.3.12

              Explanation: Galeon 1.3.12 on Linux on Intel platform. String from Eric Bowman - thanks.

              Mozilla/5.0 (X11; U; Linux i686) Gecko/20030422 Galeon/1.3.4

              Explanation: Galeon 1.3.4 on Slackware 9 on Intel'ish platform. String from Erik Inge Bolsø - thanks.

              Mozilla/5.0 Galeon/1.2.0 (X11; Linux i686; U;) Gecko/20020326

              Explanation: Galeon 1.2.0 on Redhat 7.2
              // -----------------------------------------------------------------------------------------------------------------------------
              Gnuzilla, IceApe, IceCat and IceWeasel

              Gnuzilla is the GNU version of the Mozilla suite with Iceape the equivalent of Seamonkey (whole moz suite) and IceCat their version Firefox (IceWeasel was the FF version but seems that Debian is now using that name). Only difference is that GNU remove certain non-free software from the binary distributions, plus add a few privacy tweaks - well that's GNU for you. *nix's only.

              Mozilla/5.0 (X11; Linux x86_64; rv:31.0) Gecko/20100101 Firefox/31.0 Iceweasel/31.3.0

              Explanation:Iceweasel 3.5 on Debian8. String from yeti bigfoot(right!) - but thanks anyway.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1) Gecko/20090711 IceCat/3.5

              Explanation:Icecat 3.5 on Linux. String from PDX Pat(?!) - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.19) Gecko/20110817 Iceape/2.0.14

              Explanation:Iceape (Mozilla suite variant) on antiX M11.0(a MEPIS-based distribution with Debian packaging). String from Brian Masinick - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.11) Gecko/20100819 Iceweasel/3.5.11 (like Firefox/3.5.11)

              Explanation:Iceweasel (FF variant) on Debian(?). Apparently ATT/Yahoo don't like this - they demand FF 3.6. Ouch. String from Dominique Brazziel - thanks.

              Mozilla/5.0 (X11; U; Linux i686; de; rv:1.9.0.16) Gecko/2009121610 Iceweasel/3.0.6 (Debian-3.0.6-3)

              Explanation:Iceweasel (FF variant) on Debian Lenny standard (the one that works). String from Frank Frankly (yeah right, but at least you probably know who are) - in any case thanks.

              Mozilla/5.0 (X11; U; GNU/kFreeBSD i686; en-US; rv:1.8.1.16) Gecko/20080702 Iceape/1.1.11 (Debian-1.1.11-1)

              Explanation:Iceape (full Moz suite/Seamonkey) on Debian/kFreeBSD. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; GNU/kFreeBSD i686; en-US; rv:1.9.0.1) Gecko/2008071502 Iceweasel/3.0.1 (Debian-3.0.1-1)

              Explanation:Iceweasel on Debian/kFreeBSD. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.1) Gecko/2008072716 IceCat/3.0.1-g1

              Explanation: IceCat 3.0.1 on generic Linux. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.1) Gecko/2008071420 Iceweasel/3.0.1 (Debian-3.0.1-1)

              Explanation: Iceweasel 3.0.1 on Debian. String from Oscar Rodriguez - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.8) Gecko/20071008 Iceape/1.1.5 (Ubuntu-1.1.5-1ubuntu0.7.10)

              Explanation: This is Iceape (Seamonkey equivalent) on a Ubuntu (Gusty Gibbon's repositories). String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.1) Gecko/20061205 Iceweasel/2.0.0.1 (Debian-2.0.0.1+dfsg-2)

              Explanation: This is IceWeasel (FireFox) on a Debian distro - nice short string. String from Pedro Siqwald - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.4) Gecko/20060620 Iceweasel/1.5.0.4-g1

              Explanation: This is IceWeasel (FireFox) on a Linux distro. String from Sean Artman - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Google

              A collection of google related strings. Some not strictly a browser - but increasingly crawling around your site in multiple disguises. Many of the strings functions are unverified - if you have more information please update. Google Chrome browser strings. And the Google Nexus Phones.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; Google Wireless Transcoder;)

              Explanation: This is a google service that acts as a proxy and converts web sites to a mobile format - somewhat controversial because it seems to drop its own adsense data to make it all fit better and downgrade to Moz 4. Same string also appears under MSIE since it pretends to be MSIE6.x. String from Lucy Thao and Venkatesh Mohan - thanks.

              Mozilla/5.0 (Linux; U; Android 1.1; en-gb; dream) AppleWebKit/525.10+ (KHTML, like Gecko) Version/3.0.4 Mobile Safari/523.12.2

              Explanation: Google android string - latest and (perhaps) greatest. more android info. And yes we know it should be on the mobile pages. One day. Perhaps even in your lifetime. String from Mike Cardwell - thanks.

              Mozilla/5.0 (Linux; U; Android 0.5; en-us) AppleWebKit/522+ (KHTML, like Gecko) Safari/419.3

              Explanation: Google android string - so Webkit is the browser's engine - more android info. String from Jake Wasdin - thanks.

              Mediapartners-Google/2.1

              Explanation: Crawler used to find advertising key words from pages (part of google adsense). Appears this service does not use the normal site search data. Possible explanation from Jacob - thanks.

              Google-Sitemaps/1.0

              Explanation: Google sitemaps are part of the google webmaster toolset. Possible explanation from Jacob - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              GreenBrowser

              GreenBrowser is a IE enhancement with a bunch of features including mouse gestures and a download manager to select 2 from about 20 features described on the web site.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Avant Browser; Deepnet Explorer 1.5.3; Smart 2x2; Avant Browser; .NET CLR 2.0.50727; InfoPath.1)

              Explanation: This is GreenBrowser's ID - how do we know - 'cos we were told that how. Deepnet Explorer (in case you were interested) is a BHO with among others RSS and P2P features - and they swear malware free. String from Bob Madore - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              HotJava

              The original Java based browser. Has not had the develoment resources and so is a pretty ho-hum browser by to-days high standards but you gotta love the name.

              Mozilla/3.0 (x86 [en] Windows NT 5.1; Sun)

              Explanation: HotJava verion 3.0 on Windows XP. String from Robin Lionheart - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              HTTPClient

              Not strictly a browser but a collection of Java classes implementing HTTP functions that can be used by an application to handle HTTP stuff. Apache's Jakarta project also features something called HTTPClient - thanks to Joe Francis for the update - and we feature both strings below. Application could be a browser if you so wished or an e-mail harvester or whatever. HTTPClient We featured this string in our mystery section and still do. Not HTTPClients fault if some nasty guys use a harmless library for nasty things.

              Mozilla/4.5 RPT-HTTPClient/0.3-2

              Explanation: HTTPClient version 0.3-2 on ?? String from Eugene Sadhu - thanks.

              Jakarta Commons-HttpClient/2.0.1

              Explanation: The Jakarta version of HttpClient version 2.0.1. String from Christoph Kutzinski - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Hv3

              Hv3 is a mimimal browser based on the Tkhtml3 rendering engine and written in Tcl. Currently (Sept 2008 in Alpha release). BTW: The Tkmtml3 got us thinking it may be a trivial HTML subset - as usual we were wrong. The engine supports CSS and HTML 4'ish. And EMCA script (Javascript 1.3). available for windows and *nix (and uses that jolly clever startkit idea from those Tcl/TK folks).

              Mozilla/5.1 (X11; U; Linux i686; en-US; rv:1.8.0.3) Gecko/20060425 SUSE/1.5.0.3-7 Hv3/alpha

              Explanation: Hv3 on kubuntu Hardy. While the string includes Gecko it does not use the Gecko rendering engine. The SUSE part of the string may be related to the fact that this was a binary install. String from Jake Wasdin - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              IBrowse

              The venerable and unique Amiga lives on and even has its own browser - IBrowse (quite a clever name if you think about it as in "raised IBrowse"). 68k CPU browser for Amiga and Pegasos computers. It runs on Amiga OS3.x and AmigaOS 4, as well as compatibles such as MorphOS. Will also run on the following hardware: AmigaOne or OS4 capable hardware, Pegasos I and II, Amigas, Amithlon, or platforms such as PC, MAC and others using a version of UAE for Amiga emulation (hardware update from Scott W - thanks)

              IBrowse/2.4 (AmigaOS 3.9; 68K)

              Explanation: IBrowse 2.4 running on AmigaOS4 for Amiga 1200, 68060, AOS3.9. String from Nate Web - thanks.

              IBrowse/2.3 (AmigaOS V51)

              Explanation: IBrowse 2.3 running on AmigaOS4 on an AmigaOne. (PPC/G4). IBrowse is still a 68k binary, but will be released as PPC native with IBrowse 3.0. Quite fast already even emulated under AmigaOS4! String from Scott W - thanks.

              IBrowse/2.3 (AmigaOS 4.0)

              Explanation: Ibrowse 2.3 for AmigaOS and MorphOS. String from Paul Rezendes - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              iCab

              A Mac only browser with some very nice features. You get to buy this one for $29. Tough business model with Safari and Camino now available. Beta versions are free. However this is the only currently being developed browser that runs across the whole MAC range - if you need a single browser interface that spans from the ancient 680x0 Macs to the whizbang OS X world - there is only one game in town - iCab.

              iCab/2.9.8 (Macintosh; U; 68K)
              Lynx/2.8 (compatible; iCab 2.9.8; Macintosh; U; 68K)
              Mozilla/4/5 (compatible; iCab 2.9.8; Macintosh; U; 68K)
              Mozilla/4.0 (compatible; MSIE 5.0; Mac_PowerPC)
              Mozilla/4.76 (Macintosh; I; PPC)

              Explanation: iCab 2.9.8 on the Mac Mac IIsi showing its many masquerading single-click forms. Strings from Sonic Purity - thanks.

              iCab/2.9.7 (Macintosh; U; PPC)

              Explanation: iCab 2.9.7 on PPC Mac running OS 9.1. String from Sonic Purity - thanks.

              iCab/2.9.5 (Macintosh; U; PPC; Mac OS X)

              Explanation: iCab 2.9.5 on Mac (OS X). This is the default or native Browser ID but as with most non-mainstream browsers they allow easy customisation of the browser id string. String from Robert Johnson - thanks.

              Mozilla/4.5 (compatible; iCab 2.7.1; Macintosh; I; PPC)

              Explanation: iCab 2.7.1 on Mac (OS8.6)
              // -----------------------------------------------------------------------------------------------------------------------------
              IPD/AlertSite.com

              Alertsite is a company who appear to specialise in monitoring web sites for various reasons. The strings below are a sign that you are - willingly or not - being monitored.

              ipd/1.0 from AlertSite.com

              Explanation: IPD 1.0 web monitor. String from Bill Jones - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              ICE

              Browser supplied with JBuilder. Since its all Java based it may be a development of the origonal HotJava browser which I thought was great (eh!) for its time.

              ICE Browser/5.05 (Java 1.4.0; Windows 2000 5.0 x86)

              Explanation: ICE 5.05 on Windows 2000 or NT 5.0 if you prefer. The lack of Mozilla compatability will catch most browser detect functions.
              // -----------------------------------------------------------------------------------------------------------------------------
              Kazehakase

              Don't ask us how you pronounce it. Lightweight browser for *nix à la Epiphany/Galeon. Tabbed browsing and RSS plus some nifty mouse features (Opera like). Plans to allow multiple rendering engines (bit like Epihany).

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.11) Gecko Kazehakase/0.5.4 Debian/0.5.4-2.1ubuntu3

              Explanation: Kazehakse 0.5.4 on Ubuntu Jaunty. Jake notes that the Gecko version is dropped. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.13) Gecko/20080311 (Debian-1.8.1.13+nobinonly-0ubuntu1) Kazehakase/0.5.2

              Explanation: Kazehakase 0.5.2 on Xubuntu Hardy. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; Linux x86_64; U;) Gecko/20060207 Kazehakase/0.3.5 Debian/0.3.5-1

              Explanation: Kazenhakase 0.3.5 on Debian running anm AMD 64 machine. String from Andrew Preater - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Kkman

              Not being fluent in Manadarin Chinese or Taiwanese (or even Hakka) we have no idea about the feature set of this browser - but its home page has vaguely firefox like graphics but the string looks like a skinned version of Explorer.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KKman2.0)

              Explanation: Kkman 2.0 on XP. String from Nathan Corn - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              K-Meleon

              Lightweight version of Mozilla. Runs on Windows only. The windows download of this baby is still around 7.5MB. That's light. Keeps bookmarks and favorites (and Hotlists for you ex-Opera users) separate, fast to load and spawn new pages, tiny footprint, tabbed (layered) browsing, and now even prints well! Uses the Gecko rendering engine from Mozilla. Still our browser of choice on windows. No contest. Apparently Mozilla no longer provides an embeddable version of Gecko so development of K-meleon has been suspended. When HTML5 becomes a fully fledged standard we may have to change browser - we estimate somewhere around 2080. Update: Development has restarted (2014'ish).

              Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.17pre) Gecko K-Meleon/1.6.0

              Explanation: K-Meleon 1.6 (beta) on Windows 7. Perhaps the last version to be released - let's hope not. String from Us - thanks - you're welcome.

              Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.1.4) Gecko/20070511 K-Meleon/1.1

              Explanation: K-Meleon 1.1 on win2K with 27,227 patches. String from Us - thanks - you're welcome.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 K-MeleonCCFME 0.09

              Explanation: K-MeleonCCF ME 0.9 Beta 3 V2 - K-Meleon fork over tabs vs Llayers and other issues (more info and download) on Windows XP. String from Jake Wasdin - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.0.7) Gecko/20060917 K-Meleon/1.02

              Explanation: K-Meleon 1.02 on Windows 2K. Latest version of our favourite windows browser. String from Jax Axa - thanks.

              Mozilla/5.0 (Windows; U; Win 9x 4.90; en-US; rv:1.7.5) Gecko/20041220 K-Meleon/0.9

              Explanation: K-Meleon 0.9 on Windows ME. Our favourite windows browser. String from Alexander Kozak - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8.2

              Explanation: K-Meleon 0.8.2 (latest) on Windows XP. String from Eric Bowman - thanks.

              Mozilla/5.0 (Windows; U; Win98; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8.2

              Explanation: K-Meleon 0.8.2 on Windows 98SE. Uses Gecko rv:1.5 so the DOM is in great shape but still includes the Mozilla 1.4+ bug that screws up our printer friendly pages. Sigh! String from Alex Wood - thanks.

              Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8

              Explanation: K-Meleon 0.8 on Windows NT4.

              Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.2b) Gecko/20021016 K-Meleon 0.7

              Explanation: K-Meleon 0.7 on Windows NT4. Uses Gecko rv:1.2b so the DOM is in great shape. Printing still sucks.

              Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:0.9.5) Gecko/20011011

              Explanation: K-Meleon on Windows NT4. Version 0.6. The use of Gecko rv:0.9.5 leaves it with some DOM limitations that were present in that release.

              Mozilla/5.0(Windows;N;Win98;m18)Gecko/20010124

              Explanation: K-Meleon on 0.2.1 Windows 98 SE - very old version. String from Richard Albion - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Konqueror

              The KDE browser of choice for *nix systems. And the technological base for Apple's Safari. And now available on Windows to make life better for the otherwise deprived.

              Mozilla/5.0 (compatible; Konqueror/14.0; Linux) KHTML/TDEHTML/14.0.0 (like Gecko) (Debian)

              Explanation:Konqueror 14.0 on Ubuntu 14.0.4.1 under Trinity Desktop Environment (TDE) which is a fork of KDE (at version 3.5). String from Jake Wasdin - thanks.

              Mozilla/5.0 (compatible; Konqueror/4.1; OpenBSD) KHTML/4.1.4 (like Gecko)

              Explanation:Konqueror 4.1 on OpenBSD (not for the faint-hearted, but safe, most definitely safe). String from Jimmy Dean - thanks.

              Mozilla/5.0 (Windows; Windows i686) KHTML/4.8.0 (like Gecko) Konqueror/4.8

              Explanation:Konqueror 4.8 on Windows (yes, that's right windows). Deyan also writes it now in pretty good shape and can be downloaded here. String from Deyan Mavrov - thanks.

              Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)

              Mozilla/5.0 (compatible; Konqueror/4.0; Linux) KHTML/4.0.5 (like Gecko)

              Explanation:Konqueror 4.0.5 on Kubuntu Hardy Heron. String from Jake Wasdin - thanks.

              Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)
              Verion 4.x

              Explanation:Konqueror 4.0.80 beta on Windows. KDE seem to be moving into windows turf - apparently SSL now works for the security conscious. String from Deyan Mavrov - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.92; Microsoft Windows) KHTML/3.92.0 (like Gecko)

              Explanation:Konqueror 4.0 beta(3.92) on Windows built using these instructions - uses MinGW - apparently still some problems. String from Deyan Mavrov - thanks.
              Verion 3.x

              Mozilla/5.0 (compatible; Konqueror/3.5; GNU/kFreeBSD) KHTML/3.5.9 (like Gecko) (Debian)

              Explanation:Konqueror 3.5.9 on Debian/kFreeBSD. String from Jake Wasdin - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.5; Darwin) KHTML/3.5.6 (like Gecko)

              Explanation:Konqueror on PPC under OS X 10.4.10. String from Tyler Stobbe - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.5; Darwin 8.10.0; X11; Power Macintosh; en_US)KHTML/3.5.6 (like Gecko)

              Explanation:Konqueror on PPC under Darwin 8.10.0. String from Tyler Stobbe - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.5; Linux; X11; x86_64) KHTML/3.5.6 (like Gecko) (Kubuntu)

              Explanation:Konqueror on AMD Turion 64 with Ubuntu Linux Feisty Fawn. String from Jon J - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.4; GNU/kFreeBSD) KHTML/3.4.2 (like Gecko) (Debian package 4:3.4.2-4)

              Explanation:Konqueror/KDE Version 3.4.2 on GING 0.10 live CD (Debian/kFreeBSD). String from Jake Wasdin - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.4; CYGWIN_NT-5.1) KHTML/3.4.89 (like Gecko)

              Explanation:Konqueror/KDE Version 3.4 KDE 3.4.89 on CYGWIN on XP SP2. String from Deyan Marvov - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.5; Linux 2.6.14-kanotix-6; X11) KHTML/3.5.3 (like Gecko) (Debian package 4:3.5.3-1)

              Explanation:Konqueror/KDE Version 3.5 KDE 3.5.3 on Kurumin (a Brazilian linux distribution based on Debian). String from Renan Birck - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.5; Linux; X11; i686; en_US) KHTML/3.5.3 (like Gecko)

              Explanation:Konqueror/KDE Version 3.5 KDE 3.5.3 on SuSE Linux 10.1. String from Suresh P.C. - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.4; Linux) KHTML/3.4.3 (like Gecko) (Kubuntu package 4:3.4.3-0ubuntu1)

              Explanation:Konqueror/KDE Version 3.4.3 on Linux Kubuntu didtribution. String from Zonjai Nebza - thanks.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)

              Explanation:Konqueror/KDE Version 3.4.3 on FreeBSD 5.4 on the 386 with a phony ID string - honest. String from Edwin Chambers - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.4; FreeBSD) KHTML/3.4.3 (like Gecko)

              Explanation:Konqueror/KDE Version 3.4.3 on FreeBSD 5.4 on the 386. String from Edwin chambers - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.4; Linux 2.6.8; X11; i686; en_US) KHTML/3.4.0 (like Gecko)

              Mozilla/5.0 (compatible; Konqueror/3.4; Linux) KHTML/3.4.1 (like Gecko)

              Explanation:Konqueror/KDE Version 3.4 on Linux Mandriva LE2005 - new combination of Mandrake and Connectiva. String from Leon Brooks - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.4; Linux 2.6.8; X11; i686; en_US) KHTML/3.4.0 (like Gecko)

              Explanation:Konqueror/KDE Version 3.4 on Slaware Linux 10. String from Renan Birck - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.3; Linux 2.6.8.1-24mdk; X11; i686; en_GB, en_US) (KHTML, like Gecko)

              Explanation:Konqueror/KDE Version 3.3 on Linux Mandrake 10.1 - only thing missing from the string is the user's shirt size. String from Leon Brooks - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.3; Linux) (KHTML, like Gecko)

              Explanation:Konqueror/KDE Version 3.3 on Linux Mandrake 10.1. String from Leon Brooks - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.2; Linux 2.6.7-3ajp; X11; i686) (KHTML, like Gecko)

              Explanation:Konqueror/KDE Version 3.2 on Linux Mandrake 10.0 (with a 2.6 no less). String from Andrew Preater - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.2; FreeBSD) (KHTML, like Gecko)

              Explanation:Konqueror/KDE Version 3.2 on FreeBSD (hurrah). String from Edwin Chambers - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.1; Linux 2.4.20)

              Explanation:Konqueror/KDE Version 3.1 on Linux (note this gives kernel version number). String from Erik Inge Bolsø - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.1; Linux; X11; i686)

              Explanation: Edited string from Konqueror on KDE 3.1 on Linux Mandrake 9.0 under X windows. String from Andrew Praeter - thanks.

              Mozilla/5.0 (compatible; Konqueror/3.1; Linux 2.4.19-32mdkenterprise; X11; i686; ar, en_US)

              Explanation: Fully loaded browser id from Konqueror on KDE 3.1 on Linux Mandrake 9.0 under X windows. String from Andrew Praeter - thanks.
              Verion 2.x

              Mozilla/5.0 (compatible; Konqueror/2.1.1; X11)

              Explanation: Konqueror 2.1.1 (KDE) on Linux Mandrake 8.0 under X windows
              // -----------------------------------------------------------------------------------------------------------------------------
              Links

              Another mostly text browser. Jürgen Starek contibuted this explanation of the difference between Links and Lynx. We have taken some liberties with the summary - errors are ours not Jürgen's.

              Links can render Tables and Frames
              Links does a better job of rendering color
              Lynx integrates better with scripts Perl etc.
              Links has a graphical option.

              So there you go.

              There appear to be two versions of Links. Version 1 can be obtained from the link above and now version 2 (has more graphic content than v1) which can be downloaded from here.

              Links (2.8; Linux 3.19.0-21-generic i686; GNU C 4.8.3; text)

              Explanation: Links 2.8 on Xubuntu. String from William Southard - thanks.

              Links (2.7; NetBSD 6.99.16 amd64; GNU C 4.7; x)

              Explanation: Links 2.7 on NetBSD 6.99.16. String from Alex Poylisher - thanks.

              Links

              Explanation: We publish this simply because it is the ultimate in minimal browser strings - even beats dillo in its brevity. It is actually Links-Hacked on Mandriva Linux 2008.1 (Links-Hacked aims to add among other features - tabbed browsing!). String from Jake Wasdin - thanks.

              Links (2.1pre31; Linux 2.6.21-omap1 armv6l; x)

              Explanation: Links 2.1 preview 31 on a Nokia N800 tablet under OS2008. String from Jake Wasdin - thanks.

              Links (2.1pre18; Linux 2.6.17-dyne i686; x)

              Explanation: Links 2.1 preview 18 (they'll get it right real soon) version of Linux on the dyne:bolic live CD. String from Jonathan McCormack - thanks.

              Links (2.1pre15; Linux 2.4.26-vc4 i586; x)

              Explanation: Links 2.1 preview 15 (they'll get it right soon) version of Linux on Intel'ish box. String from Erik Inge Bolsø - thanks.

              Links (2.1pre14; OS/2 1 i386; 80x33)

              Explanation: Links 2.1 Preview 14 on OS/2 Warp 4. String from Richard Steiner - thanks.

              Links (0.99; OS/2 1 i386; 80x33)

              Explanation: Links 0.99 on OS/2 Warp 4. String from Richard Steiner - thanks.

              Links (0.98; Linux 2.6.7-rc2 i686; 132x43)

              Explanation: Links 0.98 a 2.6 version of Linux on Intel'ish box (132 x 43 is screen size). String from Erik Inge Bolsø - thanks.

              Links (0.98; Unix; 80x25)

              Explanation: Links 0.98 (text browser) on Darwin 6.6 (Mac OS X 10.2.6). String fom Robert Johnson.

              Links (0.95; Unix)

              Explanation: Links 0.95 (text browser) on Linux Mandrake 8.0
              // -----------------------------------------------------------------------------------------------------------------------------
              Lobo

              A Java based browser - replacement for the old HotJava?. Java will make it platform portable for all you Windows fans.

              Mozilla/4.0 (compatible; MSIE 6.0; U; Windows;) Lobo/0.98.2

              Explanation: Lobo on Windows something SP57. String from Doug H - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Lynx

              The original text only browser(?) - seems like pushing water uphill until you realise that a lot of folks with sight problems use it as well. How many of us think about this group when building HTML pages. Besides there are number of other folks who just do not like all the glitz on the modern web. All that Web 2.0 stuff that turns 5 lines of text into a 250K download. Now that really progress.

              Lynx/2.8.5dev.16 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.6b

              Explanation: Lynx 2.8.5 (text browser) on OS/2 Warp 4. String from Richard Steiner - thanks.

              Lynx/2.8.5dev.16 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.6b

              Explanation: Lynx 2.8.5 (text browser) on OS X (a MAC no less). String from Paul Willis - thanks.

              Lynx/2.8.5rel.1 libwww-FM/2.14 SSL-MM/1.4.1 GNUTLS/1.0.16

              Explanation: Lynx 2.8.5 release 1 (text browser) on linux 2.6 k7 SMP with SSL support (GNUTLS version rather than OpenSSL). String from Lucas Lommer - thanks.

              Lynx/2.8.5rel.1 libwww-FM/2.14 SSL-MM/1.4.1 GNUTLS/0.8.12

              Explanation: Lynx 2.8.5 (text browser) on ? One of three people in the world using GNU SSL rather than OpenSSL (just joking there are five of them). String from Erik Inge Bolsø - thanks.

              Lynx/2.8.3rel.1 libwww-FM/2.14FM

              Explanation: Lynx 2.8.3 (text browser) on Win2K - under cygwin or something. String from Neil Thompson - thanks.

              Lynx/2.8.4dev.11 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.6

              Explanation: Lynx 2.8.4 (text browser) on Linux Mandrake 8.0 loaded for secure browsing

              Lynx/2.6 libwww-FM/2.14

              Explanation: Lynx 2.6 (text browser) on ? String from Eugene Sadhu - thanks
              // -----------------------------------------------------------------------------------------------------------------------------
              Maxthon

              A tabbed version of internet explorer with a bunch of other features which seem broadly similar to IE7 and works with either IE6 or IE7. This may also be one way forward into the tabbed browser world for those poor IE folks stuck on an platform which is not supported by IE7.

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; SV1; Maxthon; .NET CLR 1.1.4322)

              Explanation: Maxathon on XP with IE7. String from Asbjørn Pedersen - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              midori

              A lightweight browser based on webkit which is the rendering engine for Konqueor/Safari/Chrome. Available for both *nix and Windows.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-gb) AppleWebKit/535+ (KHTML, like Gecko) Version/5.0 Safari/535.22+ Midori/0.4

              Explanation: Midori 0.4 on the Raspberry Pi using the standard Raspbian distro (ignore the Mac string, it's a Debian based distro...and no, it's not a rectange with rounded corners). String from Jonathan McCormack - thanks.

              Midori/0.2.2 (X11; Linux i686; U; en-us) WebKit/531.2+

              Explanation: Midori 0.2.2 under Ubuntu 10.04. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux armv6l; en-us) AppleWebKit/528.5+ (KHTML, like Gecko, Safari/528.5+) midori

              Explanation: Midori on Nokia n800 tablet device. OK so perhaps it should be on the mobile page. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; fr-fr) AppleWebKit/525.1+ (KHTML, like Gecko, Safari/525.1+) midori

              Explanation: Midori on GNU/Linux Ubuntu Hardy 8.04 (webkit rr31841). String from Stéphane Marquet - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Mosaic

              The browser that started it all. Groundbreaking stuff in its day. All modern browsers owe their interface to this browser (and probably code also!). Archive version only at the above link.

              PATHWORKS Mosaic/1.0 libwww/2.15_Spyglass

              Explanation: From Nathan "PATHWORKS Mosaic is a WIN32s Win3.1 version of Mosaic licensed and modified by Digital Equipment Corporation and bundled with their PATHWORKS networking software". String from Nathan Lineback - thanks.

              WinMosaic/Version 2.0 (ALPHA 2)

              Explanation: Mosaic 2.0a on WfWG 3.11. String from Indrek Haav - thanks.

              VMS_Mosaic/3.8-1 (Motif;OpenVMS V7.3-2 DEC 3000 - M700) libwww/2.12_Mosaic

              Explanation: We can do no better than quote George Cook who supplied the string "VMS Mosaic 3.8-1 is a direct descendant of NCSA Mosaic 2.7b5 (the final release of NCSA's Mosaic for X11). It only runs on the VMS operating systems and is still under active development. It supports HTML V4 but does not support Java or Javascript. The ID string is from using it on a DEC 3000-700 Alpha running OpenVMS V7.3-2. The source and more info is available". String from George Cook - thanks.

              Mosaic from Digital/1.02_Win32

              Explanation: Version of Mosaic running on Windows - more info here. String from Jonathan McCormack - thanks.

              NCSA Mosaic/2.0.0b4 (Windows AXP)

              Explanation: Version of Mosaic running on Windows - its still available. String from Jonathan McCormack - thanks.

              NCSA_Mosaic/2.7b5 (X11;Linux 2.6.7 i686) libwww/2.12 modified

              Explanation: Version of Mosaic running on Mandrake 10.0 Official. Andrew reckons its still pretty useable but with a slightly old fashioned look 'n feel. The browser could not handle our submisson form and other stuff like div - we guess it supports a pretty basic HTML 2.0 or earlier dialect . String from Andrew Preater - thanks.

              mMosaic/3.6.6 (X11;SunOS 5.8 sun4m)

              Explanation: Apparently a special multi-cast version running on a Sun SS20 under Solaris 8. String from Michael Doyle - thanks
              // -----------------------------------------------------------------------------------------------------------------------------
              Mothra

              One of a number of web browsers that run on Plan 9 from Bell Labs - there is also a choice of browsers available. This one is pretty basic with no CSS or Javascript

              mothra/Jul-10-17:33:30-EDT-2006

              Explanation: Mothra on Plan 9 from Bell Labs. The date and time is apparently when the code was last modified. String from Chris Barts - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Mozilla (SeaMonkey)

              The name given to the all-in-one package with browser, email and composer (development code name is Seamonkey). You have to work reasonably hard these days to get mozilla seamonkey (not visible under products but it is visible under downloads). The Mozilla roadmap now says the future is Phoenix - oops, Firebird - oops, Firefox - yeah that's its name for now and Thunderbird for email.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4

              Explanation:Seamonkey 2.0.4 on Puppy Linux (Quirky 1.2) - some distributions like to save their users the hassle of installing separate browsers and email clients - which is pretty neighbourly. String from Thomas Konrad - thanks.

              Mozilla/5.0 (X11; U; Linux i686; nb-NO; rv:1.9.1.8) Gecko/20100205 SeaMonkey/2.0.3

              Explanation:Seamonkey 2.0.3 on some Linux Ditribution - "the faster browser ever" writes Magne - obviously a big fan. String from Magne Heen - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1b4pre) Gecko/20090405 SeaMonkey/2.0b1pre

              Explanation:Seamonkey 2.0 beta on Ubuntu Hardy Heron (gotta love those release names) - the difference form the string below its a beta pre release. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1b1pre) Gecko/20080915000512 SeaMonkey/2.0a1pre

              Explanation:Seamonkey 2.0 alpha on Ubuntu Gutsy Gibbon - same a string below but note the date format change for Gecko. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9pre) Gecko/2008060901 SeaMonkey/2.0a1pre

              Explanation:Seamonkey 2.0 alpha on Ubuntu Gutsy Gibbon. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.13) Gecko/20080313 SeaMonkey/1.1.9 (Ubuntu-1.1.9+nobinonly-0ubuntu1)

              Explanation:Seamonkey 1.1.9 on Kubuntu Hardy. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; SunOS sun4u; en-US; rv:1.7) Gecko/20070606

              Explanation:Mozilla 1.7 on Solaris 10 (UltraSparc)). String from Milan Kupcevic - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.8) Gecko/20050927 Debian/1.7.8-1sarge3

              Explanation:Mozilla 1.7.8 on Debian Linux, Debian 3.1r1 i386 (Sarge 3). String from R Smith - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.1.2) Gecko/20070220 Firefox/2.0.0.8

              Explanation:Seamonkey 1.1.5 on XP - yeah really - used a hacked string to overcome comcast's reluctance to recognize Gecko rather than Firefox - smart or what. String from Lon Stowell - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.1.8) Gecko/20071009 SeaMonkey/1.1.5

              Explanation:Seamonkey 1.1.5 on XP real (native) version of hacked string above. String from Lon Stowell - thanks.

              Mozilla/5.0 (Windows; U; WinNT3.51; en-US; rv:1.8.1.6) Gecko/20070802 SeaMonkey/1.1.4

              Explanation:Seamonkey 1.1.4 on NT 3.51 for those that have memories that go back that far. String from Nathan Lineback - thanks.

              Mozilla/5.0 (BeOS; U; BeOS BePC; en-US; rv:1.8.1.8pre) Gecko/20070926 SeaMonkey/1.1.5pre

              Explanation:Seamonkey 1.1.4 (even if shown as 1.1.5pre) on BeOS. String from Nathan Lineback - thanks.

              Mozilla/5.0 (X11; U; Darwin Power Macintosh; en-US; rv:1.8.1.5) Gecko/20070803 SeaMonkey/1.1.3

              Explanation:Seamonkey 1.1.3 on MAC running Darwin 8.10.0. String from Tyler Stobbe - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1b2) Gecko/20060823 SeaMonkey/1.1a

              Explanation:Seamonkey 1.1a on a Linux distro of some kind. String from Woody Suwalski - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.13) Gecko/20060417

              Explanation:Mozilla 1.7.13 on FC3 on a Dell Dimension B110. String from Brian Quach - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.1) Gecko/20060130 SeaMonkey/1.0

              Explanation:Seamonkey 1.0 on XP with SP2 (the whole enchilada). String from Deyan Marvov - thanks.

              Mozilla/5.0 (OS/2; U; Warp 4.5; en-US; rv:1.9a1) Gecko/20051119 MultiZilla/1.8.1.0s SeaMonkey/1.5a

              Explanation:Seamonkey 1.5a with MultiZilla extension on OS/2 (or eComStation) . String from Lewis G. Rosenthal - thanks.

              Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.7.12) Gecko/20051105

              Explanation:Full moz package 1.7.12 on FreeBSD 5.4 (native port as opposed to the one below which is also on FreeBSD. String from Edwin Chambers - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.12) Gecko/20050920

              Explanation:Full moz package 1.7.12 on FreeBSD 5.4 (the Linux in the string is 'cos it's running with a set of ported Linux - RH8 - libraries). String from Edwin Chambers - thanks.

              Mozilla/5.0 (X11; U; Linux ppc; en-US; rv:1.7.12) Gecko/20051009 Debian/1.7.12-1

              Explanation:Full moz package 1.7.12 for Debian on PowerPC. String from Chris Young - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8b4) Gecko/20050910 SeaMonkey/1.0a

              Explanation:Seamonkey 1.0a on Windows 2K. String from Mike Solomon - thanks.

              Mozilla/5.0 (Windows; U; Win98; en-US; rv:1.8b3) Gecko/20050713 SeaMonkey/1.0a

              Explanation:Mozilla SeaMonkey project - nightly build on Windows 98. String from J Reynolds - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8a2) Gecko/20040704

              Explanation:Mozilla browser 1.8 - looks like a nightly build and on XP - clearly enjoys crashes. String from Erik Inge Bolsø - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.7.11) Gecko/20050728

              Explanation:Mozilla browser 1.7.11 stock build on Windows XP version française. Anonyme - on vous remercie.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.8) Gecko/20050511

              Explanation:Mozilla browser 1.7.8 stock build on Windows XP. String from Ian Sweeny - thanks.

              Mozilla/5.0+(X11;+U;+Linux+i686;+en-US;+rv:1.7.3)+Gecko/20040922

              Explanation:Mozilla browser 1.7.3 stock build on Fedora Core 2 with KDE. String from Rick Blake - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.6) Gecko/20040413 Debian/1.6-5

              Explanation:Mozilla browser 1.6 on Debian Linux. String from Erik Inge Bolsø - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.6) Gecko/20040616 MultiZilla/1.6.3.1d

              Explanation: Mozilla browser 1.6.3 with multizilla tabbed enhancement on Linux. String from Erik Inge Bolsø - thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US; rv:1.0.2) Gecko/20020924 AOL/7.0

              Explanation: AOL packaged version of Mozilla browser (they are pushing MSIE now) on the MAC. String from Erik Inge Bolsø - thanks.

              Mozilla/5.0 (Windows; U; Win 9x 4.90) Gecko/20020502 CS 2000 7.0/7.0

              Explanation: CompuServe packaged version of Mozilla browser (indicated by the the CS 2000) - in this case on a Windows 9x (?). String from Mike Lust - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.5b; MultiZilla v1.5.0.2g) Gecko/20030827

              Explanation:Mozilla 1.5b with MultiZilla (a super tabbed interface) (string from Robert Martin - thanks)

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.4b) Gecko/20030504 Mozilla Firebird/0.5+ StumbleUpon/1.63

              Explanation: Maybe the first sighting of Firebird or should that be Mozilla Firebird(?). Also contains the StumbleUpon free toolbar extension. Mozilla 1.4 on Win XPPro. String from erik ? - thanks.

              Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.2) Gecko/20021126

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.3) Gecko/20030312

              Explanation:Mozilla 1.3 (latest and greatest) on Win XPPro (string from Chuang Tzu - thanks)

              Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.2) Gecko/20021126

              Explanation:Mozilla 1.2 on NT 4.0

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.2a) Gecko/20020910

              Explanation:Mozilla 1.2a on Windows XP?

              Mozilla/5.0 (X11; U; Linux 2.4.3-20mdk i586; en-US; rv:0.9.1) Gecko/20010611

              Explanation:Mozilla 0.9.1 on Linux Mandrake 8.0
              // -----------------------------------------------------------------------------------------------------------------------------
              MS Explorer

              The 900 pound gorilla of the browser business. IE 10 is currently (2011) being previewed on windows 7. MSIE 9.0 is now released - if you are using Vista or 7 (or you can hang onto IE 8.0) . And for XP you have a choice of IE6, 7 or 8. And for the rest of us - tough. Seems Trident is the MS rendering engine name (since IE8?) - the equivalent of Opera's Presto and Moz's Gecko. Microsoft's MSIE history page (Link from Riley McArdle - thanks).

              There are also strings for Windows CE/IEMobile in this section for those of a curious disposition (and yes, they should be in the mobile section. What do you expect - consistency?).

              Information about recognizing media center embedded in browser. From Rob Lehew - thanks.

              This represents the current and major historic release versions of the strings - well, in our unbalanced judgment. We maintain a separate page with a complete list of MS Internet Explorer strings that we have ever found or received (including some really bizarre stuff) - includes those on this page.
              Curious

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; InfoPath.1)

              Explanation: This string indicates the request comes from an MS InfoPath (an MS Office extra application) and means that it may be collecting data for use in a forms based application. String from Juan Sotillo - thanks.
              MS Internet Explorer on XBOX

              Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; Xbox)

              Explanation: Microsoft Internet Explorer 8.0 on XBOX (masquerading as Windows 7). String from Jonathan McCormack - thanks.
              MS Internet Explorer (MSIE) V11

              Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko

              Explanation: Microsoft Internet Explorer 11.0 (32-bit) on Windows 8 (64 bit). Fascinating string - the version number is defined by the "rv" parameter and the "like Gecko" - shades of Chrome. String from Deyan Markov - thanks.
              MS Internet Explorer (MSIE) V10

              Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)

              Explanation: Microsoft Internet Explorer 10.0 (10.0.9200.16660) on Windows 7 (64 bit). String from Andreas Klante - thanks.
              MS Internet Explorer (MSIE) V9

              Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0)

              Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Win64; x64; Trident/4.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Tablet PC 2.0)

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Win64; x64; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Tablet PC 2.0)

              Explanation: Microsoft Internet Explorer 9.0 (64-bit version) on Windows 7 (64 bit) - the three strings from the top are in normal IE9 mode, in IE8 mode and IE7 mode. Two items to note. First, IE9 removes all the .net stuff (security concerns). Second, the inclusion of the Tablet PC string (indicating support for the late, unlamented Windows Tablet specification?). String from Dwight Hams - thanks.
              MS Internet Explorer (MSIE) V8 - V6 Hybrids/Curiosities

              Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; GTB6.5; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C)

              Explanation: Microsoft IE 8.0 plus 6.x. Suspicion that it is some failed XP to Vista upgrade. Can anyone confirm? String from Kev ? - thanks.
              MS Internet Explorer (MSIE) V8

              Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe/13.0.782.218; chromeframe; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)

              Explanation: Microsoft IE 8.0 on Windows XP with Google's Chrome Frame plugin to add HTML5 features. String from Rick Giliberti - thanks.
              MS Internet Explorer (MSIE) Media Center/Media-Player

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; FunWebProducts; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506; Windows-Media-Player/10.00.00.3990)

              Explanation: MSIE 7 with Media Center 5.0 and Media-Player 10.x on Vista. String from Jake Wasdin - thanks.
              MS Internet Explorer (MSIE) with Zune 2.0

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; winfx; .NET CLR 1.1.4322; .NET CLR 2.0.50727; Zune 2.0)

              Explanation: MSIE 7 with Zune 2.0 on XP. String from Zarek Jenkinson - thanks.
              MS Internet Explorer (MSIE) V7

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; GTB6.4; .NET CLR 1.1.4322; FDM; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)

              Explanation: MSIE 7 running on Windows XP Pro - FDM = Free Download Manager. String from Willian Abel - thanks.
              MS Internet Explorer (MSIE) V6.0+

              Mozilla/4.0 (compatible; MSIE 6.0; Windows 98; Rogers Hi-Speed Internet; (R1 1.3))

              Explanation: MSIE 6.x on Windows 98 (honest) with R1 1.3 = RealOne Player version 2 . String from Buck Grewal - thanks.
              MS Internet Explorer (MSIE) V5.5

              Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)

              Explanation:MSIE 5.5 on Windows 98. String from Francis Saul - thanks.
              MS Internet Explorer (MSIE) V5.0+

              Mozilla/4.0 (compatible; MSIE 5.0; SunOS 5.10 sun4u; X11)

              Explanation:MSIE 5.x on SunOS 5.10. String from Tim Telarson - thanks.

              Mozilla/4.0 (compatible; MSIE 5.22; Mac_PowerPC)

              Explanation:MAC OS X version of MSIE. String from Eric Noel - thanks.
              MS Internet Explorer (MSIE) V4.0+

              Mozilla/4.0 (compatible; MSIE 4.01; Windows NT 5.0)

              Explanation: MSIE 4.01 on Windows XP SP2. String from Alex Williams - thanks.
              MS Internet Explorer (MSIE) V3.0+

              Mozilla/2.0 (compatible; MSIE 3.02; Windows CE; 240x320)

              Explanation: MSIE 3.02 on a Pocket PC 2002. I guess the 240x320 is the available screen size. Goodness knows what DOM this baby supports. Anyone old enough to remember MSIE 3!
              MS Internet Explorer (MSIE) V2.0+

              Mozilla/1.22 (compatible; MSIE 2.0; Windows 95)

              Explanation: MSIE 2.0 in windows '95 - anyone remember the base version on the venerable '95. String from Ryan J - thanks. Update Win'95 did not ship with a browser but 1.0 was included in the Plus! features.
              MS Windows CE

              Mozilla/4.0 (compatible; MSIE 6.0; Windows CE; IEMobile 7.11)

              Explanation: IEMobile 7.11 on Windows CE. String from Xtian Estrella - thanks.
              MSIE ActiveX Clones

              Rather than document each ActiveX based browser clone separately we have decided to add each one under a generic heading of MSIE Clones. The URL for the browser is contained in each Explanation: section.
              Crazy Browser

              Ah, memories of Patsy Cline.

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/4.0; GTB6.5; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; HPDTDF; .NET4.0C; Crazy Browser 3.0.5)

              Explanation: Crazy Browser. Crazy Browser 3.0.5 on Windows 7 - freeware with tiled, cascade and other display options. String from Bravo 58 (well you know who you are) - thanks.
              The World

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; MyIE2; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 1.1.4322; InfoPath.1; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; TheWorld)

              Explanation: The world. The world browser 3.0 on XP - a modestly named fast, light, free and secure browser (they say). String from Roger Thomas - thanks.
              bsalsa

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; GTB6.3; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; ws8 Embedded Web Browser from: http://bsalsa.com/; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)

              Explanation: bsalsa. A Delphi based embedded browser. This string shows it being invoked from Wordsearch 8. String from Jake Wasdin - thanks.
              Enigma

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)

              Explanation: Enigma. Another tabbed MSIE clone browser. They suggest its memory light, very fast and very robust - and has a built-in editor for HTML, VBScript and Jscript (Windowspeak for javascript). String from Samuel Vonlanthen - thanks.
              SmartBro

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.0; Smart Bro)

              Explanation: SmartBro. A tabbed MSIE clone browser. The interesting thing about the above string is that is shows MSIE 7.0 on a Win2K base which officially does not support IE 7. Is this a way round getting MSIE 7.0 on non-supported platforms? String from Bob Madore - thanks.
              Sleipnir

              MMozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Tablet PC 2.0) Sleipnir/2.8.3

              Explanation: Sleipnir. MSIE clone browser specializing in customization - and with big Japanese following. String from Eric Langer - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              SlimBrowser

              Free windows browser based on Trident (the MS rendering engine) - but it boasts a 2M download - now that is even smaller than dillo - but the string is longer.

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SlimBrowser)

              Explanation: SlimBrowser on Windows XP. String from Marco Pannetto - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              muCommander

              A Java based file management system based on the Norton Commander style interface - includes the ability to access remote file systems using FTP/SFTP/HTTP among others. OS and, because of Java, runs on most platforms.

              muCommander v0.8.3 (Java 1.6.0_0-b11; Linux 2.6.24-19-generic i386)

              Explanation: muCommander 0.8.3 on Linux. String used when using HTTP to access remote file system. String from Jake Wasdin - thanks.

              muCommander v0.8.3 (Java 1.4.2_03-b02; Windows XP 5.1 x86)

              Explanation: muCommander 0.8.3 on Windows XP. String used when using HTTP to access remote file system. String from Jake Wasdin - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              NetPositive

              Stock browser for the BeOS system. No longer active but the link is to the evolt browser archive site (great resource). There is embyonic work on a replacement called Themis. For all you BeOS lovers out there there appears to be two BeOS reincarnations yellotabs Zeta (commercial but shipping) and OpenBeOS (not commercial and not shipping)

              Mozilla/3.0 (compatible; NetPositive/2.2.2; BeOS)

              Explanation: NetPositive 2.2.2 default browser on BeOS (when you could get it) and Zeta (see above). String from Matt Emson - thanks.

              Mozilla/3.0 (compatible; NetPositive/2.2.1; BeOS)

              Explanation: NetPositive 2.2.1 default browser on BeOS and Zeta. String from Erik Inge Bolsø - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Netscape

              Netscape is no more. The netscape page now states that official support ended on 1st March 2008 though you can still download release 9, 8, 7 and the venerable 4 from the site. They recommend using Firefox or Flock (interesting). The last Version 9 was called Navigator again which as Deyan Mavrov reminded us is the first time they used the name since NS4.x (now that was a release). Since version 8 they used the firefox base with their own stuff added and a very slick graphic design (IOHO). Previous versions used the full Mozilla build (now seamonkey). Wonder who uses it - we guess it's just folks who have always used Netscape. End of an era. Sigh.

              This represents the current and major historic release versions of the strings - well, in our unbalanced judgment. We maintain a separate page with a complete list of Netscape strings that we have ever found or received (including some really bizarre stuff) - includes those on this page.
              Netscape Navigator 9.x

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.12) Gecko/20080219 Firefox/2.0.0.12 Navigator/9.0.0.6

              Explanation: Netscape Navigator 9.0.0.6 - Firefox 2.0.0.12 - good looking browser. String from Andrew Preater - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.8pre) Gecko/20071019 Firefox/2.0.0.8 Navigator/9.0.0.1

              Explanation: Netscape Navigator 9 with Firefox fixes - good looking browser. String from Deyan Mavrov - thanks.
              Netscape 8.x

              Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.5) Gecko/20050519 Netscape/8.0.1

              Explanation: A real Firefox based Netscape 8 with a security patch (already) on Win 2K. Using Gecko base of 1.7.5 not current 1.7.8. String from Joseph Christianson - thanks.
              Netscape 7.x

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.2) Gecko/20040804 Netscape/7.2 (ax)

              Explanation: Netscape 7.2 and still we're wondering what's the (ax)? But we wonder no longer Laurence ? wrote and told us that according to Netscape's documentation it means the browser supports the Windows Media ActiveX Control. So now we know. Many thanks. Original string from Hilde Schlecht - thanks.
              Netscape 6.x

              Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:0.9.4.1) Gecko/20020508 Netscape6/6.2.3

              Explanation: NS 6.2.3 on Windows 7 (honest). String from Jake Wasdin - thanks.
              Netscape Navigator 4.x

              Mozilla/4.8 [en] (X11; U; Linux 2.4.20-8 i686)

              Explanation: NS 4.8 on Redhat 9. String from Renan Birck - thanks.
              Netscape Navigator 3.x

              Mozilla/3.04Gold (X11; U; IRIX 5.3 IP22)

              Explanation: NS Navigator Gold 3.04 on SGI with IRIX. Another rave from the grave!. String from Peter Landbo - thanks.

              Mozilla/3.01 (WinNT; I) [AXP]

              Explanation: NS 3.01 on DEC ALPHA under NT - wow! You can get it here. String from Jonathan McCormack - thanks.
              Netscape Navigator 2.x

              Mozilla/2.02 [fr] (WinNT; I)

              Explanation: NS 2.02 on MS NT 4.0. This might now be the oldest string known to man cos NS1 cannot access virtual servers - its not see below. String from Stanislas Renan - thanks.
              Netscape Navigator Pre 1.x

              Mozilla/0.96 Beta (X11; Linux 2.6.25.18-0.2-default i686)

              Explanation: Netscape 0.96 on a modern production system - SUSE 11! You need these instructions from Jamie Zawinski's web site to make it fly - all we can say is - Wow! String from Deyan Mavrov - thanks.

              Mozilla/0.91 Beta (Windows)

              Explanation: The ex-dinosaur string. Netscape 0.91, a pre-1.0 beta release from 1994. Also known as 'Mosaic NetScape'. Running under Win XP SP2 (honest). String from Andrew Praeter - thanks.

              Mozilla/0.6 Beta (Windows)

              Explanation: The new dinosaur string. Netscape 0.6 on WfWG 3.11. HTML 2.0 - no tables - who needs tables - how about everyone. String from Andrew Praeter - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Netsurf

              We have given Netsurf a life of its own - while it is still shown on its web site as being a browser for the ex-Acorn (which is no more) RISC OS we are reliably told by email that future plans include other platforms including RISC OS, Linux and FreeBSD.

              NetSurf/2.9 (Linux; armv6l)

              Explanation: Netsurf 2.9 on the Raspberry Pi using the standard Raspbian distro. String from Jonathan McCormack - thanks.

              NetSurf/1.1 (Linux; i686)

              Explanation: NetSurf 1.1 on Kubuntu Hardy. Which means this project has now migrated from its historic Acorn/RISC OS only base and is available for #nix platforms - there is a port for BeOS in development. String from Jake Wasdin - thanks.

              NetSurf/0.0 (RISC OS; armv5l) NetSurf/0.0 (Linux; i686)

              Explanation: Post Jan 30th 2007 for pre version 1.0 test strings format for NetSurf on RISC OS and Linux platforms. String format is NetSurf/[major version].[minor version] ([OS]; [Architecture]). Strings and updates from Michael Drake - thanks.

              Netsurf

              Explanation: Pretty impressive string from the new Open Source browser for the RISC OS. String from Chris Bazley - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              OffByOne

              Super-small Windows only browser. HTML 3.2 standard, no Javascript or plug-ins but around 1MB download. Runs direct from CD. Free. 'You pays your money and you takes your choice!'

              Mozilla/4.7 (compatible; OffByOne; Windows 2000)

              Explanation: OffByOne 3.5 on XP (so minimal it keeps the win 2k string to save code). And how do we know its version 3.5? 'cos Jake told us is how. String from Jake Wasdin - thanks.

              Mozilla/4.7 (compatible; OffByOne; Windows 2000) Webster Pro V3.4

              Explanation: OffByOne on windows 2K - the Webster Pro is the ActiveX control the browser is based on. (String from Eric Root - thanks).
              // -----------------------------------------------------------------------------------------------------------------------------
              Omniweb

              Omniweb is a browser for MAC OS X (yeah they got lots of choices too - its not just you PC guys). Omniweb, from version 5.9, is now as free as a bird (they used to have one of those try - then buy policies - but no more). No doubt about it IOHO the quality of Apple and Apple vendor web pages is a cut above the normal. Omniweb since version 4.5 release uses the embedded OS X KHTML rendering engine rather than its own.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_5; en-US) AppleWebKit/531.21.8+(KHTML, like Gecko, Safari/528.16) Version/5.10.3 OmniWeb/622.14.0

              Explanation: OmniWeb 5.10.3 running on Mac OS 10.6.5 on a first-gen MacBook. String from William Cline - thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; en-US) AppleWebKit/531.9+(KHTML, like Gecko, Safari/528.16) OmniWeb/v622.10.0

              Explanation: OmniWeb 5.10.1 running on Mac OS 10.5.8 on an Intel MacBook (first gen). String from William Cline - thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US) AppleWebKit/525.18 (KHTML, like Gecko, Safari/525.20) OmniWeb/v622.3.0.105198

              Explanation: Omniweb 5.8 on the Mac OS X. String from William Cline - thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/125.4 (KHTML, like Gecko, Safari) OmniWeb/v563.34

              Explanation: Omniweb 5.0.1 on the Mac updated version - another new look!. String from Erik Inge Bolsø - thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/85 (KHTML, like Gecko) OmniWeb/v558.48

              Explanation: Omniweb 5.0.1 on the MAC. Complete with a new look (can it get better than it was) and workspaces . String from Paul Willis - thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/85 (KHTML, like Gecko) OmniWeb/v558.46

              Explanation: Omniweb 5 on the MAC. Complete with a new look (can it get better than it was) and workspaces . (String from Chris Gehlker - thanks).

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/85 (KHTML, like Gecko) OmniWeb/v496

              Explanation: Omniweb 4.5 version on the MAC. Great looking browser. (String from Robert Johnson - thanks).

              Mozilla/4.5 (compatible; OmniWeb/4.2.1-v435.9; Mac_PowerPC)

              Explanation: Omniweb 4.2.1 on the MAC (is Mozilla 4.5 closer to 4 or 5?). String from Erik Inge Bolsø - thanks.

              Mozilla/4.5 (compatible; OmniWeb/4.2-v435.2; Mac_PowerPC)

              Explanation: Omniweb latest beta version on the MAC (is Mozilla 4.5 closer to 4 or 5?) (String from Stephen Paulsen - thanks).

              OmniWeb/2.7-beta-3 OWF/1.0

              Explanation: Omniweb 2.7 under NextStep 3.x - wow! String from Michael Doyle - thanks. info quote from Michael "NS 3.3 ran on Moto 68k,SPARC uSPARC-II and SuperSPARC I & II, Intel 486 & up, HP PA-7100 & 7100LC processors". So there you go.
              // -----------------------------------------------------------------------------------------------------------------------------
              One Laptop per Child Browser

              This is the $100 target per laptop to bring educational resources to children especially those in the under-developed world. The browser is based on Mozilla's XULRunner technolgy.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.4) Gecko/20061019 pango-text

              Explanation: A very early version of the One Laptop per Child browser running in an virtual machine environment. String from Zonjai Nezba - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Opera

              Note: Opera V5+ had a free download. Opera 5 and 6 had quirky Javascript/DOM support. Opera 7 set a whole new (excellent) standard now continued with Opera 8, 9 and 10. And we just loved that Aqua skin. Watch these strings because you can select a variety of user agent strings with this browser. If at first you don't succeed - try another user agent id. Copious email from Opera lovers indicates the default for 10 is now the Opera native string - grave error in our opinion (see also comments for the version 10/9.80 wheeze below). Section now includes some Opera Mobile/Mini strings. Some more Opera Mobile strings can also be found under various PDAs and phones in the mobile page. Presto (as in Hey, Presto!) is the Opera rendering engine - their version of Gecko or Webkit or Trident - which does make it a tad easier to recognize. Glossy new interface with version 11 where we could not find out where to change the home screen url. Sigh. And why do they always cloak any manufacturer's info in their strings? For those who are keen to know about these things Opera Mobile is typically targetted at modern smartphones and renders the pages directly (just like regular Opera) whereas Opera Mini needs some help from an Opera server to render the page and is typically targetted at lower function featurephones. The code base for the main browser (running on windows etc.) now (from release 15?) uses webkit/chromium with Opera customization on top (and is identified by the string OPR/XX.XX.XX.XX, whereas Opera Mini continues to use the Presto engine code base.

              Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36 OPR/32.0.1948.69

              Explanation: Opera on Windows 7. String from us - thanks - you're welcome.

              Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.36 OPR/31.0.1889.174

              Explanation: New WebKit based Opera on Windows 8.1. String from Nyree Parsons - thanks.

              Opera/9.80 (Windows NT 6.2; U; Edition Indian Local; en) Presto/2.9.168 Version/11.51

              Explanation: Opera masquerading as .... Opera on Windows 8. String from MOBARAQUE HOSSAIN - thanks.

              Opera/9.80 (BlackBerry; Opera Mini/8.0.35667/35.6050; U; en) Presto/2.8.119 Version/11.10

              Explanation: Opera Mini version 8.0.35667/35.6050 on some blackberry doodad. Note while the Mini version in this is different from the one immediately below both the Presto and Version strings are the same - mmmmm. String from Devon Arendze - thanks.

              Opera/9.80 (Android; Opera Mini/7.5.31657/35.5125; U; ms) Presto/2.8.119 Version/11.10

              Explanation: Opera Mini version 7.5.31657/35.5125 on some android thingy. Mini continues to use the Presto rendering engine. String from Blacknaz Naz (yeah, right!) - thanks.

              Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36 OPR/19.0.1326.63

              Explanation: Opera 19 on Windows 7. The OPR/ part identifies the Opera version. String from Joe Bloggs (yeah, right!) - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/4.5.33867/32.855; U; en) Presto/2.8.119 Version/11.10

              Explanation: Opera Mini 4.5 (on a Presto 11.10 base) on some mobile thingy. String from Faiz Sial - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/4.3.24214/28.2555; U; en) Presto/2.8.119 Version/11.10

              Explanation: Opera Mini 4.3 (on a Presto 11.10 base) on some mobile thingy. See if you can spot the difference from the string below. String from ?? - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/6.5.26955/27.1382; U; en) Presto/2.8.119 Version/11.10

              Explanation: Opera Mini 6.5 (on a 11.10 base) on some mobile thingy. See if you can spot the difference from the string below. String from Krystal Mckaney - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/4.1.13907/27.1188; U; en) Presto/2.8.119 Version/11.10

              Explanation: Opera Mini 4.x (on a 11.10 base) on some mobile thingy. String from Irfan Ali - thanks.

              Opera/9.80 (Windows NT 6.1; U; en) Presto/2.10.229 Version/11.61

              Explanation: Opera 11.61 on Windows 7. String from Vlad M - thanks.

              Opera/9.80 (Android 2.3.5; Linux; Opera Mobi/ADR-1111101157; U; de) Presto/2.9.201 Version/11.50

              Explanation: Opera Mobile (Opera 11.50 base) on an Android handset (man, they really cloak the device details - talk about branding). String from Peter ? - thanks.

              Opera/9.80 (Series 60; Opera Mini/6.1.26266/26.1069; U; en) Presto/2.8.119 Version/10.54

              Explanation: Opera Mini6.1.26266 (Opera 10.54 base) on a Symbian (most likely Nokia) handset. String from Chris Hylanf - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/4.2.13221/25.623; U; en) Presto/2.5.25 Version/10.54

              Explanation: Opera Mini 4.2.13221 (Opera 10.54 base) on a Blackberry whatsit. String from Nobuntu Jama - thanks.

              Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.10

              Explanation: Opera 11.10 on Windows 7 64-bit Home Pro. String from Us - thanks - you're welcome.

              Opera/9.80 (Windows Mobile; WCE; Opera Mobi/WMD-50433; U; en) Presto/2.4.13 Version/10.00

              Explanation: Opera 10 on Windows Mobile - one of only 3 in the world - just kidding - there are at least 12. String from Ryno Kruger - thanks.

              Opera/9.80 (Windows NT 6.0; U; en) Presto/2.7.62 Version/11.00

              Explanation: Opera 11 on windows Vista Ultimate x64. String from anon - thanks anon.

              Opera/9.80 (X11; Linux i686; U; en-GB) Presto/2.6.30 Version/10.62

              Explanation: Opera 10.62 on EasyPeasy (ex Ubuntu Eee) on Eee 1000 notebook. String from David Evans - thanks.

              Opera/9.80 (Macintosh; Intel Mac OS X; U; en) Presto/2.6.30 Version/10.61

              Explanation: Opera 10.61 (build: 8429) on Mac OS X. String from Matt Greek - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/5.1.21214/19.916; U; en) Presto/2.5.25

              Explanation: Opera Mini on something? String from Ashutosh Ojha - thanks.

              Opera/9.80 (Windows NT 6.1; U; en) Presto/2.5.24 Version/10.54

              Explanation: Opera 10.54 on windows 7 Enterprise. String from Christian Hagelid - thanks.

              Opera/9.80 (S60; SymbOS; Opera Mobi/499; U; ru) Presto/2.4.18 Version/10.00

              Explanation: Opera 10.00 on Symbian OS (must be on some moble thingy). String from Alexander Smirnov - thanks.

              Opera/9.80 (Windows NT 5.1; U; en) Presto/2.5.22 Version/10.50

              Explanation: Opera 10.50 on Windows XP. String from Jake Wasdin - thanks.

              Opera/9.80 (Windows NT 6.0; U; en) Presto/2.5.22 Version/10.50

              Explanation: Opera 10.50 (Final) on Windows Vista. String from Jim F - thanks.

              Opera/9.80 (X11; Linux x86_64; U; Linux Mint; en) Presto/2.2.15 Version/10.10

              Explanation: Opera 10.10 on Linux Mint 8. String from Jake Wasdin - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/5.0.16823/1428; U; en) Presto/2.2.0

              Explanation: Opera Mini 5.0.16823/1428 on Sony Ericsson K800i. Note the 9.80 version wheeze. String from Thu Win - thanks.

              Opera/9.80 (J2ME/MIDP; Opera Mini/4.2.14912/1186; U; en) Presto/2.2.0

              Explanation: Opera Mini 4.2 on some mobile thingy. Seems the 9.80 version wheeze for all version 10 browsers is carried over to the mobile world. String from Kopy Kat (well we guess you know you you are) - thanks.

              Opera/9.80 (Windows NT 5.2; U; en) Presto/2.2.15 Version/10.10

              Explanation: Opera 10.10 on Window XP 64 bit version with SP2. So we now have the spectre of Opera 9.80 frozen forever while for those in the know just read the version value to get the real skinny. Wow this is progress. Great browser. Stupid string. String from Axel Girona - thanks.

              Opera/9.80 (X11; Linux i686; U; nl) Presto/2.2.15 Version/10.00

              Explanation: Opera 10 something. So how do we know, we can do no better than quote Ad 'Note that the version after Opera/ does not mention 10.0 anymore. There were still to many sites that incorrectly concluded that it was Opera version 1. Still valid: "Check the capabilities, not the browser."' String from Ad von Reeken - thanks.

              Opera/9.60 (J2ME/MIDP; Opera Mini/4.2.13337/458; U; en) Presto/2.2.0

              Explanation: Opera Mini 4.2 - they really do take over the device - you figure the mobile device id. String from Mickel Santiago - thanks.

              Opera/9.60 (J2ME/MIDP; Opera Mini/4.1.11320/608; U; en) Presto/2.2.0

              Explanation: Opera Mini 4.1 on something probably vaguely mobile. String from Pat Grins - thanks.

              Opera/10.00 (X11; Linux i686 ; U; en) Presto/2.2.0

              Explanation: Alpha of Opera 10 released 12/5/2008 (presto is the Opera rending engine - like Gecko for FF). String from Jake Wasdin - thanks.

              Opera/9.62 (Windows NT 5.1; U; en) Presto/2.1.1

              Explanation: Opera 9.62 on windows XP (Presto is the Opera rending engine - like Gecko for FF). String from Standa Pacan - thanks.

              Opera/9.60 (X11; Linux i686; U; en) Presto/2.1.1

              Explanation: Opera 9.60 on Debian GNU/Linux (presto is the Opera rending engine - like Gecko for FF). String from Jake Wasdin - thanks.

              Opera/9.52 (Windows NT 5.1; U; en)

              Explanation: Opera@USB (Opera loaded from a USB memory stick) Opera 9.52 on Windows XP. String from Jake Wasdin - thanks.

              Opera/9.25 (Windows NT 6.0; U; en)

              Explanation: Opera 9.25 on windows Vista. String from Paul Hedley - thanks.

              Opera/9.20 (Macintosh; Intel Mac OS X; U; en)

              Explanation: Opera 9.20 on MAC with OS X. String from Chuck Betley - thanks.

              Opera/9.02 (Windows NT 5.0; U; en)

              Explanation: Opera 9.02 on Win 2K. String from Jax Axa - thanks.

              Opera/9.00 (Windows NT 4.0; U; en)

              Explanation: Opera 9.0 on Windows NT 4.0 - this is the new default (was MSIE in older versions - to change use Tools->Quick Preferences->Edit site Preferences->Network Tab).

              Opera/9.00 (X11; Linux i686; U; en)

              Explanation: Opera 9.0 on linux 2.6, static Qt installation. String from Lucas Lommer (July 2006) - thanks.

              Opera/9.00 (Windows NT 5.1; U; en)

              Explanation: Opera 9 (final - spot the difference from the one below) on XP. String from ?? - thanks.

              Opera/9.0 (Windows NT 5.1; U; en) Opera/9.0 (Macintosh; PPC Mac OS X; U; en) Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.0

              Explanation: Opera 9 (currently beta) on the Mac and XP. Strings from Ryan J - thanks.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows CE; PPC; 480x640) Opera 8.60 [en]

              Explanation: Opera Mobile 8.60 on a Dell Axim X51v - health warning - you need to pay for this baby. Strings from Alex Williams - thanks.

              Opera/8.5 (Macintosh; PPC Mac OS X; U; en)

              Mozilla/5.0 (Macintosh; PPC Mac OS X; U; en) Opera 8.5

              Mozilla/4.0 (compatible; MSIE 6.0; Mac_PowerPC Mac OS X; en) Opera 8.5

              Explanation: Opera 8.5 on the Mac. The ever psychophrenic (took us 10 minutes with the dictionary to get that right) Opera. Strings from Zonjai Nezba - thanks.

              Opera/8.0 (Macintosh; PPC Mac OS X; U; en)

              Mozilla/5.0 (Macintosh; PPC Mac OS X; U; en) Opera 8.0

              Mozilla/4.0 (compatible; MSIE 6.0; Mac_PowerPC Mac OS X; en) Opera 8.0

              Explanation: Opera 8.0 on the Mac. All three on OS X identifying itself as real Opera, Moz and MSIE respectively. Strings from Carlos Alberto Pinto Peixoto Bastos Santos (great name - none of this boring North American single middle name stuff - rock on cultural diversity) - many thanks.

              Opera/8.01 (Windows NT 5.1)

              Mozilla/5.0 (Windows NT 5.1; U; en) Opera 8.01

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)

              Explanation: Opera 8.01 preview. All three on XP Pro identifying itself as real Opera, Moz and MSIE respectively. Strings from Guilherme Tanaka - thanks.

              Mozilla/5.0 (Windows NT 5.1; U; en) Opera 8.00

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.00

              Explanation: Opera 8.00 (ex 7.60 preview) on XP Pro identifying itself as Mozilla and MSIE respectively. Strings from Jonathan Walker - thanks.

              Opera/8.00 (Windows NT 5.1; U; en)

              Explanation: Opera 8.00 (ex 7.60 preview) on XP Pro. String from Michael May - thanks.

              Mozilla/5.0 (X11; Linux i386; U) Opera 7.60 [en-GB]

              Explanation: Opera 7.60 (pretending to be Mozilla running on NetBSD (under Linux compatability). String from Alex Poylisher - thanks.

              Opera/7.60 (Windows NT 5.2; U) [en] (IBM EVV/3.0/EAK01AG9/LE)

              Explanation: Opera 7.60 running on XP. Apparently with some nifty voice 'multimodal' capabilities. String from pomel ? - thanks.

              Opera/7.54 (Windows NT 5.1; U) [pl]

              Explanation: Opera 7.54 in native mode (not often seen in the wild) on XP. Strings from Romuald Redlich - thanks.

              Opera/7.50 (X11; Linux i686; U) [en]

              Explanation: Opera 7.50 running on Mandrake Linux and pretending to be - itself. String from Andrew Preater - thanks.

              Mozilla/5.0 (X11; Linux i686; U) Opera 7.50 [en]

              Explanation: Opera 7.50 running on Mandrake Linux and pretending to be - Mozilla/5.0 (well its almost the same). String from Andrew Preater - thanks.

              Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux i686) Opera 7.20 [en]

              Explanation: Opera 7.20 running on Linux (yeah they got lots of choices too) and pretending to be MSIE 6.0 (now we're'confused!). String from Brian Myers - thanks.

              Opera/7.11 (Windows NT 5.1; U) [en]

              Explanation: The real thing. An Opera browser pretending to be itself. On Windows XP. String from Robin Lionheart - thanks.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows ME) Opera 7.11 [en]

              Explanation: Opera 7.11 running on ? and pretending to be - MSIE 6.0 (which it does well 'cept it corrects some of the bugs!). String from Erik Inge Bolsø - thanks.

              Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.0) Opera 7.02 Bork-edition [en]

              Explanation: The infamous MSN version of Opera 7.02 on W2K. Inspired Opera response to a sleazy MS abuse of power (so whats new).

              Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 4.0) Opera 7.0 [en]

              Explanation: Opera 7.0 on NT 4.0. Our pop-outs now work with this version so it must be good!!

              Mozilla/4.0 (compatible; MSIE 5.0; Windows 2000) Opera 6.0 [en]

              Explanation: Opera 6.0 on Windows 2000.

              Mozilla/4.0 (compatible; MSIE 5.0; Windows 95) Opera 6.01 [en]

              Explanation: Opera 6.01 on Windows 95.

              Mozilla/4.0 (compatible; MSIE 5.0; Mac_PowerPC) Opera 5.0 [en]

              Explanation: Opera 5.0 on the Mac (OS8.6).
              // -----------------------------------------------------------------------------------------------------------------------------
              Oregano and Acorn Browse

              Oregano is a browser for RISC OS PCs as is a new Open Source browser called Netsurf which both run under RISC OS originally made by Acorn Ltd of the UK (which went bust in 1998) but lives on (shades of Amiga). Old systems never die they just slowly fade way. We feature Oregano, Netsurf and the original Acorn browser called - wait for it - Browse - that's it, man is that cool understated marketing or what (perhaps that's why they went bust). This platform also has the Netsurf which is planning to go multi-host and day now.

              Mozilla/4.01 (Compatible; Acorn Browse 1.25 [23-Oct-97] AW 97; RISC OS 4.39) Acorn-HTTP/0.84

              Explanation: Original Acorn Browse 1.10 RISC OS 4.36 (ACORN). browse info String from Chris Bazley - thanks.

              Mozilla/1.10 [en] (Compatible; RISC OS 3.70; Oregano 1.10)

              Explanation: Oregano 1.10 RISC OS 3.70 (ACORN). (Get Oregano here)

              Mozilla/1.10 [en] (Compatible; RISC OS 3.70; Oregano 1.10)

              Explanation: Browser Oregano (and these guys have got a choice of browsers!) running on the ACORN RISC PC. From Stanislas Renan - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Oxygen

              Small Windows and Linux Mozilla clone. Shareware with $29 price if you like it.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:0.9.6) Gecko/20011128

              Explanation: This is apparently NETDIVE Oxygen 1.1 - so there you go. String from Andrew Preater and Mark Schenk - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Palemoon

              Palemoon is a Windows only version of Firefox. It uses the standard Firefox code base but claims to be up to 25% faster at page rendering due to windows optimization. When we checked they were pretty quick at updating to the latest versions. May not always be the case however.

              Mozilla/5.0 (Windows NT 5.1; rv:8.0) Gecko/20111108 Firefox/8.0 PaleMoon/8.0

              Explanation: Palemoon/8 (FF/8 base) on Windows XP. String from Andrew Mullins - thanks.

              Mozilla/5.0 (Windows NT 6.0; Win64; x64; rv:5.0) Gecko/20110624 Firefox/5.0-x64 PaleMoon/5.0-x64

              Explanation: Palemoon FF/5 base on Windows Vista. String from Michael Roccia - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101215 Firefox/3.6.13 (Palemoon/3.6.13)

              Explanation: Palemoon on Windows XP SP2. String from Bryan Dehn - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.11) Gecko/20101023 Firefox/3.6.11 (Palemoon/3.6.11) ( .NET CLR 3.5.30729; .NET4.0E)

              Explanation: Palemoon on Windows 7 (64 bit). String from Suluh Legowo - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              HP Web PrintSmart

              Utility from HP to capture and print web pages. To more information use the link above and type 'printsmart' in the search box.

              Mozilla/3.0 (compatible; HP Web PrintSmart 04b0 1.0.1.34)

              Explanation: HP Web PrintSmart software on ?? String from Eugene Sadhu - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Mozilla Prism

              Previously called WebRunner (the Moz folks do rather like to change names). A Mozilla labs project to create Site Specific Browsers (SSBs) with mimimal chrome for the web application being accessed. We'll watch where the experiment goes with interest. Versions available for *nix and Windows.

              Mozilla/5.0 (X11; U; Linux armv6l; en-US; rv: 1.9.1a2pre) Gecko/20080813221937 Prism/0.9.1

              Explanation: Prism on a Nokia N800 tablet under OS2008. String from Jake Wasdin - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.1) Gecko/2008071719 prism/0.8

              Explanation: Prism on Linux. String from Jake Wasdin - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Proxomitron

              Not strickly a browser but a web filtering app that rewrites your pages on the fly... for Windows users only. Looks like it uses the GNU bison parser..

              Bison/0.02 [fu] (Win67; X; SK)

              Explanation: Native string from Proxomitron - interesting version of windows! String from John McCoy - thanks.
              retawq

              Text mode browser for *nix systems (Linux, FreeBSD and Darwin). Multi-windows (tabbed), session resume, and flexible keyboard mapping plus mouse support.

              retawq/0.1.6 [en] (text)

              Explanation: retawq (catchy name) on something! String from Erik Inge Bolsø - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Safari

              OS X browser now in Release 5.x for you MAC (and now Windows) users - available for the iPhone, iPad and iPod as well as more earth-bound devices. Following explanation of browser geneology from Robert Johnson "Safari uses, and Apple helps develop KHTML (which is what Konqueror embeds). KHTML is in WebCore, which is part of AppleWebKit. AppleWebKit is available to any app on the Mac. OmniWeb abandoned their own rendering engine for AppleWebKit with version 4.5.". So there you go. Apparently the choice of KHTML instead of Gecko was a wee bit contentious. Various iPhone, iPad, iPod and iOther strings.

              Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.78.2 (KHTML, like Gecko) Version/7.0.6 Safari/537.78.2

              Explanation: Safari 7.0.6 running on Mac OS X 10.9.4. String from M Day - Thanks.

              Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/536.26.17 (KHTML, like Gecko) Version/6.0.2 Safari/536.26.17

              Explanation: Safari 6.02 running on Mac OS X 10.8.2. String from M Day - Thanks.

              Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2

              Explanation: Safari 5.1.7 running on Mac OS X 10.6.8. String from Mike Crawford - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-us) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27

              Explanation: Safari 5.0.4 running on Mac OS X 10.6.6. String from Thomas Schmid - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-us) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4

              Explanation: Safari 5.0.3 running on Mac OS X 10.6.6. String from William Cline - Thanks.

              Mozilla/5.0 (iPod; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7

              Explanation: iPod thingy with Safari 4.0.5. String from Nic Bart - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-us) AppleWebKit/533.17.8 (KHTML, like Gecko) Version/5.0.1 Safari/533.17.8

              Explanation: Safari 5.0.1 (6533.17.8) on Mac OS X. String from Matt Greek - Thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/531.21.8 (KHTML, like Gecko) Version/4.0.4 Safari/531.21.10

              Explanation: Safari 4.0.4 (531.21.10) on Windows XP SP3. String from Ben Anderson - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; en-us) AppleWebKit/531.21.8 (KHTML, like Gecko) Version/4.0.4 Safari/531.21.10

              Explanation: Safari 4.0.4 (531.21.10) on iMac 2.16 Ghz Intel Core 2 Duo running OS X 10.6.2. String from Eric Pastoor - Thanks.

              Mozilla/5.0 (iPod; U; CPU iPhone OS 2_2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5H11a Safari/525.20

              Explanation: Safari 3.1.1 (525.18.1) on iPod Touch - it seems to also have pretensions to a better life as a iPhone. String from Darren D - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; en-us) AppleWebKit/525.28.3 (KHTML, like Gecko) Version/3.2.3 Safari/525.28.3

              Explanation: Safari 3.2.3 (525.28.3) on OS X 10.5.7. String from Rob Rampley - Thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Version/3.1.2 Safari/525.21

              Explanation: Safari 3.1.2 (525.21) on Windows XP SP7002 (naw, just fooling around, its only SP3). String from Fernando Lichtschein - Thanks.

              Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5F136 Safari/525.20

              Explanation: iPhone 3G version 2.1 - again with Safari 3.1.1 as below. String from Sebastien Dubois - Thanks.

              Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_0 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5A347 Safari/525.20

              Explanation: We also got this one from an unnamed source for the iPhone 3G version - again with Safari 3.1.1 as below. String from - well you know who you are - Thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Version/3.1.2 Safari/525.21

              Mozilla/5.0 (iPod; U; CPU iPhone OS 2_0 like Mac OS X; de-de) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5A347 Safari/525.20

              Explanation: Safari 3.1.1 for iPod Touch 2.0. String from Thomas Lahr - Thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Version/3.1.2 Safari/525.21

              Explanation: Safari 3.1.2 for Windows XP. String from Mike Michaelson - Thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.18 (KHTML, like Gecko) Version/3.1.1 Safari/525.17

              Explanation: Safari 3.1.1 for Windows XP (SP3). String from Ian Westerfield - Thanks.

              Mozilla/5.0 (iPod; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3A101a Safari/419.3

              Explanation: Safari 3.0 for the iPod with latest firmware (3A101a). String from Kunal Jain - Thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_2; en-us) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13

              Explanation: OS X Leopard, v10.5.2, Safari v3.1 - same configuratio as below but on PPC. String from Jeff Squyres - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_2; en-us) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13

              Explanation: OS X Leopard, v10.5.2, Intel MacBook Pro, Safari v3.1. String from Jeff Squyres - Thanks.

              Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/4A93 Safari/419.3

              Explanation: Safari 3.0 for the iPhone (version .1.3?). String from Tom Chilton - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-gb) AppleWebKit/523.10.6 (KHTML, like Gecko) Version/3.0.4 Safari/523.10.6

              Explanation: Safari 3.0.4 on Mac OS 10.5.1 Intel. String from Jason Mayfield-Lewis - Thanks.

              Mozilla/5.0 (iPod; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3A100a Safari/419.3

              Explanation: Safari 3.0 for the iPod touch. String from Greg McGuiness - Thanks.

              Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C28 Safari/419.3

              Explanation: Safari 3.0 for the iPhone. String from Greg Mcguiness - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/522.11.1 (KHTML, like Gecko) Version/3.0.3 Safari/522.12.1

              Explanation: Safari 3.0.3 for Intel version of iMac. String from Greg Mcguiness - Thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; bg) AppleWebKit/522.13.1 (KHTML, like Gecko) Version/3.0.2 Safari/522.13.1

              Explanation: Safari 3.0.2 beta for Windows XP. String from Deyan Mavrov - Thanks.

              Mozilla/4.0 (compatible; Mozilla/4.0; Mozilla/5.0; Mozilla/6.0; Safari/431.7; Macintosh; U; PPC Mac OS X 10.6 Leopard; AppleWebKit/421.9 (KHTML, like Gecko) )

              Explanation: Safari browser V2 on OS X (10.6 Leopard). String from ? - Thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; ru) AppleWebKit/522.11.3 (KHTML, like Gecko) Version/3.0 Safari/522.11.3

              Explanation: Safari browser V 3.0 Beta for Windows XP SP2 . String from Vadim Korneyko - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/419.3 (KHTML, like Gecko) Safari/419.3

              Explanation: Safari browser V 2.o.4 with Beta for OS X . String from Robert Carter - Thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/418.8 (KHTML, like Gecko) Safari/419.3

              Explanation: Safari browser 2.0.4 for MAC OS X (10.4.7) . String from Peter Tax - Thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/417.9 (KHTML, like Gecko) Safari/417.8

              Explanation: Safari browser 2.0.3 for MAC OS X (10.4.4) . String from Pavel Sochnev - Thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/417.3 (KHTML, like Gecko) Safari/417.2

              Explanation: Safari browser 2.0 for MAC OS X (10.4.4 build) . String from Tim Johnsen - Thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/412 (KHTML, like Gecko) Safari/412

              Explanation: Safari browser 2.0 for MAC OS X (10.4.1 build 8B15) . String from Robert Vawter - Thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/312.9 (KHTML, like Gecko) Safari/312.6

              Explanation: Safari 1.3.x PPC under OS X. String from Andrew Hodder - thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; fr-fr) AppleWebKit/312.5.1 (KHTML, like Gecko) Safari/312.3.1

              Explanation: Safari 1.3.1 on 1.3.9 after after Security update 2005-008 . String from Herve B - Thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; fr-fr) AppleWebKit/312.5 (KHTML, like Gecko) Safari/312.3

              Explanation: Safari 1.3.1 (v312.3) 10.3.9 = last update on last version of Panther a.k.a all people who can't update to Tiger 10.4 !! . String from Herve B - Thanks.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/124 (KHTML, like Gecko) Safari/125.1

              Explanation: Safari browser 1.25.1 for MAC OS X. String from Jim Prince - thanks. If you are into this kind of Safari stuff Jim also keeps a full list from the early betas on his site.

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/106.2 (KHTML, like Gecko) Safari/100.1

              Explanation: Safari browser 1.0 for MAC OS X. (string from Yaso Leon).

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; es) AppleWebKit/85 (KHTML, like Gecko) Safari/85

              Explanation: Safari browser 1.0 for MAC OS X with spanish language variant. (string from Robert Johnson).

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-us) AppleWebKit/74 (KHTML, like Gecko) Safari/74

              Explanation: Safari browser build 74 for MAC OS X. (string from Eric Noel).

              Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/51 (like Gecko) Safari/51

              Explanation: Safari browser for MAC OS X. (string from erik ?, Robert Seymour and Ken Zirkel).
              // -----------------------------------------------------------------------------------------------------------------------------
              HP Secure Web Browser

              One of the Gecko/Mozilla clones this time from HP for OpenWMS.

              Mozilla/5.0 (X11; U; OpenVMS AlphaServer_ES40; en-US; rv:1.4) Gecko/20030826 SWB/V1.4 (HP)

              Explanation: Quite recent version of Gecko running under OpenVMS on an Alpha (now w e don't see many of them) String from Jonathan McCormack - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Shiira

              Browser for Apple OS X based on cocoa/AppleWebKit which means it picks up the standard Safari/KHTML base. Modest goals of the project are to create a better browser than Safari.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-us)
              AppleWebKit/523.10.5 (KHTML, like Gecko) Shiira Safari/125

              Explanation: Shiira on OS X. String from Phillip Miller - thanks.
              Spectrum Internet Suite

              Open Source browser for the Apple IIgs - remember. And you guys all thought Apple had no choices for browsing. They got choices coming out of their ears. This one may be a bit light on Javascript support.

              Mozilla/2.0 (Compatible; SIS 1.2; IIgs)

              Explanation: Spectrum 2.5.2 telecommunications program using Apple IIgs System Software 6.0.1 and the Marinetti 2.0.1 TCP/IP stack (remember check between the characters!) String from Stephen Heumann - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Spicebird

              Email and collaboration and browsing. Windows, MAC OS X and Linux. Similar to Mozilla's Lightning project.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.2pre) Gecko/2009031304 Spicebird/0.7.1

              Explanation: Spicebird 0.7.1 on Linux. String from Jake Wasdin - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Songbird

              Gecko based music optimized Browser. Windows, MAC OS X and Linux. Good name for a music browser. Or should it be SongFox - Oh well it was just a thought. Good looking web site.

              Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.10) Gecko/2009043012 Songbird/1.2.0 (20090616030052)

              Explanation: Songbird 1.2.0 on Linux Mint 8. String from Jake Wasdin - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008072921 Songbird/0.7.0 (20080819112708)

              Explanation: Songbird 0.7.0 on Windows XP. String from Jake Wasdin - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9a7pre) Gecko/2007073021 Songbird/0.3pre (20070731174647)

              Explanation: Songbird 0.3 (nightly build) on Windows Vista. String from Joseph Christianson - thanks.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1b2) Gecko/20060925 Songbird/0.2.5.1 (20070301190953)

              Explanation: Songbird 0.2.5.1 on Kubuntu. String from Jake Wasdin - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.0; en-NZ; rv:1.8.1b2) Gecko/20060925 Songbird/0.2

              Explanation: Songbird 0.2 (developer preview - read beta?) on Windows 2K from someone in New Zealand (boy are we smart today or what?). String from Joseph Christianson - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8) Gecko/20060206 Songbird/0.1

              Explanation: Songbird 0.1 (proof-of-concept - read alpha) on Windows XP. String from Anthony Buckland - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              SRWare Iron

              Chromium (WebKit) based browser with enhanced privacy features. And it does not send any data about your browsing peccadilloes to you-know-who. Available on window, MAC and *nix. Soon to be available in a mobile form - it is hoped.

              Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Iron/26.0.1450.0 Chrome/26.0.1450.0 Safari/537.36

              Explanation: SRWare Iron 26.0.1450.0 on Windows 7 - Chrome 26.x base. String from ?? (unknown) - thanks.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Iron/9.0.600.2 Chrome/9.0.600.2 Safari/534.13

              Explanation: SRWare Iron 9.0 on MAC OS X - Chrome 9.x base. String from Carl McCall - thanks.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Iron/4.0.280.0 Chrome/4.0.280.0 Safari/532.9

              Explanation: SRWare Iron on Windows XP Tablet with SP3. String from Ian Springer - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Stainless

              Multi-process browser that aims to out chrome, Chrome, geddit. Or put another way you have no need for chrome if you got stainless to begin with. We should be writing their copy. MAC OS X only at this time.

              Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6; en-us) AppleWebKit/525.27.1 (KHTML, like Gecko) Stainless/0.5.5 Safari/525.20.1

              Explanation: Stainless 0.5.5 on OS X. String from Vincent Jacobs - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Strata

              A data browser based on Firefox - you get to pay for this baby - but there is a free trial version. It lets you do all knds of spiffy stuff with web based data like spread-sheet it locally etc.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.12pre) Gecko/20080101 Strata/4.1.0.1274

              Explanation: Strata running on Kubuntu Hardy Heron. String from Jake Wasdin - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Surf

              Small footprint GTK/Webkit based browser. Currently no tabs - ouch. *nix only. But lightweight, very lightweight.

              Surf/0.4.1 (X11; U; Unix; en-US) AppleWebKit/531.2+ Compatible (Safari)

              Explanation: Surf 0.4.1 running on Gentoo 3.2.12. Strings from Stark Dickflüssig and Chris Barts - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              SwiftFox

              Optimised Firefox for AMD and Intel CPUs. The fragmentation of Firefox gathers pace.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1) Gecko/20061024 Firefox/2.0 (Swiftfox)

              Explanation: Swiftfox running on Ubuntu CE V2.0. String from Jonathan McCormack - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Sylera

              Gecko based Browser from Japan - welcome. You need the Mozilla base then its a 1.5M download - seems to provide tabbed browsing, mouse gestures and a bunch of other stuff.

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.5) Gecko/20031007 Sylera/1.2.7

              Explanation: Sylera 1.2.7 on Windows XP. String from Erik Inge Bolsø - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Vision

              This should really be on the mobile page since it appears (from their web site) to be a packaged mobile environment so it probably appears on many mobile thingies. The company (Novarra) seems to have been just bought by Nokia. Lucky for them.

              Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.20) Gecko/20081217 Firefox/2.0.0.20 Novarra-Vision/8.0

              Explanation: Vision (looks like a branded FF 2.x to us) on something. String from Laurie Orta - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              W3C Line Mode

              Text only browser from the W3C. Distributed as part of its libwww system. 'It is historically interesting, since it was originally developed at CERN starting in 1990 and as such was the second web browser ever created, after Tim Berners-Lee's original browser for the NeXT' (explanation from Stephen Heumann).

              W3CLineMode/5.4.0 libwww/5.4.0

              Explanation: Version 5.4.0 (current release) Tim Berners-Lee gets an author credit on the site - why is his name not in the UA string! String from Stephen Heumann - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Waterfox

              A full-blooded 64-bit version of Firefox. Some benchmarks say it's faster, some not so sure. Since it's built under Intel's optimized compiler what's not to like. Fully compatible with 32-bit extensions.

              Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:24.0) Gecko/20100101 Firefox/24.0 Waterfox/24.0

              Explanation: Waterfox (64-bit) on Windows 8 (64-bit) - a full house. String from Joseph Christianson - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              WebCapture (Adobe)

              Erik writes "According to the web, this is Acrobat 5.0 grabbing your web page for preserving it as a PDF" so there you go.

              Mozilla/3.0 (compatible; WebCapture 2.0; Auto; Windows)

              Explanation: String from Erik Inge Bolsø - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              webOSbrowser

              webOS lives as an HP open source project and with a stock browser based on webkit.

              Mozilla/5.0 (Linux; webOS/2.2.4; U; en-GB) AppleWebKit/534.6 (KHTML, like Gecko) webOSBrowser/221.56 Safari/534.6 Pre/3.0

              Explanation: webOS running a webkit browser on the HP Pre 3. Rare string indeed. String from Jonathan McCormack - thanks

              w3m/0.4.1

              Explanation: w3m 0.4.1 on ? String from Erik ?
              // -----------------------------------------------------------------------------------------------------------------------------
              WebPositive

              Default webkit based browser with Haiku OS which is a rewrite of BeOS. Regrettably, Haiku OS does not have a short, but poetic, tagline.

              Mozilla/5.0 (compatible; U; InfiNet 0.1; Haiku) AppleWebKit/528+ (KHTML, like Gecko) WebPositive/528+ Safari/528+

              Explanation: String from Jonathan McCormack - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              WebTV (MS)

              We created a separate entry for WebTV 'cos its got unique display characteristics (read quirks and bugs).

              Mozilla/4.0 WebTV/2.8 (compatible; MSIE 4.0)

              Explanation: WebTV 2.8 on ? String from Neil Thompson - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              w3m

              Text only browser runs on Linux/BSD and WIN32 (under cygwin) an alternative to Lynx and Links. Has been used to give HTML rendering capabilities to emacs for you emacs fans.

              w3m/0.5.1

              Explanation: w3m 0.5.1 on FreeBSD on the Intel platform (read between the characters!). String from Edwin Chambers - thanks

              w3m/0.4.1

              Explanation: w3m 0.4.1 on ? String from Erik ?
              // -----------------------------------------------------------------------------------------------------------------------------
              Wget

              GNU Utility for downloading internet files using HTTP and FTP.

              Wget/1.8.1

              Explanation: Wget 1.8.1 (GNU HTTP/FTP tool) on Debian Linux. This pesky thing keeps downloading our web site. We're gonna choke it off. String from Gerard Creamer - thanks.

              Wget/1.6

              Explanation: Wget 1.6 (GNU HTTP/FTP tool) on Linux Mandrake 8.0. Is this the same as WebGet? - no its not! WebGet is available here but seems to do roughly the same thing though I'm sure neither Wget nor WebGet folks would agree with that statement.
              // -----------------------------------------------------------------------------------------------------------------------------
              Xenu's Link Analyser

              Windows utility for checking web sites for broken links (bit like WebAnalyzer).

              Xenu_Link_Sleuth_1.2d

              Explanation: Version 1.2d on some windows machine! String from Erik ? - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Xmaxima

              Browser string used by Maxima, which is a Common Lisp implementation of MIT's Macsyma of computer algebra system, when accessing web pages.
              Apparently with *nix and Windows version and now released as OS.

              netmath

              Explanation: Rivals Dillo for the shortest possible string - tough competition. String from Chris Barts - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Yandex

              Free browser based on Chrome from Russia's largest search engine company. The browser speaks English as well, one assumes, as Russian. Runs on Windows, iMac and *nix just like Chrome.

              Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 YaBrowser/1.7.1364.12383 Safari/537.22

              Explanation:Yandex on iMac OS X (10.7.5). String from Chaals (who appears to be one of the developers) - thanks.
              // -----------------------------------------------------------------------------------------------------------------------------
              Life on the edge

              Yeah well. Most of us use MS Windows or Linux on pretty normal Intel or PPC architectures but there are some people who like to live on the edge.
             * Here is the modest "life on the edge" strings list.

              Mozilla/1.10 [en] (Compatible; RISC OS 3.70; Oregano 1.10)

              Explanation: Browser Oregano (and these guys have got a choice of browsers!) running on the ACORN RISC PC. From Stanislas Renan - thanks.

              Contiki/1.0 (Commodore 64; http://dunkels.com/adam/contiki/)

              Explanation: Contiki (a very small footprint Open Source OS) with built in browser which even tells you where to get it - is that helpful or what! From Ryan Jones - thanks.

              xChaos_Arachne/5.1.89;GPL,386+

              Explanation: A web browser for DOS (honest) which you can obtain here. From Ryan Jones - thanks.
              Browser Help Objects

              This section shows strings from a number of plug-ins or proxy services whose job in life (they have decided) is to help (maybe) the user as they meander throughout the 'net. We are going to try and build a list with links to the plug-in site and - with your help - categorise them both as to how they get installed - e.g. willing user or stealth and wheter they are benign or nasty. In may cases we just show the additional string that will result rather than a full browser string - we're not gonna install nasty things just for your information now are we - well not willingly we're not!

              ..FunWebSearch...

              Explanation: MyWebSearch (or FunWebProducts enhanced browser - not classified as sypware or adware. To remove it - go here. Info from Chris Gulutz - thanks.

              Mozilla/4.0 (compatible; Powermarks/3.5; Windows 95/98/2000/NT)

              Explanation: PowerMarks seems to be a benign Bookmark enhancement for most of the popular browsers - not classified as sypware or adware. Info from David Ross - thanks.

              Mozilla/5.0 (compatible; IDZap)

              Explanation: IDZap enhanced browser. OK its done a good job and just defeated every browser detection string - now what.

              Mozilla/3.01Gold (Macintosh; I; 68K)

              Explanation: Life behind a junkbuster proxy. This is MSIE 5.0 on a Windows'95 PC - pretty obvious really! String from Phil Hibbs - thanks.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0;
              ESB{A8417D9D-5087-4807-9D73-9E09290256CD})

              String from Remco de Vijlder. Axel Kollmorgen and Eugene Sadhu suggest that it is most likely EasySearchBar (ESB - geddit). Jim Rofkar and Eugene Sadhu both add another possibility - Easy Start Bar for laptops. Thanks guys.
              Page Validation Services

              Page validation services are great until you try 'em with dynamically generated pages based on the browser - then you gotta know what they send.

              WDG_Validator/1.6.1

              Explanation: The Web Design Groups (WDG) page validator service tool they also have great material on HTML and CSS as well on their site. String from Peter Booth - thanks.

              Bobby/4.0.1 RPT-HTTPClient/0.3-3E

              Explanation: A tool that checks web pages for Accessibility - Section 508 and W3C WCAC. String from Erik Bolsø

              W3C_Validator/1.183 libwww-perl/5.64

              Explanation: The W3C validation service string supplied when you request page validation by URI.

              Jigsaw/2.2.0 W3C_CSS_Validator_JFouffa/2.0

              Explanation: The W3C CSS validation service string.
              Potentially Unpleasant Things

              These strings or partial strings may have unknown side effects or be downright malicious depending on who is using them. You may want to know about 'em when they are visiting.

              vobsub

              Explanation: Contributed by Jan Praestkjaer. A CD ripping plug-in. More information may obtained here.

              DigExt

              Explanation: Contributed by John Bridges. 'DigExt' can appear in a MSIE browser string and is potentially pretty nasty. If you ask for content to be available off-line in certain versions of MSIE (we saw it on 5.0 and 4.x) then MSIE will grab a lot of stuff from the sites you visit and slave it in its temporary internet files. Pretty unpleasant stuff since it chews up your and the sites bandwidth. Sounds like a nice option but MS don't tell you the consequences. Now if I just knew where the option was I'd disable it but since I just upgraded to MSIE 6.0 (cos my 5.0 kept crashing after I refused an automatic update - any connection!! Oh and by the way in case you think we are prejudiced we also upgraded to NS6) I can't find any of this stuff and my browser string doesn't show 'DigExt' anymore - is this another mystery! If you are interested to get more info on the pernicious 'DigExt' John provided this link.

              This may have morphed in the first string shown under the MSIE list - it contains 'MSIECrawler' - thanks to Adam Hauner.
              In the wild

              These strings were sent to us from various logs. If you think you know what they are drop us an email and we'll add an explanation. Lines beginning with # indicate our comments and explanations:

              SAGEM-myX5-2/1.0 Profile/MIDP-2.0 Configuration/CLDC-1.0
              UP.Browser/6.2.2.6.d.3 (GUI) MMP/1.0
              # Sagem phone. Update from Don Beele.

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; SV1; FunWebProducts;
              E-nrgyPlus; dial; snprtz|S04770920630454; .NET CLR 1.1.4322)
              # E-nrgyPlus is Adware/Spyware: E-nrgyPlus Dialer. Updated by Don Beele - thanks.

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;
              snprtz|S21696000000680|2600#Service Pack 2#2#5#154321|isdn;
              .NET CLR 1.1.4322)
              # snprtz|S21696000000680|2600#Service Pack 2#2#5#154321|isdn indicates a
              # virus: Trojan.Win32.Dialer.hc. Updated by Don Beele - thanks

              Mozilla/4.0 (compatible; DB Browse 4.3; DB OS 6.0)
              Mozilla/4.0 (compatible; X 10.0; Commodore 64)

              Opera/7.02 Bork-edition (Windows NT 5.0; U) [en]
              # special version of Opera 7 to fix a problem acessing MSN

              Mozilla/4.0 (compatible; AWEB 3.4 SE; AmigaOS)
              # Amiga Aweb

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;
              FunWebProducts; ADVPLUGIN|K114|05|S2096708331|dial; SIMBAR Enabled;
              SIMBAR={0928F776-3240-4451-8757-B474D4642219}; SIMBAR=0;
              .NET CLR 1.1.4322; .NET CLR 2.0.50727)
              # ADVPLUGIN|K114|05|S2096708331|dial indicates a
              # virus: Trojan.Win32.Dialer.hc and
              # SIMBAR={0928F776-3240-4451-8757-B474D4642219}; SIMBAR=0; indicates
              # spyware/adware, update from Don Beele - thanks

              Mozilla/5.0 compatible WebaltBot/1.00 (i686-pc-linux)
              PlantyNet_WebRobot_V1.9 dhkang@plantynet.com
              Firefox 0.9.2 (The intelligent alternative)

              Mozilla/4.7C-SGI [en] (X11; I; IRIX 6.5 IP32)
              # SGI - R5000 CPU at 180 Mhz. Update Don Beele - thanks

              Mozilla/4.7 (compatible; OffByOne; Windows 2000) Webster Pro V3.4
              # offbyone browser. Update by Don Beele - thanks

              psbot/0.1 (+http://www.picsearch.com/bot.html)
              # picsearch robot - indexes images on web. Update from Don Beele - thanks

              Mystery Strings and Questions

              Anyone read, we think, Polish and use a cell phone if so you can probably answer this:

              holmes/3.10.1 (OnetSzukaj/5.0; +http://szukaj.onet.pl)

              Submitted by David Ross (thanks). Answer from Michal Malek. Apparently it is a polish search engine crawler from OnetSzukaj. Many thanks. And he didn't need a cell phone.
              Anyone recognize the IEMB3 in this string:

              Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322;
              IEMB3; IEMB3)

              Submitted by Ineke K (thanks). The most plausible explanation is IE Mobile Browser but with a 7.0 version?
              Anyone recognize this string:

              Bookdog/3.11.4

              This one was submitted and answered by David Ross (thanks). Its BookDog (which apparently replaces Safari Sniffer) and can organize/validate bookmarks for Safari, Camino and Firefox on the Mac. It replaces the normal User-Agent string rather that adds to it which is normal mode for BHO and Plugins - pretty strange even if not a true mystery.
              Anyone recognize this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; BT [build 60A];
              .NET CLR 1.1.4322)

              Any ideas about the BT [build 60A] string? String from John Dobson.

              Alexander Twigg thinks this is the British Telecom Yahoo! browser based on MSIE 6 - and who are we to dispute that - many thanks Alexander.
              Anyone recognize this string:

              Mozilla/4.0 (compatible; MSIE 5.01; Windows 98; Config C)

              Any ideas about the Config C string? String from John Dobson.
              Anyone recognize this string:

              Mozilla/5.0 (Windows; U; Win98; en-US; rv:1.8) Gecko/20051123
              Firefox/1.5 (977517da3442c5cr5yba8n969bd36a6c7e5a)

              Any ideas about the string following Firefox? String from Ryan J.
              Anyone recognize this string:

              KDDI-CA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0

              We suspected it was some kind of Japanese mobile device (IP tracked to Internet Multifeed Co. of Japan). String from David E. Ross. Yeah it's a DoCoMo device. Answer from karawapo who also supplied a huge list of DoCoMo devices.
              Anyone recognize the Secure IE in this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;
              .NET CLR 1.1.4322; Secure IE 3.3.1286)

              String from Lewis Kapell. Possible answer also from Lewis is Secure IE (surprise, surprise).
              Anyone recognize the i-NavFourF in this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; i-NavFourF;
              .NET CLR 1.1.4322)

              String from maffiou. Possible answer also from maffiou is the i-van system from Verisign, Inc. which allows International names in URLs (including DNS). More possibiliies here. Confirmed by Andreas Städing.
              Anyone recognize the TIMET-040122 in this string:

              Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; TIMET-0904;
              TIMET-040122)

              String from D. Latham.
              Anyone recognize the 'AT&T CMS7.0'in this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; AT&T CSM7.0;
              .NET CLR 1.0.3705; .NET CLR 1.1.4322; Hotbar 4.6.1)

              String from Brantley Harris. The hotbar is clearly our good friends hotbar who monitor your traffic. You can remove it here. But the CMS... Turns out it's AT&T Worldnet Customer Support Manager v7.0. Update from Don Beele - thanks.
              Anyone recognize the 'iebar'in this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; iebar)

              String from Willem van Nunen. Its, suprisingly, iebar now why didn't we think about that ('cos we're stupid, that's why). Answer from Chip Downs - thanks.
              Anyone recognize the CS 2000 in this string:

              Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:0.9.4.2)
              Gecko/20021112 CS 2000 7.0/7.0

              String from Willem van Nunen. Answer from Richard Aspden it is "the CompuServe 2000 application, which had Gecko built-in. AOL was going to do the same with it's own native software (AOL owning CS), but decided to use IE in the end anyway.". Many thanks.
              Anyone recognize the ESB stuff in this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0;
              ESB{A8417D9D-5087-4807-9D73-9E09290256CD})

              String from Remco de Vijlder. Axel Kollmorgen, Eugene Sadhu and Jim Rofkar all made suggestions - we moved this one to the BHO section with the best links we can find. Oracle's Fusion Middleware Enterprise Service Bus?
              Anyone recognize the DIL0001021 in this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; DIL0001021)

              String from John Dobson. Answer: Seems is Demon Internet Limited a big UK ISP who customize MSIE. From Lee Harvey Osmond - thanks - who also wants to know what 'YPC' means in a string.
              Anyone recognize the H010818 in this string:

              Mozilla/4.0+(compatible;+MSIE+5.5;+Windows+NT+4.0;+H010818)

              String from Jim Rofkar. Possible Answer: From Eugene Sadhu "Interestingly this string "H010818" is found in the registry of WinME machines and may have some correlation to the WindowsUpdate App. Perhaps a browser used after visiting the WindowsUpdate site will send this string to other machines on the same session. HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\Internet Settings\User Agent\Post Platform\H010818" Many thanks.
              Anyone recognize this CLSID like string in a browser:

              Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.0;+
              {4E449FBB-3E07-4F3B-AF93-9F441086A756};+.NET+CLR+1.1.4322)
              Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.0;+
              {8E13D179-78A2-4C06-9BFC-EA5BF2EE1750})

              String from Jim Rofkar - followed by the answer from Jim also. These may indicate the browser has a BHO (Browser Helper Object) software installed (perhaps unwittingly) from either LOP foistware (site link appears broken) or WurlMedia. This site seems to keep a reasonably up-to-date list of nasty things. If you think you have got some nasty stuff on your browser just type 'hijack' into a google search and follow the most promising links.

              ...SURF...

              Anyone know what application the word SURF in a browser string comes from? String from Erik Nelson. Tyler Bannister writes" One of our students is having a problem with her browser pre-fetching every page she reads in Internet Explorer. The problem doesn't occur in Netscape, thus it seems likely that "SURF" is some type of browser help object for IE and probably malware" - thanks - anyone got more on this one?

              Mozilla/4.01 (Compatible; Acorn Phoenix 2.08 [intermediate]; RISC OS 4.39) Acorn-HTTP/0.84

              Anyone know what the browser being used is? String from Erik ?. Updated answer from Chris Bazley. Apparently the original Acorn Browse in a development version morphed into Phoenix (not to be confused with the pre firebird pre firefox mozilla browser!) on the ACORN RISC OS. For all you ACORN fans however seems there may be a Firefox port called Rozilla.
              Anyone recognize this one:

              mozilla/4.0 (compatible; msie 6.0; windows 98; ypc 3.0.2; yplus 4.4.01d)

              Anyone know what the ypc and yplus is? String from Bruce Preston.

              Answer: It's Yahoo Parental Controls which seems to be available to you if you sign-up with Yahoo. Answer from John Pye - thanks.
              Anyone recognize this one:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; FREE; .NET CLR 1.1.4322)

              Anyone know what the FREE is? String from Jean Christophe Olivain. Axel Kollmorgen writes "seems to be a customized version of IE made by free.fr or a related company. all the "FREE" agents in my logs are from france and their ip's related somehow with free.fr". Thanks Axel. Confirmation from Symon Rottem who says that the isp free provides an installation CD with a customised version of MSIE. This one is now dead - maybe we should move to the MSIE strings.
              Anyone recognize this one:

              Mozilla/4.5 RPT-HTTPClient/0.3-2

              We suspect this may be an e-mail extractor program, but we could be wrong. Anyone got a definitive answer? String from Michael Wilcox.

              Answer: It's an HTTP client library. Answer from Eugene Sadhu - thanks.
              Anyone recognize the KTXN part of this string:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; KTXN)

              Anyone got any idea? String from Chug Yang.

              Answer: KTXN is "Keynote" a website monitoring service. Keynote traffic (evidenced by presence of KTXN in the user agent string) should not be blocked from your web server (because then your reported availability % will be zero!) but it should not be included in your pageviews/visits/etc measurements since that is not real customers' traffic. Answer from Justin Grant - thanks.
              Anyone recognize this one:

              SUNPlex 4.1 (Trusted Solaris 8 Operating Environment; Solaris 8 OE; Sun Fire 15K)

              Seems to be from a SUN Secure cluster - benign application or something more sinister? String from Erik's ? logs.

              We got this in response: "SUNPlex Manager is a web-based administration tool for the SunCluster 3 clustering software. Trusted Solaris is a secured version of the Solaris operating system (I believe it at one point was rated C2 in the Orange Book but I don't know for sure what it is now). Sun Fire 15K is the Godzilla of servers, one of my favorite machines to work on, and makes one awesomungus web browser." Thanks to Joe George.
              Anyone recognize this one:

              Mozilla/3.0 (compatible; HP Web PrintSmart 04b0 1.0.1.34)

              String from Dan Johnson's logs.

              Answer: Eugene Sadhu writes "HP Web PrintSmart software is a tool for creating custom printed documents from Web pages that you retrieve from anywhere on the Web." His view is its probably not available now (we checked and could only a historic reference n HP's pages). Thanks Eugene.
              Anyone recognize this one:

              Mozilla/4.0 (compatible; MSIE 5.0; Win3.1; ATHMWWW1.1;)

              String from Dan Johnsons logs. MSIE 5.0 does not run on windows 3.1 so its something else. Just someone playing with browsers ids? Whenever we are categorical about something you can bet we're wrong. Turns out MSIE 5 can run on Windows 3.1 - answer from Bennett Hatchett - many thanks. ATHWWW1.1 looks to us like the @Home browser a ex-internet cable company that was big in the late 90s.

              Answer: This is the browser string from Excite@home's custom browser so we suspect is version 3.1 runing on a Windows platform - anyone know the base technology e.g. custom MSIE or what. Answer from Eugene Sadhu - thanks.
              Anyone recognize this one:

              Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; .NET CLR 1.0.3705; .NET CLR 1.1.4322)

              Answer: Neal Blomfield writes "The following user agent string listed as a mystery string on your website looks like IE6 on Win2k running both verion 1 (.NET CLR 1.0.3705) and version 1.1 (.NET CLR 1.1.4322) of the .NET framework". Sounds plausible to us. Thanks Neal. Bobby Mcgee also provided this link http://www.httprevealer.com/usage_dotnet.htm to help identify folks using the .NET infrastructure. Thanks Bobby.
              What does 'DigExt' mean (solved - credit to John Bridges see here).
              Anyone recognize the browser or OS:

              Mozilla/4.0 (compatible; ICS 1.2.105)

              Answer: Marc Schneider suggests it's probably from Novell's Internet Caching system. We also got confirmation of this from Eugene Sadhu "It is most probably from the Compaq TaskSmart C-Series server, in fact, one that had the upgrade patch to build 1.2.105 installed (released 15 Sep 2000)" Mystery solved - thanks guys.
              Anyone know what the 'U' is in many Linux browser strings.
              Answer: From Rickard Anglerud - thanks: It defines security level and may have one of the following values:
              N for no security
              U for strong security
              I for weak security
              Anyone recognise this baby?

              Sqworm/2.9.85-BETA (beta_release; 20011115-775; i686-pc-linux
              Submitted by: Mike van Riel
             */
    ];

// Marc Gray's PHP script (untested by us)
// use at your discretion
//    public static function ListAll() {
//        $page = file_get_contents('http://www.zytrax.com/tech/web/mobile_ids.html');
//        preg_match_all('/<(p) class="g-c-[ns]"[^>]*>(.*?)<\/p>/s', $page, $m);
//
//        $agents = array();
//        foreach ($m[2] as $agent) {
//            $split = explode("\n", trim($agent));
//            foreach ($split as $item) {
//                $agents[] = trim($item);
//            }
//        }
//// $agents now holds every user agent string, one per array index, trimmed
//        foreach ($agents as $agent) {
//            echo($agent . "\n");
//        }
//    }

    public static function Test() {
        foreach (self::$UserAgent as $name => $string) {
            list($engine, $version) = self::Explore($string);
            echo "<b>$name</b> : <u>$engine</u> / $version<br><hr>";
        }
        die('STOP');
    }

}
