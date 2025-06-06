<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

namespace framework\phone;

class PhoneRenderer
{
    public static function renderInternalFormat(PhoneNumber $phoneNumber): string
    {
        return PhoneConstants::PLUS_SIGN . $phoneNumber->countryCode . '.' . $phoneNumber->nationalNumber;
    }

    public static function renderInternationalFormat(PhoneNumber $phoneNumber): string
    {
        $countryCallingCode = $phoneNumber->countryCode;
        $nationalSignificantNumber = $phoneNumber->getNationalSignificantNumber();

        if (!PhoneRegionCountryCodeMap::countryCodeExists(countryCodeToCheck: $countryCallingCode)) {
            return $nationalSignificantNumber;
        }

        // Note getRegionCodeForCountryCode() is used because formatting information for regions which share a country calling code is contained by only one region for performance reasons.
        // For example, for NANPA regions it will be contained in the metadata for US.
        $regionCode = PhoneRegionCountryCodeMap::getRegionCodeForCountryCode(countryCallingCode: $countryCallingCode);
        // Metadata cannot be null because the country calling code is valid (which means that the region code cannot be ZZ and must be one of our supported region codes).
        $phoneMetaData = PhoneMetaData::getForRegionOrCallingCode(
            countryCallingCode: $countryCallingCode,
            regionCode: $regionCode
        );
        $formattedNumber = PhoneRenderer::formatNsn(
            nationalSignificantNumber: $nationalSignificantNumber,
            phoneMetaData: $phoneMetaData
        );
        PhoneRenderer::maybeAppendFormattedExtension(
            phoneNumber: $phoneNumber,
            phoneMetaData: $phoneMetaData,
            formattedNumber: $formattedNumber
        );

        return PhoneConstants::PLUS_SIGN . $countryCallingCode . ' ' . $formattedNumber;
    }

    private static function formatNsn(string $nationalSignificantNumber, PhoneMetaData $phoneMetaData): string
    {
        $intlNumberFormats = $phoneMetaData->intlNumberFormats();
        // When the intlNumberFormats exists, we use that to format national number for the INTERNATIONAL format instead of using the numberDesc.numberFormats.
        $availableFormats = (count($intlNumberFormats) === 0) ? $phoneMetaData->numberFormats() : $intlNumberFormats;
        $formattingPattern = PhoneRenderer::chooseFormattingPatternForNumber(
            availableFormats: $availableFormats,
            nationalNumber: $nationalSignificantNumber
        );

        return is_null($formattingPattern) ? $nationalSignificantNumber : PhoneRenderer::formatNsnUsingPattern(
            nationalSignificantNumber: $nationalSignificantNumber,
            formattingPattern: $formattingPattern
        );
    }

    /**
     * @param PhoneFormat[] $availableFormats
     * @param string $nationalNumber
     *
     * @return PhoneFormat|null
     */
    private static function chooseFormattingPatternForNumber(
        array $availableFormats,
        string $nationalNumber
    ): ?PhoneFormat {
        foreach ($availableFormats as $numFormat) {
            $leadingDigitsPatternMatcher = null;
            $size = $numFormat->leadingDigitsPatternSize();
            // We always use the last leading_digits_pattern, as it is the most detailed.
            if ($size > 0) {
                $leadingDigitsPatternMatcher = new PhoneMatcher(
                    pattern: $numFormat->getLeadingDigitsPattern($size - 1),
                    subject: $nationalNumber
                );
            }
            if ($size == 0 || $leadingDigitsPatternMatcher->lookingAt()) {
                $m = new PhoneMatcher(
                    pattern: $numFormat->pattern,
                    subject: $nationalNumber
                );
                if ($m->matches() > 0) {
                    return $numFormat;
                }
            }
        }

        return null;
    }

    private static function formatNsnUsingPattern(
        string $nationalSignificantNumber,
        PhoneFormat $formattingPattern
    ): string {
        return new PhoneMatcher(
            pattern: $formattingPattern->pattern,
            subject: $nationalSignificantNumber
        )->replaceAll(replacement: $formattingPattern->format);
    }

    private static function maybeAppendFormattedExtension(
        PhoneNumber $phoneNumber,
        PhoneMetaData $phoneMetaData,
        string &$formattedNumber
    ): void {
        if (mb_strlen(string: $phoneNumber->extension) > 0) {
            if ($phoneMetaData->hasPreferredExtnPrefix()) {
                $formattedNumber .= $phoneMetaData->preferredExtnPrefix . $phoneNumber->extension;
            } else {
                $formattedNumber .= PhoneConstants::DEFAULT_EXTN_PREFIX . $phoneNumber->extension;
            }
        }
    }
}