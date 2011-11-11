FunstaffTikaBundle: Wrapper for tika
====================================

This bundle work with [Apache Tika](http://tika.apache.org/).


Bundle Under development


Configuration
-------------
File config.yml

    funstaff_tika:
        tika_path:  /path/to/tika-app-1.0.jar
        output:     ~


Examples
--------
Extract document with text output:

    $tika = $this->get('funstaff.tika')
            ->setOutputFormat('text')
            ->addDocument('foo', '/path/to/foo')
            ->extractContent();

    foreach ($tika->getDocuments() as $document) {
        $content = $document->getContent();
        $metadata = $document->getMetadata();
        $author = $metadata->get('Author');
    }


Credits
-------
To all users that gave feedback and committed code [https://github.com/Funstaff/FunstaffTikaBundle](https://github.com/Funstaff/FunstaffTikaBundle).