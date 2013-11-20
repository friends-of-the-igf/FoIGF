## site setup
command line
	$ composer update

requires base silverstripe _ss_environment.php file in web root

## scss
any site changes are made in layout.scss
any bootstrap changes override core classes in layout.scss
any bootstrap var changes made in _variables.scss

## js
thirdparty js goes in the themes/igf/thirdparty dir
custom js goes in the themes/igf/javascript dir

## for live
turn on Session view count through global settings

## search
requires solr setup

### file extraction
run task to extract already existing file content
dev/tasks/FileExtractionTask

## tasks
dev/tasks/FileExtractionTask
dev/tasks/Solr_Configure
dev/tasks/Solr_Reindex