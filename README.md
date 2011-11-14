FunstaffTikaBundle: Wrapper for tika
====================================

This bundle work with [Apache Tika](http://tika.apache.org/).


Configuration
-------------
File config.yml

    funstaff_tika:
        tika_path:      /path/to/tika-app-1.0.jar
        output_format:  ~  # default: xml
        logging:        ~  # Use the Symfony2 default. Force the logging with this param.


Examples
--------

### Extract only the content:
    $tika = $this->get('funstaff.tika')
            ->setOutputFormat('text')
            ->addDocument('foo', '/path/to/foo')
            ->extractContent();

### Extract Only the metadata
    $tika = $this->get('funstaff.tika')
            ...
            ->extractMetadata();

### Extract content and metadata
    $tika = $this->get('funstaff.tika')
            ...
            ->extractAll();

### Work with data
    foreach ($tika->getDocuments() as $document) {
        $content = $document->getContent();
        $metadata = $document->getMetadata();
        $author = $metadata->get('Author');
    }


Credits
-------
To all users that gave feedback and committed code [https://github.com/Funstaff/FunstaffTikaBundle](https://github.com/Funstaff/FunstaffTikaBundle).