<?php

declare(strict_types=1);

namespace Jfcherng\IpLocation;

use ipip\db\Reader as ipdbReader;

final class IpLocation
{
    /**
     * @var self
     */
    private static $self;

    /**
     * The ipipReader instances.
     *
     * @var \ipip\db\Reader[]
     */
    private static $ipdbReaders;

    /**
     * Ipdb fields and their default values.
     *
     * @var array
     */
    private static $ipdbFields = [
        'country_name' => '', // 國家名稱
        'region_name' => '', // 區域名稱，中國為省份
        'city_name' => '', // 城市名稱，中國為市級
        'owner_domain' => '', // 擁有者域名
        'isp_domain' => '', // 運營商名稱
    ];

    /**
     * The options.
     *
     * @var array
     */
    private $options = [
        // the ipip DB file location
        'ipipDb' => __DIR__ . '/db/ipipfree.ipdb',
        // the cz88 DB file location
        'cz88Db' => __DIR__ . '/db/qqwry.ipdb',
    ];

    /**
     * The constructor.
     */
    private function __construct()
    {
    }

    /**
     * The destructor.
     */
    public function __destruct()
    {
        foreach (self::$ipdbReaders as $reader) {
            $reader->close();
        }
    }

    /**
     * Setup properties for this class.
     *
     * @param array $options the options
     */
    public function setup(array $options): void
    {
        foreach ($options as $key => $value) {
            if (\array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            }
        }
    }

    /**
     * Get the instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        return self::$self = self::$self ?? new self();
    }

    /**
     * Find IP location information.
     *
     * @param string $ip the IP/hostname string
     *
     * @throws \InvalidArgumentException
     *
     * @return array the IP location information
     */
    public function find(string $ip): array
    {
        $ip = \strtolower(\trim($ip));

        // try to convert non-IP to IP
        if (!\preg_match('/^[0-9a-f.:]++$/u', $ip)) {
            $ip = \gethostbyname($ip);
        }

        // the input cannot be an valid IP
        if (
            !\filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4) &&
            !\filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)
        ) {
            throw new \InvalidArgumentException("Invalid IP: {$ip}");
        }

        $results = \array_merge(
            $this->findFromIpdb($ip, $this->options['cz88Db']),
            // prefer IPIP's result
            $this->findFromIpdb($ip, $this->options['ipipDb'])
        );

        return $this->addIpdbMissingFields($results);
    }

    /**
     * Look up IP location information from ipip DB.
     *
     * @see https://github.com/ipipdotnet/ipdb-php
     *
     * @param string $ip     the IP string
     * @param string $dbFile the path of database file
     *
     * @return array the lookup result
     */
    private function findFromIpdb(string $ip, string $dbFile): array
    {
        if (!isset(self::$ipdbReaders[$dbFile])) {
            self::$ipdbReaders[$dbFile] = new ipdbReader($dbFile);
        }

        $reader = self::$ipdbReaders[$dbFile];

        return $reader->findMap($ip) ?? [];
    }

    /**
     * Add missing fields for the IPDB lookup result.
     *
     * @param array $result the ipdb result
     *
     * @return array
     */
    private function addIpdbMissingFields(array $result): array
    {
        foreach (self::$ipdbFields as $field => $default) {
            $result[$field] = $result[$field] ?? $default;
        }

        return $result;
    }
}
