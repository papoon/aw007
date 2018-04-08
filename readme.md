# Web Applications 2017/2018

## Project Description

The AW 2017/18 project consists in creating a semantic search engine for diseases and scientific articles, with the following components:
*	Data Collection: collecting text and data about diseases (Demo I)
*	Data Annotation: create semantic links between the collected data (Demo II)
*	Data Access: make the information available as a web service and as dynamic web page.

### Data Collection

*	Collect the list of diseases using the DBpedia SPARQL endpoint, for example:
PREFIX dbo: <http://dbpedia.org/ontology/>
SELECT ?name where {
 ?name a dbo:Disease .
}
*	For each disease collect:
*	related articles (titles and abstracts) from PubMed using its web service
*	related posts from Twitter API
*	related pictures from Flicker API
*	related metadata from DBpedia using the SPARQL endpoint
*	Data can be stored using any DBMS (not important for evaluation). Some suggestions:  
*	store images as links to them
*	abstracts as local filenames (raw text files)
*	Instead of using a DBMS you can store all data as tsv files
*	Have an update functionality, so it does not have to download everything from scratch everytime we execute the data collector
*	Tip: start with a limited number of diseases

### Data Annotation

*	For each article recognize disease mentions (NER) using at least one of these tools:
*	MER, example: recognizing diseases in a given text
*	PubTator, example:  recognizing diseases in abstract 29346343
*	Bioportal Annotator, see the documentation
*	For each disease store the most related articles based on: 
*	the number occurrences (TFIDF)
*	having similar diseases in that abstract
*	use DiShIn and the Human Disease Ontology
*	relevance feedback, including explicit feedback and implicit feedback
*	published date

### Data Access

*	a REST Web Service to get and add information about diseases and articles, including at least the following functionalities:w
*	get the top-n related articles for a given disease
*	get the top-n related diseases given a disease
*	get recent tweets and photos, and other metadata about a given disease
*	get the diseases mentioned in a given article and where in text they were recognized
*	add user feedback about the relevance of an article for a given disease
*	add user feedback about the correctness of a disease mentioned in the abstract
*	Notes:
*	the Web Service must return JSON, and have the option to additionally return XML if specified in the request HTTP Header
*	provide online documention describing the Web Service API 
*	a dynamic web page using as much as possible to AJAX, including at least the following functionalities:
*	search for diseases with autocomplete and recommendations of related diseases
*	show articles, tweets, photos and metadata about a disease
*	show the recognized diseases in a given article and click to search for any of those
*	receive explicit and implicit feedback from the user 
*	use microformats, example Scholarly Article schema
*	validate them using Google structured data testing tool
*	more examples
*	Notes:
*	Macromedia Flash or Java Applets is not allowed

### Documentation

*	Provide online documentation describing:
*	the data collected with statistics and examples  (Demo I)
*	the annotations stored for each disease and article, with statistics and examples (Demo II)
*	the Web Service API, with usage examples 
*	video trailer (~1minute) of the dynamic web page uploaded in YouTube, with the tag: "Web Applications 2017/18 FCUL"
*	Submit a final report in the course's website as a single pdf file:
*	one page report with:
*	group number, and with the number, name of each team member
*	links to online documentation and video trailer
*	table with work distribution between team members
*	code listings in an annex (only original code)
*	file named AW1617_group??.pdf (replace ?? by the respective two digit number)

### Evaluation

*	Developing additional functionalities will be valued.
*	requires Group Selection 
*	Project Demonstrations I and II 
*	5 minutes per group (prepare it beforehand)
*	select a Demo I slot and Demo II slot
*	show the online documentation and demonstrate with some examples (see section Documentation) 
*	each demo will contribute to 10% of the final project grade
*	Project Demonstration III
*	submit the final report (see Submission)
*	select a Demo III slot 
*	10 minutes per group showing all work done (prepare it beforehand)
*	80% of the final project grade
*	failing to attend this demo (without justification) equals desqualification from the project
*	individual questions about the work done can be asked to a specific team member
*	Software
*	All components of the developed work should be working at appserver.alunos.di.fc.ul.pt during all demos 
*	the software can be developed in other computer but at each demo has to be running in appserver 
*	Some resources may block access to DI-FCUL when the number of requests is too high. As such, it is the group reponsability to guarantee that even when this occurs, their project is working and presents data previously retrieved.
*	All components of the developed work should be available during the evaluation;

## Documentation

### Demo I

### Demo II

### Demo III

## Built With

* [PHP5](http://php.net) - The server side programming language
* [MySql](https://www.mysql.com) - The DBMS 
* [Git](https://git-scm.com) - Version control system
* [BootStrap](https://getbootstrap.com) - CSS FrameWork
* [Twig](https://twig.symfony.com) - Template Engine for PHP
* [ChartJs](http://www.chartjs.org) - Simple yet flexible JavaScript charting for designers & developers
* [autoComplete](https://goodies.pixabay.com/javascript/auto-complete/demo.html) - An extremely lightweight and powerful vanilla JavaScript completion suggester

## Versioning

We use GitHub for hosting versioning. For the versions available at (https://github.com/papoon/aw007). 

## Authors

* **Fábio Martins** - - [papoon](https://github.com/papoon)
* **Joana Matos** - - [jssm](https://github.com/jssm)
* **Rodrigo Matos** - - [RCMatos](https://github.com/RCMatos)
* **Sara Gonçalves** - - [saragoncalves](https://github.com/saragoncalves)

See also the list of [contributors](https://github.com/papoon/aw007/contributors) who participated in this project.

## License


## Acknowledgments

