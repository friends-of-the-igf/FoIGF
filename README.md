# Friend of the IGF
-------------------
##Licence
Copyright 2013 Stripe The Web Ltd. 

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

## Overview
The goal of this project is to create a website to host Internet Governance Forum content - transcripts, video, background documents - in a simple and searchable way, increasing the accessibility and visibility of Internet Governance Forum discussions for the Internet community. The site is built using the SilverStripe CMS and uses Solr for search. 

## Administrators

	* Dylan Sweetensen - <dylan (at) stripetheweb (dot) com>
	* ?? ?? - <?? (at) stripetheweb (dot) com>

Website: http://friendsoftheigf.org
Github: 

## Requirements

	* SilverStripe 3.0.x [http://www.silverstripe.org/] (included in composer)
	* Base SilverStripe _ss_environment.php file in web root [http://doc.silverstripe.org/framework/en/topics/environment-management]
	* FullTextSearch module [https://github.com/silverstripe-labs/silverstripe-fulltextsearch] (included in composer)
	* Solr for Search [https://cwiki.apache.org/confluence/display/solr/Installing+Solr] (included in FullTextSearch package)
	* Compass Module [https://github.com/silverstripe-labs/silverstripe-compass] (included in composer)
	* SortableGridField 0.2.0 [https://github.com/UndefinedOffset/SortableGridField] (included in composer)
	* Text Extraction Module [http://addons.silverstripe.org/add-ons/silverstripe/textextraction] (included in composer)


## Installation

Open Terminal or Command line and change directory to the website's base directory. Run $ composer update. [http://getcomposer.org/]
Grant read/write permissions to the entire site and make an initial dev/build?flush=all.

##Search Set Up

1. Open terminal or command line and switch the directory to following path:
> [your webroot]/fulltextsearch/thirdparty/solr/server
2. Enter the command $ java -jar start.jar
3. To verify that it Solr is now running you can visit http://localhost:8983/solr/
4. You will need to run the Configure and Reindex tasks located at /dev/tasks/Solr_Configure and /dev/tasks/Solr_Reindex respectively. 

To stop Solr running press control+C in the terminal window. 




