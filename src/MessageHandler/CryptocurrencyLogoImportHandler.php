<?php

namespace App\MessageHandler;

use App\Message\CryptocurrencyLogoImport;
use Aws\S3\S3Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;

#[AsMessageHandler]
class CryptocurrencyLogoImportHandler
{
    public function __construct(
        private S3Client $s3Client,
        private ParameterBagInterface $parameterBag,
    ) {}

    public function __invoke(CryptocurrencyLogoImport $message)
    {
        try {
            $coinMarketCapId = $message->getCoinMarketCapId();
            $logoUrl = sprintf('https://s2.coinmarketcap.com/static/img/coins/64x64/%s.png', $coinMarketCapId);
            $logoPath = sprintf('coinmarketcap/cryptocurrency/%s.png', $coinMarketCapId);

            $this->s3Client->putObject([
                'Bucket' => $this->parameterBag->get('aws_s3_bucket'),
                'Key'    => $logoPath,
                'Body'   => file_get_contents($logoUrl),
                'ACL'    => 'public-read',
            ]);
        } catch (\Throwable $exception) {
            throw new RecoverableMessageHandlingException('', 0, $exception, 3600000);
        }
    }
}
