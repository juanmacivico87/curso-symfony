<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeExtension extends AbstractExtension
{
    const DEFAULT_CONFIGURATION = [
        'format' => 'Y-m-d H:i:s',
    ];

    public function getFilters()
    {
        return [
            new TwigFilter('time', [$this, 'timeFormat']),
        ];
    }

    public function timeFormat(DateTime $date, array $configuration = []): string
    {
        $configuration = array_merge(self::DEFAULT_CONFIGURATION, $configuration);
        $currentDate   = new DateTime();
        $diffInSeconds = $currentDate->getTimestamp() - $date->getTimestamp();

        if ($diffInSeconds <= 60)
            return 'created now';

        if ($diffInSeconds > 60 && $diffInSeconds <= 3600)
            return 'created recently';

        if ($diffInSeconds > 3600 && $diffInSeconds <= 18000)
            return 'created some hours ago';

        return $date->format($configuration['format']);
    }
}
