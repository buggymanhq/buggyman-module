<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace BuggymanModule;

use Zend\Stdlib\AbstractOptions;

class Options extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var string
     */
    protected $publicToken = '';

    /**
     * @var int
     */
    protected $errorLevel;

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->errorLevel = E_ALL | E_STRICT;
        parent::__construct($options);
    }


    /**
     * Set value of Enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Return value of Enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set value of Token
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Return value of Token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set value of ErrorLevel
     *
     * @param int $errorLevel
     */
    public function setErrorLevel($errorLevel)
    {
        $this->errorLevel = $errorLevel;
    }

    /**
     * Return value of ErrorLevel
     *
     * @return int
     */
    public function getErrorLevel()
    {
        return $this->errorLevel;
    }

    /**
     * Set value of PublicToken
     *
     * @param string $publicToken
     */
    public function setPublicToken($publicToken)
    {
        $this->publicToken = $publicToken;
    }

    /**
     * Return value of PublicToken
     *
     * @return string
     */
    public function getPublicToken()
    {
        return $this->publicToken;
    }

}