<?php

namespace rednaoeasycalculationforms\core\Managers;

use HtmlSanitizer\Sanitizer;
use rednaoeasycalculationforms\core\Loader;

class HTMLSanitizer
{
    /** @var Loader */
    public $loader;
    /** @var Sanitizer */
    private $sanitizer;
    public function __construct($loader)
    {
        $this->loader=$loader;
        require_once $this->loader->DIR.'vendor/autoload.php';

        $this->sanitizer=\HtmlSanitizer\Sanitizer::create([
            'extensions' => ['basic', 'code', 'image', 'list', 'table'],
            'tags' => [
                'abbr' => [
                    'allowed_attributes' => ['style'],
                ],
                'a' => [
                    'allowed_attributes' => ['href', 'title','style'],
                    'allowed_hosts' => null,
                    'allow_mailto' => true,
                    'force_https' => false,
                ],
                'blockquote' => [
                    'allowed_attributes' => ['style'],
                ],
                'br' => [
                    'allowed_attributes' => ['style'],
                ],
                'caption' => [
                    'allowed_attributes' => ['style'],
                ],
                'code' => [
                    'allowed_attributes' => ['style'],
                ],
                'dd' => [
                    'allowed_attributes' => ['style'],
                ],
                'del' => [
                    'allowed_attributes' => ['style'],
                ],
                'details' => [
                    'allowed_attributes' => ['open','style'],
                ],
                'div' => [
                    'allowed_attributes' => ['style'],
                ],
                'dl' => [
                    'allowed_attributes' => ['style'],
                ],
                'dt' => [
                    'allowed_attributes' => ['style'],
                ],
                'em' => [
                    'allowed_attributes' => ['style'],
                ],
                'figcaption' => [
                    'allowed_attributes' => ['style'],
                ],
                'figure' => [
                    'allowed_attributes' => ['style'],
                ],
                'h1' => [
                    'allowed_attributes' => ['style'],
                ],
                'h2' => [
                    'allowed_attributes' => ['style'],
                ],
                'h3' => [
                    'allowed_attributes' => ['style'],
                ],
                'h4' => [
                    'allowed_attributes' => ['style'],
                ],
                'h5' => [
                    'allowed_attributes' => ['style'],
                ],
                'h6' => [
                    'allowed_attributes' => ['style'],
                ],
                'hr' => [
                    'allowed_attributes' => ['style'],
                ],
                'iframe' => [
                    'allowed_attributes' => ['src', 'width', 'height', 'frameborder', 'title', 'allow', 'allowfullscreen','style'],

                    /*
                     * If an array is provided, iframes relying on other hosts than one in this array
                     * will be disabled (the `src` attribute will be blank). This can be useful if you want
                     * to prevent iframes contacting external websites.
                     * Any allowed domain also includes its subdomains.
                     *
                     * Example:
                     *      'allowed_hosts' => ['trusted1.com', 'google.com'],
                     */
                    'allowed_hosts' => null,

                    /*
                     * If true, all frames URLS using the HTTP protocol will be rewritten to use HTTPS instead.
                     */
                    'force_https' => false,
                ],
                'img' => [
                    'allowed_attributes' => ['src', 'alt', 'title','style'],

                    /*
                     * If an array is provided, images relying on other hosts than one in this array
                     * will be disabled (the `src` attribute will be blank). This can be useful if you want
                     * to prevent images contacting external websites. Keep null to allow all hosts.
                     * Any allowed domain also includes its subdomains.
                     *
                     * Example:
                     *      'allowed_hosts' => ['trusted1.com', 'google.com'],
                     */
                    'allowed_hosts' => null,

                    /*
                     * If true, images data-uri URLs will be accepted.
                     */
                    'allow_data_uri' => true,

                    /*
                     * If true, all images URLs using the HTTP protocol will be rewritten to use HTTPS instead.
                     */
                    'force_https' => false,
                ],
                'i' => [
                    'allowed_attributes' => ['style'],
                ],
                'li' => [
                    'allowed_attributes' => ['style'],
                ],
                'ol' => [
                    'allowed_attributes' => ['style'],
                ],
                'pre' => [
                    'allowed_attributes' => ['style'],
                ],
                'p' => [
                    'allowed_attributes' => ['style'],
                ],
                'q' => [
                    'allowed_attributes' => ['style'],
                ],
                'rp' => [
                    'allowed_attributes' => ['style'],
                ],
                'rt' => [
                    'allowed_attributes' => ['style'],
                ],
                'ruby' => [
                    'allowed_attributes' => ['style'],
                ],
                'small' => [
                    'allowed_attributes' => ['style'],
                ],
                'span' => [
                    'allowed_attributes' => ['style'],
                ],
                'strong' => [
                    'allowed_attributes' => ['style'],
                ],
                'sub' => [
                    'allowed_attributes' => ['style'],
                ],
                'summary' => [
                    'allowed_attributes' => ['style'],
                ],
                'sup' => [
                    'allowed_attributes' => ['style'],
                ],
                'table' => [
                    'allowed_attributes' => ['style'],
                ],
                'tbody' => [
                    'allowed_attributes' => ['style'],
                ],
                'td' => [
                    'allowed_attributes' => ['style'],
                ],
                'tfoot' => [
                    'allowed_attributes' => ['style'],
                ],
                'thead' => [
                    'allowed_attributes' => ['style'],
                ],
                'th' => [
                    'allowed_attributes' => ['style'],
                ],
                'tr' => [
                    'allowed_attributes' => ['style'],
                ],
                'u' => [
                    'allowed_attributes' => ['style'],
                ],
                'ul' => [
                    'allowed_attributes' => ['style'],
                ]]
        ]);
    }

    public function Sanitize($text)
    {
        return $this->sanitizer->sanitize($text);
    }
}