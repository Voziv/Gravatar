<?php

namespace lrobert\Gravatar;

/**
 * Gravatar - A library to make working with Gravatar in PHP easy.
 *
 * Gravatar URL breakdown:
 * {BASE_URL}/{HASH}?{[s]}{[r]}{[d]}
 * Base URL: http://www.gravatar.com/avatar/
 * Hash    : 205e460b479e2e5b48aec07710c08d50.jpg
 * ?s=200  : Size in pixels
 * ?r=pg   : Maximum Rating
 * ?d=404  : Default Image. Can be a predefined type or a URL-encoded
 *           URL to your own image
 *
 * @package     lrobert\Gravatar
 * @author      leerobert.ca
 * @license     MIT License (See bundled license)
 * @link        https://github.com/lrobert/Gravatar
 */
class Gravatar
{
    /**
     * Default URL's for gravatar
     */
    const DEFAULT_URL        = 'http://www.gravatar.com/avatar';
    const DEFAULT_SECURE_URL = 'https://secure.gravatar.com/avatar';

    /**
     * List of fallback options
     */
    const FALLBACK_404         = '404';
    const FALLBACK_BLANK       = 'blank';
    const FALLBACK_DEFAULT     = '';
    const FALLBACK_IDENTICON   = 'identicon';
    const FALLBACK_MONSTER_ID  = 'monsterid';
    const FALLBACK_MYSTERY_MAN = 'mm';
    const FALLBACK_RETRO       = 'retro';
    const FALLBACK_WAVATAR     = 'wavatar';

    /**
     * List of ratings
     */
    const RATING_G  = 'g';
    const RATING_PG = 'pg';
    const RATING_R  = 'r';
    const RATING_X  = 'x';

    /**
     * The base url to perform the request on
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The default image to use
     *
     * @var string
     */
    protected $defaultImage;

    /**
     * The whether or not to force the default image
     *
     * @var bool
     */
    protected $forceDefaultImage;

    /**
     * The maximum content rating
     *
     * @var string
     */
    protected $maximumRating;

    /**
     * The default size
     *
     * @var int
     */
    protected $size;

    /**
     * A template url to use with sprintf to inject a hash
     *
     * @var string
     */
    protected $urlTemplate;

    /**
     * Just setting some defaults
     */
    public function __construct()
    {
        $this->baseUrl       = static::DEFAULT_SECURE_URL;
        $this->defaultImage  = static::FALLBACK_DEFAULT;
        $this->maximumRating = static::RATING_G;
        $this->size          = 80;
    }

    /**
     * Gets the current base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Sets the base url
     *
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        unset($this->urlTemplate);

        return $this;
    }

    /**
     * Gets the current default image
     *
     * @return string
     */
    public function getDefaultImage()
    {
        return $this->defaultImage;
    }

    /**
     * Sets the default image
     *
     * @param string $defaultImage
     *
     * @return $this
     */
    public function setDefaultImage($defaultImage)
    {
        $this->defaultImage = $defaultImage;

        unset($this->urlTemplate);

        return $this;
    }

    /**
     * Gets the current force
     *
     * @return bool
     */
    public function getForceDefaultImage()
    {
        return $this->forceDefaultImage;
    }

    /**
     * Sets the force default image flag
     *
     * @param bool $forceDefaultImage
     *
     * @return $this
     */
    public function setForceDefaultImage($forceDefaultImage)
    {
        $this->forceDefaultImage = (bool)$forceDefaultImage;

        unset($this->urlTemplate);

        return $this;
    }

    /**
     * Gets the current maximum rating
     *
     * @return string
     */
    public function getMaximumRating()
    {
        return $this->maximumRating;
    }

    /**
     * Sets the maximum rating
     *
     * @param string $maximumRating
     *
     * @return $this
     */
    public function setMaximumRating($maximumRating)
    {
        $this->maximumRating = $maximumRating;

        unset($this->urlTemplate);

        return $this;
    }

    /**
     * Gets the current size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Sets the size
     *
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = (int)$size;

        unset($this->urlTemplate);

        return $this;
    }

    /**
     * Gets and caches a URL template to use with sprintf
     */
    protected function getUrlTemplate()
    {
        if (!isset($this->urlTemplate)) {
            $parameters = array();

            /**
             * Size
             */
            if ($this->size) {
                $parameters[] = sprintf('s=%s', (int)$this->size);
            }

            /**
             * Maximum Rating
             */
            if ($this->maximumRating) {
                $parameters[] = sprintf('r=%s', urlencode($this->maximumRating));
            }

            /**
             * Default Image
             */
            if ($this->defaultImage) {
                $parameters[] = sprintf('d=%s', urlencode($this->defaultImage));
            }

            /**
             * Default Image
             */
            if ($this->forceDefaultImage) {
                $parameters[] = 'f=y';
            }

            /**
             * Build Template
             */
            if (count($parameters) > 0) {
                $this->urlTemplate = sprintf('%s/%%s.jpg?%s', $this->baseUrl, implode('&', $parameters));
            } else {
                $this->urlTemplate = sprintf('%s/%%s.jpg', $this->baseUrl);
            }
        }

        return $this->urlTemplate;
    }


    /**
     * Gets the URL to the Gravatar
     *
     * @param string $email    The email to get the URL for.
     *
     * @param bool   $autoHash Whether or not we should automatically hash the
     *                         email. Implemented this so I could include the
     *                         hash in tests instead of an actual email address
     *                         to avoid spambots
     *
     * @return string The URL for the Gravatar
     */
    public function getUrl($email, $autoHash = true)
    {
        return sprintf($this->getUrlTemplate(), ($autoHash) ? $this->getHashIdentifier($email) : $email);
    }

    /**
     * Hashes the users email (or other identifier) as per Gravatar's
     * specifications
     *
     * @param string $identifier The email or other string to hash
     *
     * @return string
     */
    public function getHashIdentifier($identifier)
    {
        return hash('md5', strtolower(trim($identifier)));
    }

}
