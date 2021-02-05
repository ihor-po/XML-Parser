<?php


namespace App\Components\AddressParser;


class AddressParser
{
    private const UNUSED_ADDRESS_INFO_REGEX = '/^(Україна|УКРАЇНА|україна)(, )([0-9]){5}(, )|^([0-9]){5}(, )|^(Україна|УКРАЇНА|україна)(, )/is';

    private const REGION_PATTERN = '/(обл\.|область|о\.|ОБЛ\.)/is';

    private const PLACE_PATTERN = '/(м\.|місто|селище міського типу|село|М\.|С\.)/is';

    private const DISTRICT_PATTERN = '/((р-н)|район|селище міського типу|(СМТ.)|РАЙОН)/is';

    private const STREET_PATTERN = '/(ПРОВУЛОК|ВУЛ\.|ВУЛИЦЯ|ПЛОЩА|ЖИТЛОВИЙ МАСИВ|М-Н|ПРОЇЗД|ПРОСПЕКТ)/is';

    private const HOUSE_PATTERN = '/(будинок|буд\.|БУДИНОК|БУД\.|Б\.|б\.)/is';
//    private const HOUSE_PATTERN = '/((((б?|Б?)(.)?(уд|УД)?)(.)?)((инок)|(ИНОК)))( )?((\d+([а-яА-Я])?))/i';

    private const NUM_PATTERN = '/(кв\.|офіс|квартира|кімната|приміщення)/is';
//    private const NUM_PATTERN = '/(((кв(артира)?(.)?))|(офіс)|(кімната)|(приміщення))+( )?(\d+.*[^.,])/i';

    private $addressPartPatterns = [
        'region' => self::REGION_PATTERN,
        'place' => self::PLACE_PATTERN,
        'district' => self::DISTRICT_PATTERN,
        'street' => self::STREET_PATTERN,
        'house' => self::HOUSE_PATTERN,
        'num' => self::NUM_PATTERN
    ];

    /**
     * @param string $address
     */
    public function parse(string $address)
    {
        $address = $this->removeUnusedInformation(static::UNUSED_ADDRESS_INFO_REGEX, $address);
        $addressParts = explode(',', $address);
        $parts = $this->getParts($addressParts);

        return $parts;
    }

    /**
     * @param array $addressParts
     * @return array
     */
    private function getParts(array $addressParts): array
    {
        $result = [];
        foreach ($this->addressPartPatterns as $key => $pattern) {
            $result[$key] = $this->getAddressPart($pattern, $addressParts);
        }

        return $result;
    }

    /**
     * @param string $pattern
     * @param string $target
     * @return string|string[]|null
     */
    private function removeUnusedInformation(string $pattern, string $target)
    {
        return preg_replace($pattern, '', $target);
    }

    /**
     * @param string $pattern
     * @param array $addressParts
     * @return string
     */
    private function getAddressPart(string $pattern, array $addressParts): string
    {
        $part = $this->searchAddressPart($pattern, $addressParts);

        if (!empty($part)) {
            return $this->clearAddressPart($pattern, $part);
        }

        return '';
    }

    /**
     * @param string $pattern
     * @param string $addressPart
     * @return string
     */
    private function clearAddressPart(string $pattern, string $addressPart): string
    {
        $addressPart = $this->removeUnusedInformation($pattern, $addressPart);
        return trim($addressPart);
    }

    /**
     * @param string $pattern
     * @param array $addressParts
     * @return string
     */
    private function searchAddressPart(string $pattern, array $addressParts): string
    {
        foreach ($addressParts as $part) {
            if (preg_match($pattern, $part)) {
                return $part;
            }
        }
        return '';
    }
}