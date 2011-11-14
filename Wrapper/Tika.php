<?php

namespace Funstaff\TikaBundle\Wrapper;

use Funstaff\TikaBundle\Wrapper\TikaInterface;
use Symfony\Bridge\Monolog\Logger;

/**
 * Tika
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class Tika implements TikaInterface
{
    protected $configuration;

    protected $outputFormat;

    protected $document = array();

    protected $documentClass;

    protected $metadataClass;

    protected $logger;

    protected $logging;

    /**
     * Construct
     *
     * @param Array $configuration
     * @param string $documentClass (path)
     * @param string $metadataClass (path)
     * @param Logger $logger
     */
    public function __construct(array $configuration, $documentClass, $metadataClass, Logger $logger)
    {
        $this->configuration = $configuration;
        $this->documentClass = $documentClass;
        $this->metadataClass = $metadataClass;
        $this->logger = $logger;
        $this->logging = $configuration['logging'];
        $this->outputFormat = ($configuration['output_format']) ? : 'xml';
    }

    /**
     * Set Output format
     *
     * @param string $output
     *
     * @return this
     */
    public function setOutputFormat($outputFormat)
    {
        if (!in_array($outputFormat, array('xml', 'html', 'text'))) {
            throw new \InvalidArgumentException('Not Authorized values (only xml, html and text)');
        }

        $this->outputFormat = $outputFormat;

        return $this;
    }

    /**
     * Get Output format
     *
     * @return $string
     */
    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    /**
     * Add document
     *
     * @param string $name
     * @param string $path
     *
     * @return this
     */
    public function addDocument($name, $path)
    {
        $this->document[$name] = new $this->documentClass($name, $path);

        return $this;
    }

    /**
     * Get Document
     *
     * @param string $name
     *
     * @return array
     */
    public function getDocument($name)
    {
        return $this->document[$name];
    }

    /**
     * Get Documents
     *
     * @return array
     */
    public function getDocuments()
    {
        return $this->document;
    }

    /**
     * Set Logging
     *
     * @param boolean $logging
     */
    public function setLogging($logging)
    {
        $this->logging = $logging;
    }

    /**
     * Get Logging
     *
     * @return $logging
     */
    public function getLogging()
    {
        return $this->logging;
    }

    /**
     * Extract Content
     *
     * @ return void
     */
    public function extractContent()
    {
        ob_start();
        $command = $this->generateTikaCommand($this->outputFormat);
        foreach ($this->document as $doc) {
            if ($this->logger && $this->logging) {
                $this->logger->info(sprintf('Tika extract content: %s', $doc->getPath()));
            }
            passthru(sprintf("$command %s", $doc->getPath()));
            $output = ob_get_clean();
            $doc->setContent($output);
        }
    }

    /**
     * Extract Metadata
     *
     * @return void
     */
    public function extractMetadata()
    {
        ob_start();
        $command = $this->generateTikaCommand('meta');
        foreach ($this->document as $doc) {
            if ($this->logger && $this->logging) {
                $this->logger->info(sprintf('Tika extract metadata: %s', $doc->getPath()));
            }
            passthru(sprintf("$command %s", $doc->getPath()));
            $output = ob_get_clean();
            $doc->setMetadata(new $this->metadataClass($output));
        }
    }

    /**
     * Extract All
     *
     * @return void
     */
    public function extractAll()
    {
        $this->extractContent();
        $this->extractMetadata();
    }

    /**
     * Generate Tika Command
     *
     * @param string $flag
     *
     * @return string Tika base command
     */
    protected function generateTikaCommand($flag)
    {
        if (count($this->document) == 0) {
            throw new \InvalidArgumentException('Add document before run extract function');
        }

        $tikaCommand = sprintf('java -jar %s %s',
                                $this->configuration['tika_path'],
                                $this->getOutputFlag($flag)
                                );

        return $tikaCommand;
    }

    /**
     * Get Output Flag
     *
     * @param string $outputFormat
     *
     * @return string flag
     */
    protected function getOutputFlag($outputFormat)
    {
        $flag = '';
        switch ($outputFormat) {
            case 'xml':
            $flag = '-x';
            break;
            case 'html':
            $flag = '-h';
            break;
            case 'text':
            $flag = '-t';
            break;
            case 'meta':
            $flag = '-m';
            break;
        }

        return $flag;
    }
}