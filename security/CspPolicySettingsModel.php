<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\security;

use framework\core\HttpRequest;

readonly class CspPolicySettingsModel
{
    public const string PROTOCOL_PLACEHOLDER = '{PROTOCOL}';
    public const string HOST_PLACEHOLDER = '{HOST}';

    // Content Security Policy Reference: https://content-security-policy.com/

    public function __construct(
        private string $defaultSrc = "'self' data: " . CspPolicySettingsModel::PROTOCOL_PLACEHOLDER . "://" . CspPolicySettingsModel::HOST_PLACEHOLDER,
        private string $styleSrc = "'self'",
        private string $fontSrc = "'self'",
        private string $imgSrc = "'self' data: " . CspPolicySettingsModel::PROTOCOL_PLACEHOLDER . "://" . CspPolicySettingsModel::HOST_PLACEHOLDER,
        private string $objectSrc = "'none'",
        private string $mediaSrc = '',
        private string $scriptSrc = "'strict-dynamic'",
        private string $connectSrc = "'none'",
        private string $baseUri = "'self'",
        private string $frameSrc = "'none'",
        private string $frameAncestors = "'none'",
    ) {
    }

    public function getHttpHeaderDataString(string $nonce): string
    {
        $dataArray = [];

        if ($this->defaultSrc !== '') {
            $dataArray[] = 'default-src ' . $this->defaultSrc;
        }
        if ($this->styleSrc !== '') {
            $val = $this->styleSrc;
            if (
                !str_contains(haystack: $val, needle: "'none'")
                && !str_contains(haystack: $val, needle: "'unsafe-inline'")
                && $nonce !== ''
            ) {
                $val .= " 'nonce-" . $nonce . "'";
            }
            $dataArray[] = 'style-src ' . $val;
        }
        if ($this->fontSrc !== '') {
            $dataArray[] = 'font-src ' . $this->fontSrc;
        }
        if ($this->imgSrc !== '') {
            $dataArray[] = 'img-src ' . $this->imgSrc;
        }
        if ($this->objectSrc !== '') {
            $dataArray[] = 'object-src ' . $this->objectSrc;
        }
        if ($this->mediaSrc !== '') {
            $dataArray[] = 'media-src ' . $this->mediaSrc;
        }
        if ($this->scriptSrc !== '') {
            $val = $this->scriptSrc;
            if (
                !str_contains(haystack: $val, needle: "'none'")
                && !str_contains(
                    haystack: $val,
                    needle: "'unsafe-inline'"
                )
                && $nonce !== ''
            ) {
                $val .= " 'nonce-" . $nonce . "'";
            }
            $dataArray[] = 'script-src ' . $val;
        }
        if ($this->connectSrc !== '') {
            $dataArray[] = 'connect-src ' . $this->connectSrc;
        }
        if ($this->baseUri !== '') {
            $dataArray[] = 'base-uri ' . $this->baseUri;
        }
        if ($this->frameSrc !== '') {
            $dataArray[] = 'frame-src ' . $this->frameSrc;
        }
        if ($this->frameAncestors !== '') {
            $dataArray[] = 'frame-ancestors ' . $this->frameAncestors;
        }

        return (count(value: $dataArray) === 0) ? '' : implode(
                separator: '; ',
                array: array_map(
                    callback: function ($value) {
                        return str_replace(
                            search: [
                                CspPolicySettingsModel::PROTOCOL_PLACEHOLDER,
                                CspPolicySettingsModel::HOST_PLACEHOLDER,
                            ],
                            replace: [
                                HttpRequest::getProtocol(),
                                HttpRequest::getHost(),
                            ],
                            subject: $value
                        );
                    },
                    array: $dataArray
                )
            ) . ';';
    }
}